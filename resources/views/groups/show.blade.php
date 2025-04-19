@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $group->name }}</h4>
                    <div>
                        <a href="{{ route('groups.expenses.create', $group) }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>Add Expense
                        </a>
                        @if($group->created_by === Auth::id())
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-light ms-2">
                                <i class="fas fa-edit me-2"></i>Edit Group
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Group Details</h5>
                            <p class="text-muted">{{ $group->description }}</p>
                            <p class="mb-0"><strong>Created by:</strong> {{ $group->creator->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Members</h5>
                    @if($group->created_by === Auth::id())
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="fas fa-user-plus me-2"></i>Add Member
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Group Creator Card -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            {{ strtoupper(substr($group->creator->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $group->creator->name }}</h6>
                                            <small class="text-muted">{{ $group->creator->email }}</small>
                                        </div>
                                    </div>
                                    <div class="badge bg-primary mt-2">
                                        <i class="fas fa-crown me-1"></i>Group Creator
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other Members -->
                        @foreach($group->members as $member)
                            @if($member->id !== $group->created_by)
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-circle bg-secondary text-white me-3">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $member->name }}</h6>
                                                <small class="text-muted">{{ $member->email }}</small>
                                            </div>
                                        </div>
                                        @if($group->created_by === Auth::id() && $member->id !== Auth::id())
                                            <form action="{{ route('groups.members.remove', [$group, $member]) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to remove this member?')">
                                                    <i class="fas fa-user-minus me-2"></i>Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Expenses</h5>
                </div>
                <div class="card-body">
                    @if($group->expenses->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No expenses yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Paid By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->title }}</td>
                                            <td>${{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->payer->name }}</td>
                                            <td>{{ $expense->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
@if($group->created_by === Auth::id())
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('groups.members.add', $group) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Member's Email</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                            placeholder="Enter member's email address">
                        <div class="form-text">The user must have an account to be added to the group.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endsection 