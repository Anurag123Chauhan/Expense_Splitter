@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>{{ $expense->title }}</h4>
                @if(Auth::id() == $expense->group->created_by)
                <div class="btn-group">
                    <a href="{{ route('groups.expenses.edit', [$expense->group, $expense]) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('groups.expenses.destroy', [$expense->group, $expense]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Expense Details</h5>
                            <div class="mb-2">
                                <strong><i class="fas fa-dollar-sign me-2"></i>Amount:</strong>
                                <span class="text-success">${{ number_format($expense->amount, 2) }}</span>
                            </div>
                            <div class="mb-2">
                                <strong><i class="fas fa-calendar me-2"></i>Date:</strong>
                                <span>{{ $expense->created_at->format('F j, Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <strong><i class="fas fa-user me-2"></i>Paid by:</strong>
                                <span>{{ $expense->payer->name }}</span>
                            </div>
                            <div>
                                <strong><i class="fas fa-users me-2"></i>Group:</strong>
                                <span>{{ $expense->group->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Split Details</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Member</th>
                                            <th>Share</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expense->shares as $share)
                                        <tr>
                                            <td>{{ $share->user->name }}</td>
                                            <td>${{ number_format($share->amount, 2) }}</td>
                                            <td>
                                                @if($share->is_paid)
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(Auth::id() == $share->user_id)
                                                    @if(!$share->is_paid)
                                                    <form action="{{ route('groups.expenses.shares.mark-as-paid', [$expense->group, $expense, $share]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check me-1"></i> Mark as Paid
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('groups.expenses.shares.mark-as-unpaid', [$expense->group, $expense, $share]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-times me-1"></i> Mark as Unpaid
                                                        </button>
                                                    </form>
                                                    @endif
                                                @elseif(Auth::id() == $expense->group->created_by)
                                                    @if(!$share->is_paid)
                                                    <form action="{{ route('groups.expenses.shares.mark-as-paid', [$expense->group, $expense, $share]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check me-1"></i> Mark as Paid
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('groups.expenses.shares.mark-as-unpaid', [$expense->group, $expense, $share]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-times me-1"></i> Mark as Unpaid
                                                        </button>
                                                    </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('groups.show', $expense->group) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Group
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 