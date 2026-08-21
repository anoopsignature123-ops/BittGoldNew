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
        $methods = PaymentMethod::all();
        $activeMethods = $methods->count();
        return view('admin.payment-methods.index', compact('methods', 'activeMethods'));
    }

    public function store(Request $request)
    {
        try {
            $type = $request->input('type');

            // Agar type pehle se exist karta hai toh update karein
            $existing = PaymentMethod::where('type', $type)->first();
            if ($existing) {
                return $this->updateCore($request, $existing);
            }

            $validated = $request->validate([
                'type' => ['required', Rule::in(['qr', 'upi', 'bank'])],
                'title' => ['required', 'string', 'max:100'],
                'qr_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'upi_id' => ['nullable', 'string', 'max:255'],
                'bank_name' => ['nullable', 'string', 'max:255'],
                'account_holder_name' => ['nullable', 'string', 'max:255'],
                'account_number' => ['nullable', 'string', 'max:255'],
                'ifsc_code' => ['nullable', 'string', 'max:255'],
                'branch_name' => ['nullable', 'string', 'max:255'],
            ]);

            $data = [
                'type' => $type,
                'title' => $validated['title'],
                'is_active' => true, // By default active
                'sort_order' => 0,
                'upi_id' => $type === 'upi' ? ($validated['upi_id'] ?? null) : null,
                'bank_name' => $type === 'bank' ? ($validated['bank_name'] ?? null) : null,
                'account_holder_name' => $type === 'bank' ? ($validated['account_holder_name'] ?? null) : null,
                'account_number' => $type === 'bank' ? ($validated['account_number'] ?? null) : null,
                'ifsc_code' => $type === 'bank' ? ($validated['ifsc_code'] ?? null) : null,
                'branch_name' => $type === 'bank' ? ($validated['branch_name'] ?? null) : null,
            ];

            if ($request->hasFile('qr_image')) {
                $data['qr_image'] = $request->file('qr_image')->store('payment-methods', 'public');
            }

            PaymentMethod::create($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Payment method saved successfully!']);
            }

            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method saved successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        return $this->updateCore($request, $paymentMethod);
    }

    private function updateCore(Request $request, PaymentMethod $paymentMethod)
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:100'],
                'qr_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'upi_id' => ['nullable', 'string', 'max:255'],
                'bank_name' => ['nullable', 'string', 'max:255'],
                'account_holder_name' => ['nullable', 'string', 'max:255'],
                'account_number' => ['nullable', 'string', 'max:255'],
                'ifsc_code' => ['nullable', 'string', 'max:255'],
                'branch_name' => ['nullable', 'string', 'max:255'],
            ]);

            $data = [
                'title' => $validated['title'],
                'is_active' => true, // By default active
                'upi_id' => $paymentMethod->type === 'upi' ? ($validated['upi_id'] ?? null) : $paymentMethod->upi_id,
                'bank_name' => $paymentMethod->type === 'bank' ? ($validated['bank_name'] ?? null) : $paymentMethod->bank_name,
                'account_holder_name' => $paymentMethod->type === 'bank' ? ($validated['account_holder_name'] ?? null) : $paymentMethod->account_holder_name,
                'account_number' => $paymentMethod->type === 'bank' ? ($validated['account_number'] ?? null) : $paymentMethod->account_number,
                'ifsc_code' => $paymentMethod->type === 'bank' ? ($validated['ifsc_code'] ?? null) : $paymentMethod->ifsc_code,
                'branch_name' => $paymentMethod->type === 'bank' ? ($validated['branch_name'] ?? null) : $paymentMethod->branch_name,
            ];

            if ($request->hasFile('qr_image')) {
                if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                    Storage::disk('public')->delete($paymentMethod->qr_image);
                }
                $data['qr_image'] = $request->file('qr_image')->store('payment-methods', 'public');
            }

            $paymentMethod->update($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Payment method updated successfully!']);
            }

            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $paymentMethod->delete();
            return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method removed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting method: ' . $e->getMessage());
        }
    }
}