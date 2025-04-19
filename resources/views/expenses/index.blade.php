@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Expenses for {{ $group->name }}</h2>
                    <a href="{{ route('groups.expenses.create', $group) }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Add New Expense
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($expenses->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="lead">No expenses found for this group.</p>
                            <a href="{{ route('groups.expenses.create', $group) }}" class="btn btn-primary mt-2">Add Your First Expense</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Paid By</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->title }}</td>
                                            <td>${{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->payer->name }}</td>
                                            <td>{{ $expense->date->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" 
                                                       class="btn btn-sm btn-info me-1">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('groups.expenses.edit', [$group, $expense]) }}" 
                                                       class="btn btn-sm btn-primary me-1">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('groups.expenses.destroy', [$group, $expense]) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this expense?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
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

<style>
    .btn-group {
        display: flex;
        gap: 5px;
    }
    
    .table th {
        font-weight: 600;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .table-responsive {
        border-radius: 0 0 8px 8px;
    }
</style>
@endsection 