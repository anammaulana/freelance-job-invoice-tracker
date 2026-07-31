<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Project;
use App\Services\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(private readonly ExpenseService $expenseService) {}

    public function index(): View
    {
        $expenses = Expense::with('project.client')
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('expenses.create', [
            'projects' => Project::with('client')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $expense = $this->expenseService->create($request->validated());

        return redirect()->route('expenses.show', $expense)->with('status', 'Expense berhasil dibuat.');
    }

    public function show(Expense $expense): View
    {
        $expense->load([
            'documents' => fn ($query) => $query->with('uploadedBy')->latest(),
            'project.client',
        ]);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        return view('expenses.edit', [
            'expense' => $expense,
            'projects' => Project::with('client')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense = $this->expenseService->update($expense, $request->validated());

        return redirect()->route('expenses.show', $expense)->with('status', 'Expense berhasil diperbarui.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $this->expenseService->delete($expense);

        return redirect()->route('expenses.index')->with('status', 'Expense berhasil dihapus.');
    }
}
