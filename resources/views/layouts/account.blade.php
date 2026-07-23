<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'حساب کاربری - ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @media (max-width: 640px) {
            body {
                overflow-x: hidden !important;
            }

            .fixed.inset-0 {
                overscroll-behavior-x: none;
            }
        }
    </style>
</head>
<body>
<div class="min-h-screen bg-base-200">
    {{-- Mobile Header --}}
    <div class="lg:hidden">
        @livewire('layout.account.mobile-header')
    </div>

    {{-- Mobile Drawer Overlay --}}
    <div x-data="{ open: false }" @toggle-sidebar.window="open = !open">
        {{-- Overlay --}}
        <div x-show="open"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"
             @click="open = false">
        </div>

        {{-- Sidebar Drawer --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed right-0 top-0 h-full w-72 bg-base-100 shadow-xl z-50 lg:hidden">
            @livewire('layout.account.sidebar')
        </div>

        {{-- Main Layout --}}
        <div class="flex">
            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:block w-72 min-h-screen bg-base-100 border-l border-base-300">
                @livewire('layout.account.sidebar')
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 p-4 min-w-0 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
