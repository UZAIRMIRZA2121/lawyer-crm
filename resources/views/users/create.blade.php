@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
        @php
            $user = $user ?? null;
        @endphp

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
            </div>
            <div class="mb-3 col-md-6">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username', $user->username ?? '') }}">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
            </div>
            <div class="mb-3 col-md-6">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                @if (isset($user)) <small>Leave blank to keep current password</small> @endif
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Role</label>
                <input type="text" name="role" class="form-control" value="{{ old('role', $user->role ?? '') }}" readonly>
            </div>
         <div class="mb-3 col-md-6">
    <label>Qualification</label>
    <textarea name="qualification" class="form-control" rows="4">{{ old('qualification', $user->qualification ?? '') }}</textarea>
</div>

        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $user->contact ?? '') }}">
            </div>
            <div class="mb-3 col-md-6">
                <label>Profile Image</label>
                <input type="file" name="profile_img" class="form-control">
                @if (isset($user) && $user->profile_img)
                    <img src="{{ asset('storage/' . $user->profile_img) }}" width="70" class="mt-2 d-block">
                @endif
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Facebook</label>
                <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $user->facebook ?? '') }}">
            </div>
            <div class="mb-3 col-md-4">
                <label>Twitter</label>
                <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $user->twitter ?? '') }}">
            </div>
            <div class="mb-3 col-md-4">
                <label>LinkedIn</label>
                <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $user->linkedin ?? '') }}">
            </div>
        </div>

        <div class="text-end">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
