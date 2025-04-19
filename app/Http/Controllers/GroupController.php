<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $groups = Group::where('created_by', Auth::id())
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['creator', 'members'])
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:users,id'
        ]);

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'created_by' => Auth::id()
        ]);

        $group->members()->attach($validated['member_ids']);
        $group->members()->attach(Auth::id()); // Add creator as a member

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    public function show(Group $group)
    {
        if (!Auth::user()->can('view', $group)) {
            abort(403);
        }
        
        $group->load(['members', 'expenses.payer', 'expenses.shares.user']);
        
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        if (!Auth::user()->can('update', $group)) {
            abort(403);
        }
        
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('groups.edit', compact('group', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        if (!Auth::user()->can('update', $group)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:users,id'
        ]);

        $group->update([
            'name' => $validated['name'],
            'description' => $validated['description']
        ]);

        $group->members()->sync($validated['member_ids']);
        $group->members()->attach(Auth::id()); // Ensure creator remains a member

        return redirect()->route('groups.show', $group)
            ->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        if (!Auth::user()->can('delete', $group)) {
            abort(403);
        }
        
        $group->delete();
        
        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    public function addMember(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($group->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this group.');
        }

        $group->members()->attach($user->id);

        return back()->with('success', 'Member added successfully.');
    }

    public function removeMember(Group $group, User $user)
    {
        $this->authorize('update', $group);

        if ($user->id === $group->created_by) {
            return back()->with('error', 'Cannot remove the group creator.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }
} 