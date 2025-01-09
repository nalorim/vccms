@extends('layouts.main')

@section('content')

<div class="container">
    <div class="body">

        <div class="sidebar">
            <livewire:menu.sub />
        </div>

        <div class="col-span-10 px-4">
            @yield('sub')
        </div>

    </div>

</div>

@endsection