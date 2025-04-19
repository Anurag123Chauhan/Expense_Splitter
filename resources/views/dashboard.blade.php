@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
        <p class="text-muted">Welcome back, {{ Auth::user()->name }}! Here's an overview of your expense groups and recent activity.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="dashboard-stats mb-4">
    <div class="stat-card">
        <i class="fas fa-users"></i>
        <h3>{{ $groups->count() }}</h3>
        <p>Total Groups</p>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #198754, #146c43);">
        <i class="fas fa-receipt"></i>
        <h3>{{ $recentExpenses->count() }}</h3>
        <p>Recent Expenses</p>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
        <i class="fas fa-money-bill-wave"></i>
        <h3>${{ number_format($recentExpenses->sum('amount'), 2) }}</h3>
        <p>Total Expenses</p>
    </div>
</div>

<!-- Groups Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Your Groups</h5>
                <a href="{{ route('groups.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> New Group
                </a>
            </div>
            <div class="card-body">
                @if($groups->count() > 0)
                    <div class="row">
                        @foreach($groups as $group)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="group-card">
                                    <h5>{{ $group->name }}</h5>
                                    <p class="text-muted mb-3">{{ Str::limit($group->description, 100) }}</p>
                                    
                                    <div class="group-members mb-3">
                                        @foreach($group->members->take(5) as $member)
                                            <div class="member-avatar" title="{{ $member->name }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endforeach
                                        @if($group->members->count() > 5)
                                            <div class="member-avatar">
                                                +{{ $group->members->count() - 5 }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="group-actions">
                                        <span class="text-muted">{{ $group->members->count() }} members</span>
                                        <a href="{{ route('groups.show', $group) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>No groups yet</h5>
                        <p class="text-muted mb-3">Get started by creating a new group to track expenses with friends.</p>
                        <a href="{{ route('groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Your First Group
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Expenses Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Recent Expenses</h5>
            </div>
            <div class="card-body p-0">
                @if($recentExpenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Group</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Paid By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentExpenses as $expense)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ optional($expense->group)->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $expense->title }}</td>
                                        <td class="fw-bold">${{ number_format($expense->amount, 2) }}</td>
                                        <td>{{ optional($expense->payer)->name ?? 'N/A' }}</td>
                                        <td>{{ $expense->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($expense->group)
                                                <a href="{{ route('groups.expenses.show', [$expense->group, $expense]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5>No recent expenses</h5>
                        <p class="text-muted">When you add expenses to your groups, they'll appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 