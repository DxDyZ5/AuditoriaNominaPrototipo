@extends('layouts.wrapper')

@section('body')
    <body class="d-flex flex-column min-vh-100" @if(request()->is('reports/*') && !request()->is('reports/*/edit')) data-spy="scroll" data-target="#reports-navbar" data-offset="{{ Auth::check() ? '73' : '145' }}" @endif>
        @guest
            @if(config('settings.announcement_guest'))
                @include('shared.announcement', ['message' => config('settings.announcement_guest'), 'type' => config('settings.announcement_guest_type'), 'id' => config('settings.announcement_guest_id')])
            @endif
        @else
            @if(config('settings.announcement_user'))
                @include('shared.announcement', ['message' => config('settings.announcement_user'), 'type' => config('settings.announcement_user_type'), 'id' => config('settings.announcement_user_id')])
            @endif
        @endguest

        @include('shared.header')

        <div class="d-flex flex-column flex-fill @auth content @endauth" style="min-height: 100vh;">
            <main class="flex-grow-1 pb-5 bg-base-1">
                @yield('content')
            </main>

            @include('shared.modals.confirmation')
            @include('shared.footer')
        </div>
    </body>
@endsection
