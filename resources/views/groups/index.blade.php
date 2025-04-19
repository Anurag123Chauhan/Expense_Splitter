@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><i class="fas fa-users me-2"></i>Groups</h2>
            <a href="{{ route('groups.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Group
            </a>
        </div>
    </div>
</div>

@if($groups->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h3 class="mb-2">No groups yet</h3>
            <p class="text-muted mb-4">Get started by creating a new group to track expenses with friends.</p>
            <a href="{{ route('groups.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Your First Group
            </a>
        </div>
    </div>
@else
    <div class="row">
        @foreach($groups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="group-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="mb-0">{{ $group->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('groups.show', $group) }}">
                                        <i class="fas fa-eye me-2"></i> View Details
                                    </a>
                                </li>
                                @if($group->created_by === Auth::id())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('groups.edit', $group) }}">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('groups.destroy', $group) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this group?')">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
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
@endif
@endsection 