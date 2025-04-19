@extends('layouts.app')

@section('content')
<div class="card">
    <h2 class="text-2xl font-bold mb-4">Edit Expense - {{ $group->name }}</h2>

    <form method="POST" action="{{ route('groups.expenses.update', [$group, $expense]) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $expense->title) }}" required autofocus>
            @error('title')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0.01" class="form-input" value="{{ old('amount', $expense->amount) }}" required>
            @error('amount')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-input" rows="3">{{ old('description', $expense->description) }}</textarea>
            @error('description')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date" class="form-input" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required>
            @error('date')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="paid_by" class="form-label">Paid By</label>
            <select id="paid_by" name="paid_by" class="form-input" required>
                <option value="">Select a member</option>
                @foreach($group->members as $member)
                    <option value="{{ $member->id }}" {{ (old('paid_by', $expense->paid_by) == $member->id) ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
            @error('paid_by')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Split Between</label>
            <div class="space-y-4">
                @foreach($group->members as $member)
                    <div class="flex items-center gap-4">
                        <input type="checkbox" 
                            id="member_{{ $member->id }}" 
                            name="shares[{{ $member->id }}][user_id]" 
                            value="{{ $member->id }}"
                            class="form-checkbox"
                            {{ $expense->shares->contains('user_id', $member->id) ? 'checked' : '' }}>
                        <label for="member_{{ $member->id }}" class="text-sm font-medium">{{ $member->name }}</label>
                        <input type="number" 
                            name="shares[{{ $member->id }}][amount]" 
                            step="0.01" 
                            min="0.01" 
                            class="form-input w-32"
                            placeholder="Amount"
                            value="{{ old('shares.' . $member->id . '.amount', $expense->shares->where('user_id', $member->id)->first()?->amount) }}">
                    </div>
                @endforeach
            </div>
            @error('shares')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
            @error('shares.*')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="btn">Update Expense</button>
            <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="btn" style="background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('amount').addEventListener('input', function() {
        const totalAmount = parseFloat(this.value) || 0;
        const checkedMembers = document.querySelectorAll('input[type="checkbox"][name^="shares"]:checked').length;
        if (checkedMembers > 0) {
            const equalShare = (totalAmount / checkedMembers).toFixed(2);
            document.querySelectorAll('input[type="checkbox"][name^="shares"]:checked').forEach(checkbox => {
                const memberId = checkbox.value;
                const amountInput = document.querySelector(`input[name="shares[${memberId}][amount]"]`);
                if (amountInput) {
                    amountInput.value = equalShare;
                }
            });
        }
    });

    // Update share amounts when checkboxes change
    document.querySelectorAll('input[type="checkbox"][name^="shares"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
            const checkedMembers = document.querySelectorAll('input[type="checkbox"][name^="shares"]:checked').length;
            if (checkedMembers > 0) {
                const equalShare = (totalAmount / checkedMembers).toFixed(2);
                document.querySelectorAll('input[type="checkbox"][name^="shares"]:checked').forEach(checkbox => {
                    const memberId = checkbox.value;
                    const amountInput = document.querySelector(`input[name="shares[${memberId}][amount]"]`);
                    if (amountInput) {
                        amountInput.value = equalShare;
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection 