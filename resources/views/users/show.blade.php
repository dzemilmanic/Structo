@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $user->name }} {{ $user->lastname }}</h1>
        <p>Email: {{ $user->email }}</p>
        <p>Role: {{ $user->role }}</p>
        <!-- Dodaj ostale podatke po potrebi -->
    </div>
@endsection
