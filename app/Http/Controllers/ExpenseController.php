<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Group;
use App\Models\ExpenseShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('view', $group);
        
        $expenses = $group->expenses()
            ->with(['payer', 'shares.user'])
            ->latest()
            ->get();
            
        return view('expenses.index', compact('group', 'expenses'));
    }

    public function create(Group $group)
    {
        $this->authorize('view', $group);
        
        return view('expenses.create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'paid_by' => 'required|exists:users,id',
            'shares' => 'required|array',
            'shares.*.user_id' => 'required|exists:users,id',
            'shares.*.amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $expense = $group->expenses()->create([
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'payer_id' => $validated['paid_by']
            ]);

            // Add shares for all members
            foreach ($validated['shares'] as $share) {
                $expense->shares()->create([
                    'user_id' => $share['user_id'],
                    'amount' => $share['amount'],
                    'is_paid' => $share['user_id'] == $validated['paid_by'] // Mark payer's share as paid
                ]);
            }

            DB::commit();

            return redirect()->route('groups.show', $group)
                ->with('success', 'Expense added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add expense. Please try again.');
        }
    }

    public function show(Group $group, Expense $expense)
    {
        $this->authorize('view', $group);
        
        $expense->load(['payer', 'shares.user', 'group']);
        
        return view('expenses.show', compact('group', 'expense'));
    }

    public function edit(Group $group, Expense $expense)
    {
        $this->authorize('update', $group);
        
        return view('expenses.edit', compact('group', 'expense'));
    }

    public function update(Request $request, Group $group, Expense $expense)
    {
        $this->authorize('update', $group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'payer_id' => 'required|exists:users,id',
            'shares' => 'required|array',
            'shares.*.user_id' => 'required|exists:users,id',
            'shares.*.amount' => 'required|numeric|min:0.01'
        ]);

        try {
            DB::beginTransaction();

            $expense->update([
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'payer_id' => $validated['payer_id']
            ]);

            $expense->shares()->delete();
            foreach ($validated['shares'] as $share) {
                $expense->shares()->create([
                    'user_id' => $share['user_id'],
                    'amount' => $share['amount'],
                    'is_paid' => $share['user_id'] == $validated['payer_id'] // Automatically mark payer's share as paid
                ]);
            }

            DB::commit();

            return redirect()->route('groups.expenses.show', [$group, $expense])
                ->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update expense. Please try again.');
        }
    }

    public function destroy(Group $group, Expense $expense)
    {
        $this->authorize('update', $group);
        
        $expense->delete();
        
        return redirect()->route('groups.expenses.index', $group)
            ->with('success', 'Expense deleted successfully.');
    }

    public function markShareAsPaid(Group $group, Expense $expense, ExpenseShare $share)
    {
        $this->authorize('view', $group);
        
        if (Auth::id() != $share->user_id && Auth::id() != $group->created_by) {
            abort(403, 'You are not authorized to mark this share as paid.');
        }
        
        $share->update(['is_paid' => true]);
        
        return back()->with('success', 'Share marked as paid.');
    }

    public function markShareAsUnpaid(Group $group, Expense $expense, ExpenseShare $share)
    {
        $this->authorize('view', $group);
        
        if (Auth::id() != $share->user_id && Auth::id() != $group->created_by) {
            abort(403, 'You are not authorized to mark this share as unpaid.');
        }
        
        $share->update(['is_paid' => false]);
        
        return back()->with('success', 'Share marked as unpaid.');
    }
} 