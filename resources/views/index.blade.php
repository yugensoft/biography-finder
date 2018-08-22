@extends('base')

@section('title', 'Biography Finder')

@section('content')
    @if(Request::exists('show_tech_stack'))
        @include('tech_stack')
    @endif

    {{-- React mount point --}}
    <div id="root"></div>

    <script>
        var countries = @json($countries);
        var fields = @json($fields);
    </script>

    @if(Auth::check() and Auth::user()->isSuperAdmin())
        <script>var can_edit = true;</script>
    @else
        <script>var can_edit = false;</script>
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
@endsection