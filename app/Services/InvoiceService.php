<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data): Invoice {
            $project = Project::query()->lockForUpdate()->findOrFail($data['project_id']);
            $this->ensureProjectInvoiceLimit($project, (float) $data['amount']);

            $data['invoice_number'] = $this->generateInvoiceNumber((string) $data['issue_date']);

            return Invoice::create($data);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data): Invoice {
            $project = Project::query()->lockForUpdate()->findOrFail($data['project_id']);
            $this->ensureProjectInvoiceLimit($project, (float) $data['amount'], $invoice);
            $this->ensureInvoicePaymentLimit($invoice, (float) $data['amount']);

            $invoice->update($data);

            return $invoice->refresh();
        });
    }

    public function delete(Invoice $invoice): void
    {
        $invoice->delete();
    }

    private function generateInvoiceNumber(string $issueDate): string
    {
        $period = date('Ym', strtotime($issueDate));
        $latest = Invoice::query()
            ->where('invoice_number', 'like', "INV-{$period}-%")
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $nextSequence = $latest ? ((int) substr($latest, -4)) + 1 : 1;

        return sprintf('INV-%s-%04d', $period, $nextSequence);
    }

    private function ensureProjectInvoiceLimit(Project $project, float $amount, ?Invoice $ignoredInvoice = null): void
    {
        $existingTotal = (float) $project->invoices()
            ->when($ignoredInvoice, fn ($query) => $query->whereKeyNot($ignoredInvoice->id))
            ->sum('amount');

        if (($existingTotal + $amount) > (float) $project->project_value) {
            throw ValidationException::withMessages([
                'amount' => 'Total invoice amount for this project cannot exceed the project value.',
            ]);
        }
    }

    private function ensureInvoicePaymentLimit(Invoice $invoice, float $newAmount): void
    {
        if ((float) $invoice->payments()->sum('amount') > $newAmount) {
            throw ValidationException::withMessages([
                'amount' => 'Invoice amount cannot be lower than total recorded payments.',
            ]);
        }
    }
}
