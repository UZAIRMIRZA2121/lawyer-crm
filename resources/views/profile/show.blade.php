@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>My Profile</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="col-md-6 mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="{{ old('username', $user->username) }}">
                    @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Contact --}}
                <div class="col-md-6 mb-3">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact', $user->contact) }}">
                    @error('contact')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Qualification --}}
                <div class="col-md-6 mb-3">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control"
                        value="{{ old('qualification', $user->qualification) }}">
                    @error('qualification')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Role (disabled) --}}
                <div class="col-md-6 mb-3">
                    <label>Role</label>
                    <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                </div>

                {{-- Facebook --}}
                <div class="col-md-4 mb-3">
                    <label>Facebook</label>
                    <input type="text" name="facebook" class="form-control"
                        value="{{ old('facebook', $user->facebook) }}">
                    @error('facebook')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Twitter --}}
                <div class="col-md-4 mb-3">
                    <label>Twitter</label>
                    <input type="text" name="twitter" class="form-control" value="{{ old('twitter', $user->twitter) }}">
                    @error('twitter')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- LinkedIn --}}
                <div class="col-md-4 mb-3">
                    <label>LinkedIn</label>
                    <input type="text" name="linkedin" class="form-control"
                        value="{{ old('linkedin', $user->linkedin) }}">
                    @error('linkedin')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="col-md-6 mb-3">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div class="col-md-6 mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                {{-- Profile Image --}}
                <div class="col-md-6 mb-3">
                    <label>Profile Image</label>
                    <input type="file" name="profile_img" class="form-control">
                    @error('profile_img')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    
                </div>
                <div class="col-md-6 mb-3">
                 
                    @if ($user->profile_img)
                        <img src="{{ asset('storage/' . $user->profile_img) }}" class="mt-2" style="height: 100px;">
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
@endsection
