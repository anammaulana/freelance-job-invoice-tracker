<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Project;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService) {}

    public function index(): View
    {
        $invoices = Invoice::with('project.client')->latest()->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        return view('invoices.create', [
            'projects' => Project::with('client')->orderBy('name')->get(),
            'statuses' => Invoice::STATUSES,
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = $this->invoiceService->create($request->validated());

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['project.client', 'payments' => fn ($query) => $query->latest('payment_date')]);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        return view('invoices.edit', [
            'invoice' => $invoice,
            'projects' => Project::with('client')->orderBy('name')->get(),
            'statuses' => Invoice::STATUSES,
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice = $this->invoiceService->update($invoice, $request->validated());

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->delete($invoice);

        return redirect()->route('invoices.index')->with('status', 'Invoice berhasil dihapus.');
    }
}
