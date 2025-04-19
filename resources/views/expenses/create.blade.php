@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Add New Expense to {{ $group->name }}</h2>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('groups.expenses.store', $group) }}" method="POST" id="expense-form">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Total Amount</label>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0.01" 
                                   value="{{ old('amount') }}" 
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date">Date</label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="paid_by">Paid By</label>
                            <select class="form-control @error('paid_by') is-invalid @enderror" 
                                    id="paid_by" 
                                    name="paid_by" 
                                    required>
                                <option value="">Select who paid</option>
                                @foreach($group->members as $member)
                                    <option value="{{ $member->id }}" 
                                            {{ old('paid_by') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Split Between</label>
                            <div class="btn-group mb-3">
                                <button type="button" class="btn btn-outline-primary" id="split-equally-btn">
                                    <i class="fas fa-equals me-1"></i>Split Equally
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="split-proportionally-btn">
                                    <i class="fas fa-percentage me-1"></i>Split Proportionally
                                </button>
                            </div>
                            <div id="shares-container">
                                @foreach($group->members as $member)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ $member->name }}</span>
                                        <input type="hidden" 
                                               name="shares[{{ $loop->index }}][user_id]" 
                                               value="{{ $member->id }}">
                                        <input type="number" 
                                               class="form-control share-amount" 
                                               name="shares[{{ $loop->index }}][amount]" 
                                               step="0.01" 
                                               min="0.01" 
                                               placeholder="Amount" 
                                               required
                                               readonly>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <span class="text-muted">Total Shares: </span>
                                <span id="total-shares">0.00</span>
                                <span class="text-muted"> / </span>
                                <span id="total-amount-display">0.00</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Add Expense</button>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const totalAmount = document.getElementById('amount');
    const paidBySelect = document.getElementById('paid_by');
    const shareInputs = document.querySelectorAll('.share-amount');
    const splitEquallyBtn = document.getElementById('split-equally-btn');
    const splitProportionallyBtn = document.getElementById('split-proportionally-btn');
    const totalSharesDisplay = document.getElementById('total-shares');
    const totalAmountDisplay = document.getElementById('total-amount-display');
    const expenseForm = document.getElementById('expense-form');
    
    // Function to enable share inputs
    function enableShareInputs() {
        shareInputs.forEach(input => {
            input.readOnly = false;
        });
    }
    
    // Function to calculate total shares
    function calculateTotalShares() {
        let total = 0;
        shareInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        return total;
    }
    
    // Function to update total shares display
    function updateTotalSharesDisplay() {
        const total = calculateTotalShares();
        totalSharesDisplay.textContent = total.toFixed(2);
        
        // Update total amount display
        const amount = parseFloat(totalAmount.value) || 0;
        totalAmountDisplay.textContent = amount.toFixed(2);
        
        // Check if total shares exceed total amount
        if (total > amount && amount > 0) {
            totalSharesDisplay.classList.add('text-danger');
        } else {
            totalSharesDisplay.classList.remove('text-danger');
        }
    }
    
    // Function to split amount equally
    function splitEqually() {
        const amount = parseFloat(totalAmount.value) || 0;
        
        if (amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }
        
        // Enable all share inputs
        enableShareInputs();
        
        // Count all members
        const memberCount = shareInputs.length;
        
        if (memberCount > 0) {
            const equalShare = amount / memberCount;
            
            shareInputs.forEach(function(input) {
                input.value = equalShare.toFixed(2);
            });
            
            updateTotalSharesDisplay();
        }
    }
    
    // Function to split amount proportionally
    function splitProportionally() {
        const amount = parseFloat(totalAmount.value) || 0;
        
        if (amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }
        
        // Enable all share inputs
        enableShareInputs();
        
        // Set initial values for proportional split
        shareInputs.forEach(function(input) {
            // Set a default value of 1 for each member
            input.value = '1.00';
        });
        
        // Calculate total of all shares
        let totalShares = 0;
        shareInputs.forEach(function(input) {
            totalShares += parseFloat(input.value) || 0;
        });
        
        // Split proportionally
        if (totalShares > 0) {
            shareInputs.forEach(function(input) {
                const share = parseFloat(input.value) || 0;
                const proportion = share / totalShares;
                input.value = (amount * proportion).toFixed(2);
            });
            
            updateTotalSharesDisplay();
        }
    }
    
    // Function to validate shares before form submission
    function validateShares() {
        const amount = parseFloat(totalAmount.value) || 0;
        const total = calculateTotalShares();
        
        if (total > amount) {
            alert('The sum of shares (' + total.toFixed(2) + ') cannot exceed the total amount (' + amount.toFixed(2) + ').');
            return false;
        }
        
        return true;
    }
    
    // Add event listeners
    if (splitEquallyBtn) {
        splitEquallyBtn.addEventListener('click', splitEqually);
    }
    
    if (splitProportionallyBtn) {
        splitProportionallyBtn.addEventListener('click', splitProportionally);
    }
    
    // Update shares when total amount changes
    if (totalAmount) {
        totalAmount.addEventListener('input', function() {
            updateTotalSharesDisplay();
            
            // If a split method has been used, update the shares
            if (shareInputs.length > 0 && shareInputs[0].value && !shareInputs[0].readOnly) {
                splitEqually();
            }
        });
    }
    
    // Add event listeners to share inputs
    shareInputs.forEach(input => {
        input.addEventListener('input', function() {
            updateTotalSharesDisplay();
            
            const amount = parseFloat(totalAmount.value) || 0;
            const total = calculateTotalShares();
            
            if (total > amount && amount > 0) {
                alert('Warning: The sum of shares (' + total.toFixed(2) + ') exceeds the total amount (' + amount.toFixed(2) + ').');
            }
        });
    });
    
    // Add form submission validation
    if (expenseForm) {
        expenseForm.addEventListener('submit', function(e) {
            if (!validateShares()) {
                e.preventDefault();
            }
        });
    }
    
    // Initialize if a payer is already selected and shares are set
    if (paidBySelect && paidBySelect.value && shareInputs.length > 0 && shareInputs[0].value) {
        enableShareInputs();
        updateTotalSharesDisplay();
    }
});
</script>
@endpush
@endsection 