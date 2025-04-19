@extends('layouts.app')

@section('content')
<div class="card">
    <h2 class="text-2xl font-bold mb-4">Edit Group</h2>

    <form method="POST" action="{{ route('groups.update', $group) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Group Name</label>
            <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $group->name) }}" required autofocus>
            @error('name')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-input" rows="3">{{ old('description', $group->description) }}</textarea>
            @error('description')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="member_ids" class="form-label">Members</label>
            <select id="member_ids" name="member_ids[]" class="form-input" multiple required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, old('member_ids', $group->members->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-600 mt-1">Hold Ctrl (Windows) or Command (Mac) to select multiple members.</p>
            @error('member_ids')
                <div class="text-danger text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="btn">Update Group</button>
            <a href="{{ route('groups.show', $group) }}" class="btn" style="background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>
@endsection 