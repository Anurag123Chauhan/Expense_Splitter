@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header">
        <h2>Summary</h2>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="section">
                    <h3>Overall Balance</h3>
                    <div class="balance-box">
                        <div class="balance-amount {{ $totalBalance > 0 ? 'positive' : ($totalBalance < 0 ? 'negative' : 'neutral') }}">
                            ${{ number_format(abs($totalBalance), 2) }}
                            @if($totalBalance > 0)
                                to receive
                            @elseif($totalBalance < 0)
                                to pay
                            @else
                                settled
                            @endif
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>Group Balances</h3>
                    <div class="group-balances">
                        @foreach($groupBalances as $groupBalance)
                            <div class="group-balance-card">
                                <h4>{{ $groupBalance->group->name }}</h4>
                                <div class="balance-amount {{ $groupBalance->balance > 0 ? 'positive' : ($groupBalance->balance < 0 ? 'negative' : 'neutral') }}">
                                    ${{ number_format(abs($groupBalance->balance), 2) }}
                                </div>
                                <p class="balance-status">
                                    @if($groupBalance->balance > 0)
                                        You are owed
                                    @elseif($groupBalance->balance < 0)
                                        You owe
                                    @else
                                        All settled
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="section">
                    <h3>Suggested Settlements</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Group</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settlements as $settlement)
                                    <tr>
                                        <td>{{ $settlement->group->name }}</td>
                                        <td>{{ $settlement->from->name }}</td>
                                        <td>{{ $settlement->to->name }}</td>
                                        <td>${{ number_format($settlement->amount, 2) }}</td>
                                        <td>
                                            @if($settlement->from_id === Auth::id())
                                                <form action="{{ route('settlements.mark-as-paid', $settlement) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-link">Mark as Paid</button>
                                                </form>
                                            @else
                                                <span class="status-badge {{ $settlement->is_paid ? 'paid' : 'pending' }}">
                                                    {{ $settlement->is_paid ? 'Paid' : 'Pending' }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No settlements needed</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header {
    margin-bottom: 20px;
}

.header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

.card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-body {
    padding: 20px;
}

.section {
    margin-bottom: 30px;
}

.section h3 {
    font-size: 18px;
    font-weight: 500;
    color: #333;
    margin-bottom: 15px;
}

.balance-box {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
}

.balance-amount {
    font-size: 24px;
    font-weight: bold;
}

.balance-amount.positive {
    color: #28a745;
}

.balance-amount.negative {
    color: #dc3545;
}

.balance-amount.neutral {
    color: #6c757d;
}

.group-balances {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.group-balance-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 15px;
}

.group-balance-card h4 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 10px;
}

.balance-status {
    font-size: 14px;
    color: #6c757d;
    margin-top: 5px;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.table th {
    background: #f8f9fa;
    font-weight: 500;
    color: #495057;
}

.btn-link {
    color: #007bff;
    text-decoration: none;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.btn-link:hover {
    text-decoration: underline;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.paid {
    background: #d4edda;
    color: #155724;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}
</style>
@endsection 