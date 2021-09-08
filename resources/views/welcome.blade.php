@extends('layouts.app')

@section('content')
<div class="container-lg">
    <div class="row justify-content-center">
        @guest
            <home-component/>
        @else
            <dashboard-component/>
        @endguest
    </div>
</div>
@endsection
