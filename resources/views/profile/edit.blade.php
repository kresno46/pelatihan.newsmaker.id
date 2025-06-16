@extends('layouts.app')

@section('namePage', 'Profil Saya')

@section('content')
    <div class="space-y-10">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        {{-- @include('profile.partials.delete-user-form') --}}
    </div>
@endsection
