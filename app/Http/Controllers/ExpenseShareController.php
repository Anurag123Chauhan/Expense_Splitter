<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Models\ExpenseShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseShareController extends Controller
{
    public function markAsPaid(Group $group, Expense $expense, ExpenseShare $share)
    {
        $this->authorize('view', $group);
        
        if ($share->expense_id !== $expense->id) {
            return back()->with('error', 'Invalid expense share.');
        }
        
        $share->update(['is_paid' => true]);
        
        return back()->with('success', 'Payment marked as completed.');
    }

    public function markAsUnpaid(Group $group, Expense $expense, ExpenseShare $share)
    {
        $this->authorize('view', $group);
        
        if ($share->expense_id !== $expense->id) {
            return back()->with('error', 'Invalid expense share.');
        }
        
        $share->update(['is_paid' => false]);
        
        return back()->with('success', 'Payment marked as pending.');
    }
} 