<?php

namespace App\Services;

use App\Models\Expense;

class ExpenseService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        return $expense->refresh();
    }

    public function delete(Expense $expense): void
    {
        $expense->delete();
    }
}
