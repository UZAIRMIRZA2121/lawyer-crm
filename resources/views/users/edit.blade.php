@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit User</h2>
        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @php
                $user = $user ?? null;
            @endphp

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                </div>
                <div class="mb-3 col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $user->email ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    @if (isset($user))
                        <small>Leave blank to keep current password</small>
                    @endif
                </div>
                <div class="mb-3 col-md-3">
                    <label>User Role</label>
                    <select name="role" class="form-control">
                        <option value="">Select Role</option>

                        <option value="sub-admin" {{ old('role', $user->role ?? '') == 'sub-admin' ? 'selected' : '' }}>
                            Sub Admin
                        </option>

                        <option value="clerk" {{ old('role', $user->role ?? '') == 'clerk' ? 'selected' : '' }}>
                            Clerk
                        </option>

                        <option value="team" {{ old('role', $user->position_title ?? '') == 'team' ? 'selected' : '' }}>
                            Team
                        </option>
                    </select>
                </div>
                <div class="mb-3 col-md-2">
                    
                    <label>User Status</label>
                    <select name="status" class="form-control">
                      
                        <option value="1" {{$user->status  == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{  $user->status   == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label>Qualification</label>
                    <textarea name="qualification" class="form-control" rows="4">{{ old('qualification', $user->qualification ?? '') }}</textarea>
                </div>

                <div class="mb-3 col-md-3">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control"
                        value="{{ old('contact', $user->contact ?? '') }}">
                </div>
                <div class="mb-3 col-md-3">
                    <label>Row</label>
                    <input type="text" name="position" class="form-control"
                        value="{{ old('position', $user->position ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-4">
                    <label>Facebook</label>
                    <input type="url" name="facebook" class="form-control"
                        value="{{ old('facebook', $user->facebook ?? '') }}">
                </div>
                <div class="mb-3 col-md-4">
                    <label>Twitter</label>
                    <input type="url" name="twitter" class="form-control"
                        value="{{ old('twitter', $user->twitter ?? '') }}">
                </div>
                <div class="mb-3 col-md-4">
                    <label>LinkedIn</label>
                    <input type="url" name="linkedin" class="form-control"
                        value="{{ old('linkedin', $user->linkedin ?? '') }}">
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label>Profile Image</label>
                    <input type="file" name="profile_img" class="form-control">

                </div>
                <div class="mb-3 col-md-6">

                    @if (isset($user) && $user->profile_img)
                        <img src="{{ asset('storage/' . $user->profile_img) }}" width="200" class="mt-2 d-block">
                    @endif
                </div>
            </div>

            <div class="text-end">
                <button class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
@endsection
