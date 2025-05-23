@extends('layouts.app')

@section('content')
<div>
    <h1>Welcome to the Homepage</h1>
    <p>This is the default Homepage view.</p>
    <p>You can customize this view by creating a new file in the <code>resources/views/home</code> directory.</p>
    <p>Please note that this is a Laravel Blade template.</p>
    <p>You can also create a new route in <code>routes/web.php</code> to point to this view.</p>
</div>

<x-action-button action="{{ route('logout') }}" method="POST" buttonClass="text-red-600 hover:underline">
    Logout
</x-action-button>

@endsection