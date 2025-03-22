<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

{{-- NAVBAR mobile only --}}
<x-nav sticky full-width>

    <x-slot:brand>
        {{-- Drawer toggle for "main-drawer" --}}
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
        </label>

        {{-- Brand --}}
        <div>App</div>
    </x-slot:brand>

    {{-- Right side actions --}}
    <x-slot:actions>
        <x-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive/>
        <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive/>
        <x-theme-toggle class="btn btn-circle" darkTheme="dark" lightTheme="light"/>
    </x-slot:actions>
</x-nav>

{{-- MAIN --}}
<x-main with-nav full-width>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

        {{-- MENU --}}
        <x-menu activate-by-route>

            {{-- User --}}
            @if($user = auth()->user())
                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
                    <x-slot:actions>
                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"
                                  no-wire-navigate link="{{ route('logout') }}"/>
                    </x-slot:actions>
                </x-list-item>



                <x-menu-separator/>
            @endif

            <x-menu-item title="Usuarios" icon="o-sparkles" link="{{ route('user.list') }}"/>
            @role('admin')
            <x-menu-item title="Operadora" icon="o-sparkles" link="{{ route('operadora.list') }}"/>
            @endrole
            @role('operadora')
            <x-menu-item title="Convenios" icon="o-sparkles" link="{{ route('convenio.list') }}"/>
            @endrole

            @role('convenio')
            <x-menu-item title="Conveniadas" icon="o-sparkles" link="{{ route('conveniada.list') }}"/>
            @endrole
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>

{{--  TOAST area --}}
<x-toast/>
</body>
</html>
