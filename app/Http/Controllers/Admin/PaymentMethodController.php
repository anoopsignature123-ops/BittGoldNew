<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        try {
            $methods = PaymentMethod::orderBy('sort_order')->orderBy('id')->get();
            $activeMethods = $methods->where('is_active', true)->count();
            return view('admin.payment-methods.index', compact('methods', 'activeMethods'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading payment methods: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validatedData($request, true);
            $data = $this->methodData($request, $validated);

            if ($request->hasFile('qr_image')) {
                $data['qr_image'] = $request->file('qr_image')->store('payment-methods', 'public');
            }

            PaymentMethod::create($data);
            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method added successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error saving payment method: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            $validated = $this->validatedData($request, $paymentMethod->type !== 'qr' || ! $paymentMethod->qr_image);
            $data = $this->methodData($request, $validated);

            if ($request->hasFile('qr_image')) {
                if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                    Storage::disk('public')->delete($paymentMethod->qr_image);
                }
                $data['qr_image'] = $request->file('qr_image')->store('payment-methods', 'public');
            } elseif ($validated['type'] !== 'qr' && $paymentMethod->qr_image) {
                if (Storage::disk('public')->exists($paymentMethod->qr_image)) {
                    Storage::disk('public')->delete($paymentMethod->qr_image);
                }
                $data['qr_image'] = null;
            }

            $paymentMethod->update($data);
            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating payment method: ' . $e->getMessage());
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $paymentMethod->delete();
            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting payment method: ' . $e->getMessage());
        }
    }

    public function toggle(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => ! $paymentMethod->is_active]);

        return redirect()->route('admin.payment-methods.index')->with(
            'success', "{$paymentMethod->title} is now " . ($paymentMethod->is_active ? 'active.' : 'inactive.')
        );
    }

    private function validatedData(Request $request, bool $requireQrImage): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(['qr', 'upi', 'bank'])],
            'title' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'qr_image' => [Rule::requiredIf(fn () => $requireQrImage && $request->input('type') === 'qr'), 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'upi_id' => [Rule::requiredIf(fn () => $request->input('type') === 'upi'), 'nullable', 'string', 'max:255'],
            'bank_name' => [Rule::requiredIf(fn () => $request->input('type') === 'bank'), 'nullable', 'string', 'max:255'],
            'account_holder_name' => [Rule::requiredIf(fn () => $request->input('type') === 'bank'), 'nullable', 'string', 'max:255'],
            'account_number' => [Rule::requiredIf(fn () => $request->input('type') === 'bank'), 'nullable', 'string', 'max:255'],
            'ifsc_code' => [Rule::requiredIf(fn () => $request->input('type') === 'bank'), 'nullable', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function methodData(Request $request, array $validated): array
    {
        $type = $validated['type'];

        return [
            'type' => $type,
            'title' => $validated['title'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
            'upi_id' => $type === 'upi' ? $validated['upi_id'] : null,
            'bank_name' => $type === 'bank' ? $validated['bank_name'] : null,
            'account_holder_name' => $type === 'bank' ? $validated['account_holder_name'] : null,
            'account_number' => $type === 'bank' ? $validated['account_number'] : null,
            'ifsc_code' => $type === 'bank' ? $validated['ifsc_code'] : null,
            'branch_name' => $type === 'bank' ? ($validated['branch_name'] ?? null) : null,
            'notes' => $validated['notes'] ?? null,
        ];
    }

    public function diagnostics()
    {
        return view('admin.payment-methods.diagnostics');
    }
}
