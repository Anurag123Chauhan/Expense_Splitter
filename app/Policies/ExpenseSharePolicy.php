<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseSharePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group, Expense $expense, ExpenseShare $share): bool
    {
        // Only the user who owes the share or the group creator can mark it as paid/unpaid
        return $user->id === $share->user_id || $user->id === $group->created_by;
    }
} 