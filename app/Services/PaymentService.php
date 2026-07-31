<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data): Payment {
            $invoice = Invoice::query()->lockForUpdate()->findOrFail($invoice->id);
            $this->ensurePaymentLimit($invoice, (float) $data['amount']);

            $payment = $invoice->payments()->create($data);
            $this->syncInvoiceStatus($invoice);

            return $payment;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Payment $payment, array $data): Payment
    {
        return DB::transaction(function () use ($payment, $data): Payment {
            $payment = Payment::query()->with('invoice')->lockForUpdate()->findOrFail($payment->id);
            $this->ensurePaymentLimit($payment->invoice, (float) $data['amount'], $payment);

            $payment->update($data);
            $this->syncInvoiceStatus($payment->invoice);

            return $payment->refresh();
        });
    }

    public function delete(Payment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            $payment = Payment::query()->with('invoice')->lockForUpdate()->findOrFail($payment->id);
            $invoice = $payment->invoice;

            $payment->delete();
            $this->syncInvoiceStatus($invoice);
        });
    }

    private function ensurePaymentLimit(Invoice $invoice, float $amount, ?Payment $ignoredPayment = null): void
    {
        $existingTotal = (float) $invoice->payments()
            ->when($ignoredPayment, fn ($query) => $query->whereKeyNot($ignoredPayment->id))
            ->sum('amount');

        if (($existingTotal + $amount) > (float) $invoice->amount) {
            throw ValidationException::withMessages([
                'amount' => 'Total payment amount cannot exceed the invoice amount.',
            ]);
        }
    }

    private function syncInvoiceStatus(Invoice $invoice): void
    {
        $totalPayments = (float) $invoice->payments()->sum('amount');

        if ($totalPayments <= 0) {
            if (in_array($invoice->status, [Invoice::STATUS_PARTIAL, Invoice::STATUS_PAID], true)) {
                $invoice->update(['status' => Invoice::STATUS_SENT]);
            }

            return;
        }

        $invoice->update([
            'status' => $totalPayments >= (float) $invoice->amount
                ? Invoice::STATUS_PAID
                : Invoice::STATUS_PARTIAL,
        ]);
    }
}
