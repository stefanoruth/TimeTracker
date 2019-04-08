@extends('master')
@section('body')
<div class="flex justify-center items-center h-screen w-full bg-blue-500">
    <div class="text-center">
        <h1 class="text-3xl text-white mb-2">TimeTracker</h1>
        <a href="{{ url('login') }}" class="text-4xl text-blue-500 bg-white px-4 py-1 block hover:text-blue-700">Log In</a>
    </div>
</div>

@stop
