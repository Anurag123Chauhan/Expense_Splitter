<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get groups where user is a member or creator
        $groups = Group::where('created_by', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['members', 'creator'])
            ->get();
        
        // Get recent expenses from groups where user is a member
        $recentExpenses = Expense::whereHas('group', function ($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->with(['group', 'payer'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', compact('groups', 'recentExpenses'));
    }
} 