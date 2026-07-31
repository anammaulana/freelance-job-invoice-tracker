<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function create(Invoice $invoice): View
    {
        return view('payments.create', compact('invoice'));
    }

    public function store(StorePaymentRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->paymentService->create($invoice, $request->validated());

        return redirect()->route('invoices.show', $invoice)->with('status', 'Payment berhasil dicatat.');
    }

    public function edit(Invoice $invoice, Payment $payment): View
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        return view('payments.edit', compact('invoice', 'payment'));
    }

    public function update(UpdatePaymentRequest $request, Invoice $invoice, Payment $payment): RedirectResponse
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        $this->paymentService->update($payment, $request->validated());

        return redirect()->route('invoices.show', $invoice)->with('status', 'Payment berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice, Payment $payment): RedirectResponse
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        $this->paymentService->delete($payment);

        return redirect()->route('invoices.show', $invoice)->with('status', 'Payment berhasil dihapus.');
    }
}
