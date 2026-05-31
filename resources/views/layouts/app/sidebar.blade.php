<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')

    <style>
        @media (max-width: 1023px) {
            #mobileSidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                width: 280px !important;
                z-index: 99999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
                overflow-y: auto !important;
            }

            body.sidebar-open #mobileSidebar {
                transform: translateX(0) !important;
            }

            #sidebarOverlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 99998;
            }

            body.sidebar-open #sidebarOverlay {
                display: block;
            }

            .mobile-menu-btn,
            .mobile-close-btn {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border-radius: 10px;
                border: none;
                background: transparent;
                color: #111;
                font-size: 28px;
                line-height: 1;
            }

            .mobile-menu-btn:hover,
            .mobile-close-btn:hover {
                background: #f4f4f5;
            }
        }

        .custom-user-dropdown {
            display: none;
        }

        .custom-user-dropdown.show {
            display: block;
        }
    </style>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <flux:sidebar id="mobileSidebar" sticky
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />

            <button type="button" class="mobile-close-btn lg:hidden" onclick="closeMobileSidebar()">
                ×
            </button>
        </flux:sidebar.header>

        <livewire:team-switcher />

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">

                @can('dashboard view')
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                @endcan

                @can('games view')
                    <flux:sidebar.item icon="squares-2x2" :href="route('games.index')" :current="request()->routeIs('games.*')" wire:navigate>
                        {{ __('Games') }}
                    </flux:sidebar.item>
                @endcan

                @can('game-results view')
                    <flux:sidebar.item icon="calendar-days" :href="route('game-results.today-update')" :current="request()->routeIs('game-results.today-update')" wire:navigate>
                        {{ __('Today Result Update') }}
                    </flux:sidebar.item>
                @endcan

                @can('game-results views')
                    <flux:sidebar.item icon="chart-bar-square" :href="route('game-results.index')" :current="request()->routeIs('game-results.*')" wire:navigate>
                        {{ __('Game Results') }}
                    </flux:sidebar.item>
                @endcan

                @can('chart-years view')
                    <flux:sidebar.item icon="calendar-days" :href="route('chart-years.index')" :current="request()->routeIs('chart-years.*')" wire:navigate>
                        {{ __('Chart Years') }}
                    </flux:sidebar.item>
                @endcan

                @can('advertisements view')
                    <flux:sidebar.item icon="megaphone" :href="route('advertisements.index')" :current="request()->routeIs('advertisements.*')" wire:navigate>
                        {{ __('Advertisements') }}
                    </flux:sidebar.item>
                @endcan

                @can('notices view')
                    <flux:sidebar.item icon="bell-alert" :href="route('notices.index')" :current="request()->routeIs('notices.*')" wire:navigate>
                        {{ __('Notices') }}
                    </flux:sidebar.item>
                @endcan

                @can('content-blocks view')
                    <flux:sidebar.item icon="document-text" :href="route('content-blocks.index')" :current="request()->routeIs('content-blocks.*')" wire:navigate>
                        {{ __('Content Blocks') }}
                    </flux:sidebar.item>
                @endcan

                @can('seo-pages view')
                    <flux:sidebar.item icon="globe-alt" :href="route('seo-pages.index')" :current="request()->routeIs('seo-pages.*')" wire:navigate>
                        {{ __('SEO Pages') }}
                    </flux:sidebar.item>
                @endcan

                @can('users view')
                    <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                        {{ __('Users') }}
                    </flux:sidebar.item>
                @endcan

                @can('roles view')
                    <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>
                        {{ __('Roles') }}
                    </flux:sidebar.item>
                @endcan

                @can('permissions view')
                    <flux:sidebar.item icon="key" :href="route('permissions.index')" :current="request()->routeIs('permissions.*')" wire:navigate>
                        {{ __('Permissions') }}
                    </flux:sidebar.item>
                @endcan

            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- Custom user dropdown for mobile + desktop --}}
        <div class="relative px-3 pb-4">
            <div id="sidebarUserDropdown"
                class="custom-user-dropdown absolute left-3 right-3 bottom-20 z-[999999] overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex items-center gap-3 border-b border-zinc-200 px-3 py-3 dark:border-zinc-700">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-200 text-sm font-semibold text-zinc-900">
                        {{ auth()->user()->initials() }}
                    </div>

                    <div class="min-w-0">
                        <div class="truncate text-sm font-semibold text-zinc-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="truncate text-xs text-zinc-500">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" wire:navigate
                    class="flex items-center gap-3 px-4 py-3 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    <span>⚙️</span>
                    <span>{{ __('Settings') }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800">
                        <span>↪️</span>
                        <span>{{ __('Log out') }}</span>
                    </button>
                </form>
            </div>

            <button type="button" id="sidebarUserButton" onclick="toggleSidebarUserDropdown()"
                class="w-full flex items-center gap-3 rounded-xl border border-zinc-300 bg-white px-3 py-2 text-left dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-200 text-sm font-semibold text-zinc-900">
                    {{ auth()->user()->initials() }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-semibold text-zinc-900 dark:text-white">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="truncate text-xs text-zinc-500">
                        {{ auth()->user()->currentTeam?->name ?? "Super Admin's Team" }}
                    </div>
                </div>

                <span class="text-xl leading-none text-zinc-700 dark:text-zinc-200">⌄</span>
            </button>
        </div>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <button type="button" class="mobile-menu-btn" onclick="openMobileSidebar()">
            ☰
        </button>

        <flux:spacer />

        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-200 text-sm font-semibold text-zinc-900">
            {{ auth()->user()->initials() }}
        </div>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts

    <script>
        function openMobileSidebar() {
            document.body.classList.add('sidebar-open');
        }

        function closeMobileSidebar() {
            document.body.classList.remove('sidebar-open');
            closeSidebarUserDropdown();
        }

        function toggleSidebarUserDropdown() {
            const dropdown = document.getElementById('sidebarUserDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        function closeSidebarUserDropdown() {
            const dropdown = document.getElementById('sidebarUserDropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('sidebarUserDropdown');
            const button = document.getElementById('sidebarUserButton');

            if (!dropdown || !button) return;

            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
                closeSidebarUserDropdown();
            }
        });
    </script>
</body>

</html>