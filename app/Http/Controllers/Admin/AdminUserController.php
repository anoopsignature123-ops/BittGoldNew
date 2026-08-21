<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserReferral;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MLMService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    protected function authorizeAdmin()
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'admin') {
            return redirect()->route('admin.login');
        }

        return null;
    }

    public function index(Request $request)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        $memberQuery = User::with(['role', 'sponsor', 'referralRecord'])
            ->whereHas('role', function ($query) {
                $query->where('slug', 'user');
            });

        $stats = [
            'total' => (clone $memberQuery)->count(),
            'active' => (clone $memberQuery)->where('status', 'active')->count(),
            'inactive' => (clone $memberQuery)->where('status', 'inactive')->count(),
            'activated' => (clone $memberQuery)->whereNotNull('activated_at')->count(),
        ];

        // Search filter (Name, Email, Mobile, Referral Code)
        if ($request->filled('search')) {
            $search = $request->search;
            $memberQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'activated') {
                $memberQuery->whereNotNull('activated_at');
            } elseif ($request->status === 'inactivated') {
                $memberQuery->whereNull('activated_at');
            } elseif (in_array($request->status, ['active', 'inactive', 'pending'])) {
                $memberQuery->where('status', $request->status);
            }
        }

        $users = $memberQuery->withCount('referrals')
            // ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }


        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'sponsor_referral_code' => ['required', 'string', 'max:100', 'exists:users,referral_code'],
        ]);

        $userRole = Role::find(2) ?? Role::where('slug', 'user')->firstOrFail();
        $roleId = $userRole->id;

        $sponsor = User::where('referral_code', $request->sponsor_referral_code)->firstOrFail();
        $referralCode = $this->generateReferralCode();

        $user = User::create([
            'role_id' => $roleId,
            'sponsor_id' => $sponsor->id,
            'referral_code' => $referralCode,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => '+91',
            'status' => 'inactive',

            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'email_verified_at' => now(),
        ]);

        UserReferral::create([
            'user_id' => $user->id,
            'sponsor_id' => $sponsor->id,
            'sponsor_referral_code' => $sponsor->referral_code,
        ]);

        try {
            send_template_email('welcome-user', $user->email, [
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->country_code . ' ' . $user->mobile,
                'userId' => $user->referral_code,
                'plain_password' => $request->password,
                'activation_link' => route('user.login'),
                'referrByName' => $sponsor->name,
                'referrById' => $sponsor->referral_code,
                'referrByEmail' => $sponsor->email,
                'logo' => url('assets/images/logo/logo.png'),
                'site_name' => config('app.name', 'BittGold'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Admin-created user welcome email could not be sent.', ['user_id' => $user->id, 'exception' => $exception->getMessage()]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully and welcome email sent.');
    }

    public function edit(User $user)
    {
        // dd($user); <-- Ise yahan se hata dein!

        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        return view('admin.users.edit', ['editingUser' => $user]);
    }
    public function update(Request $request, User $user)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'country_code' => ['nullable', 'string', 'max:10'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            'sponsor_referral_code' => ['required', 'string', 'max:100', 'exists:users,referral_code'],
        ]);

        $sponsor = User::where('referral_code', $request->sponsor_referral_code)->firstOrFail();
        if ($sponsor->id === $user->id) {
            return back()->withErrors(['sponsor_referral_code' => 'A user cannot be their own sponsor.'])->withInput();
        }

        $referralCode = $request->referral_code ?: $user->referral_code;
        if ($referralCode !== $user->referral_code) {
            while (User::where('referral_code', $referralCode)->where('id', '!=', $user->id)->exists()) {
                $referralCode = strtoupper(Str::random(8));
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => $request->country_code,
            'sponsor_id' => $sponsor->id,
        ]);

        UserReferral::updateOrCreate(
            ['user_id' => $user->id],
            ['sponsor_id' => $sponsor->id, 'sponsor_referral_code' => $sponsor->referral_code]
        );

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
                'plain_password' => $request->password,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        if ($user->id === session('auth.user_id')) {
            return back()->with('error', 'You cannot delete the currently signed-in admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function previewDashboard(User $user)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        if ($user->role?->slug !== 'user') {
            return back()->with('error', 'Only member accounts can be previewed in the user dashboard.');
        }

        // Do not replace the admin login. This separate value lets all member
        // links/actions stay within the selected member's preview context.
        session(['auth.preview_user_id' => $user->id]);

        $userId = $user->id;
        $today = now()->toDateString();

        $earnings = [
            'referral' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'referral')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'referral')->sum('amount'),
            ],
            'level' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'level')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'level')->sum('amount'),
            ],
            'trade_profit' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'trade_profit')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'trade_profit')->sum('amount'),
            ],
            'leadership' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'leadership')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'leadership')->sum('amount'),
            ],
        ];

        $totalEarned = Income::where('user_id', $userId)->sum('amount');
        $directReferralsCount = User::where('sponsor_id', $userId)->count();
        $activeInvestment = $user->investments()->where('status', 'active')->latest()->first();
        $activePackage = $activeInvestment ? '' . number_format($activeInvestment->amount, 2) : 'No Package';
        $currentRank = optional($user->rank)->name ?? 'Unranked';

        $chartLabels = [];
        $chartDays = [];
        $chartDaysTotal = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $chartLabels[] = now()->subDays($i)->format('D');
            $chartDays[] = (float) Income::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->sum('amount');
            $chartDaysTotal[] = (float) Income::where('user_id', $userId)
                ->whereDate('created_at', '<=', $date)
                ->sum('amount');
        }

        return view('user.dashboard', [
            'user' => $user,
            'headerUser' => $user,
            'previewMode' => true,
            'earningWallet' => number_format($user->earning_wallet ?? 0, 2),
            'depositWallet' => number_format($user->deposit_wallet ?? 0, 2),
            'totalEarned' => number_format($totalEarned, 2),
            'activePackage' => $activePackage,
            'currentRank' => $currentRank,
            'directReferrals' => $directReferralsCount,
            'earnings' => $earnings,
            'chartLabels' => $chartLabels,
            'chartDays' => $chartDays,
            'chartDaysTotal' => $chartDaysTotal,
        ]);
    }

    public function exitPreview()
    {
        session()->forget('auth.preview_user_id');

        return redirect()->route('admin.users.index');
    }

    public function tree(User $user = null)
    {
        // Agar URL mein user ID nahi di gayi hai, toh default pehle user ko root bana dein
        if (!$user || !$user->exists) {
            $rootUser = User::first();
        } else {
            $rootUser = $user;
        }

        // Agar AJAX request hai (sub-tree expansion ke liye)
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'user' => $rootUser,
                'tree' => $this->formatTreeNode($rootUser)
            ]);
        }

        $treeData = $this->formatTreeNode($rootUser);

        return view('admin.users.tree', compact('rootUser', 'treeData'));
    }

    private function formatTreeNode($user)
    {
        if (!$user)
            return null;

        $activeInv = $user->investments()->where('status', 'active')->sum('amount');
        $sponsor = User::where('id', $user->sponsor_id)->first();

        $children = [];
        foreach ($user->referrals as $child) {
            $children[] = $this->formatTreeNode($child);
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile ?? 'N/A',
            'unique_id' => $user->referral_code ?? $user->unique_id,
            'sponsor_id' => $sponsor ? ($sponsor->referral_code ?? $sponsor->unique_id) : 'System',
            'sponsor_name' => $sponsor ? $sponsor->name : 'System',
            'status' => $user->status,
            'active_package' => $activeInv > 0 ? '' . number_format($activeInv, 2) : 'No Package',
            'active_investment' => $activeInv,
            'children' => $children
        ];
    }

    // private function generateReferralCode(): string
    // {
    //     do {
    //         $code = 'BG' . strtoupper(Str::random(7));
    //     } while (User::where('referral_code', $code)->exists());

    //     return $code;
    // }

    private function generateReferralCode(): string
    {
        do {
            $randomNumbers = mt_rand(1000000, 9999999);

            $code = 'BG' . $randomNumbers;
        } while (
            User::query()
                ->where('referral_code', $code)
                ->exists()
        );

        return $code;
    }


    public function teamReport(Request $request, User $user)
    {
        $query = User::where('sponsor_id', $user->id)->with('investments', 'rank');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $teamMembers = $query->latest()->paginate(10)->withQueryString();

        $totalDirects = User::where('sponsor_id', $user->id)->count();
        $activeDirects = User::where('sponsor_id', $user->id)->where('status', 'active')->count();

        return view('admin.users.team', compact('user', 'teamMembers', 'totalDirects', 'activeDirects'));
    }

    public function proxyInvestment(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        $amount = $request->amount;

        if ($amount % 10000 !== 0) {
            return back()->with('error', 'Package investment must be in multiples of 10,000.');
        }

        if ($user->deposit_wallet < $amount) {
            return back()->with('error', 'Insufficient balance in user deposit wallet.');
        }

        $user->deposit_wallet -= $amount;
        $user->status = 'active';
        if (!$user->activated_at) {
            $user->activated_at = now();
        }
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'wallet_type' => 'deposit_wallet',
            'type' => 'debit',
            'amount' => $amount,
            'remark' => 'Package investment purchase (admin preview)',
        ]);

        Investment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'activated_at' => now(),
            'status' => 'active',
        ]);

        $mlmService = new MLMService();
        $mlmService->evaluateUserRank($user->sponsor);
        $mlmService->distributeReferralIncome($user, $amount);
        $mlmService->distributeLevelIncome($user, $amount);
        $mlmService->distributeTradeProfitIncome($user, $amount);

        return redirect()->route('admin.users.preview', $user)->with('success', 'Investment processed for user.');
    }

    public function proxyWithdrawal(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'bank_details' => 'required|string|max:255',
        ]);

        if ($user->earning_wallet < $request->amount) {
            return back()->with('error', 'Insufficient balance in user earning wallet.');
        }

        DB::transaction(function () use ($user, $request) {
            $fee = ($request->amount * 10) / 100;
            $payable = $request->amount - $fee;

            $user->earning_wallet -= $request->amount;
            $user->save();

            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'fee' => $fee,
                'payable_amount' => $payable,
                'status' => 'pending',
                'bank_details' => $request->bank_details,
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => 'earning_wallet',
                'type' => 'debit',
                'amount' => $request->amount,
                'remark' => 'Withdrawal request submitted (admin preview)',
            ]);
        });

        return redirect()->route('admin.users.preview', $user)->with('success', 'Withdrawal request submitted for user.');
    }

    public function proxyDeposit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'required|string|max:255',
        ]);

        Deposit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'reference_no' => $request->reference_no,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.users.preview', $user)->with('success', 'Deposit request created for user.');
    }

    public function adjustWallet(User $user)
    {
        return view('admin.users.wallet-adjust', compact('user'));
    }

    public function updateWallet(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'remark' => 'required|string|max:255',
        ]);

        $amount = $request->amount;
        $walletType = 'deposit_wallet'; // Fixed default
        $action = 'credit'; // Fixed default

        DB::transaction(function () use ($user, $walletType, $action, $amount, $request) {
            $user->$walletType += $amount;
            $user->save();

            // Log Transaction Ledger
            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => $walletType,
                'type' => $action,
                'amount' => $amount,
                'remark' => 'Admin Deposit: ' . $request->remark,
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', 'Funds added successfully to user deposit wallet.');
    }

    public function directDepositIndex(Request $request)
    {
        $query = User::with('investments')->where('role_id', 2); // Only members

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $totalAccounts = User::where('role_id', 2)->count();
        $totalDepositAmount = User::where('role_id', 2)->sum('deposit_wallet');

        return view('admin.users.direct-deposit', compact('users', 'totalAccounts', 'totalDepositAmount'));
    }

    public function storeDirectDeposit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->deposit_wallet += $request->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => 'deposit_wallet',
                'type' => 'credit',
                'amount' => $request->amount,
                'remark' => $request->remark ?? 'Direct fund deposit by admin',
            ]);
        });

        return back()->with('success', 'Successfully added ' . number_format($request->amount, 2) . ' to ' . $user->name . '\'s deposit wallet.');
    }


    public function view(User $user)
    {
        // Load necessary relationships if any (e.g. investments, sponsor, rank)
        $user->load('sponsor', 'investments', 'rank');

        // Wallets & Stats calculations based on your project structure
        $withdrawWallet = $user->withdraw_wallet ?? 0;
        $depositWallet = $user->deposit_wallet ?? 0;
        $earningWallet = $user->earning_wallet ?? 0;

        $activePackage = $user->investments()->where('status', 'active')->latest()->first();
        $packagesHistory = $user->investments()->latest()->get();

        return view('admin.users.view', compact(
            'user',
            'withdrawWallet',
            'depositWallet',
            'earningWallet',
            'activePackage',
            'packagesHistory'
        ));
    }
}