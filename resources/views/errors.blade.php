@extends('layout/layout')

@section('title', 'Error')

@section('content')
    <div class="main-container">
        <h1>Error</h1>
        <p>Sorry, an error occurred while processing your request.</p>
        <p>
        Error: {{$message}}
        </p>
    </div>
@endsection