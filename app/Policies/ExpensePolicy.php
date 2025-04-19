<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Group $group): bool
    {
        return $user->id === $group->created_by || $group->members->contains($user->id);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group, Expense $expense): bool
    {
        return $user->id === $group->created_by || $group->members->contains($user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Group $group): bool
    {
        return $user->id === $group->created_by || $group->members->contains($user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group, Expense $expense): bool
    {
        return $user->id === $group->created_by || $user->id === $expense->paid_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group, Expense $expense): bool
    {
        return $user->id === $group->created_by || $user->id === $expense->paid_by;
    }
} 