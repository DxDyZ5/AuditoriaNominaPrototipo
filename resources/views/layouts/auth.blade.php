@extends('layouts.wrapper')

@section('body')
    <body class="d-flex flex-column">
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
            <main class="flex-grow-1 d-flex flex-column bg-base-1">
                @yield('content')
            </main>

            @include('shared.footer', ['lightweight' => true])
        </div>
    </body>
@endsection
