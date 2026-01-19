@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-block"><i class="fas fa-arrow-left"></i> Back to Users</a>
    <h1 class="text-3xl font-bold text-gray-900">Edit User: {{ $user->name }}</h1>
</div>

<div class="max-w-2xl text-pink-500">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Assign Role</label>
                <select name="role" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg">Update User</button>
        </div>
    </form>
</div>
@endsection
