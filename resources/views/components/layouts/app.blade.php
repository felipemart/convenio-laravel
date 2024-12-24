<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

{{-- The navbar with `sticky` and `full-width` --}}
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
    </x-slot:actions>
</x-nav>

{{-- The main content with `full-width` --}}
<x-main with-nav full-width>

    {{-- This is a sidebar that works also as a drawer on small screens --}}
    {{-- Notice the `main-drawer` reference here --}}
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">

        {{-- User --}}
        @if($user = auth()->user())
            <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-2">
                <x-slot:actions>
                    <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate
                              link="/logout"/>
                </x-slot:actions>
            </x-list-item>

            <x-menu-separator/>
        @endif

        {{-- Activates the menu item when a route matches the `link` property --}}
        <x-menu activate-by-route>
            <x-menu-item title="Home" icon="o-home" link="###"/>
            <x-menu-item title="Messages" icon="o-envelope" link="###"/>
            <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                <x-menu-item title="Wifi" icon="o-wifi" link="####"/>
                <x-menu-item title="Archives" icon="o-archive-box" link="####"/>
            </x-menu-sub>

            @permission('acessar_operadora')
            <x-menu-item title="Operadora1" icon="o-sparkles" link="/"/>
            @endpermission

            @role(['admin2', 'operadora'])
            <x-menu-item title="Operadora2" icon="o-sparkles" link="/"/>
            @endrole


            <x-menu-item title="Usuarios" icon="o-sparkles" link="{{ route('user.list') }}"/>

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
