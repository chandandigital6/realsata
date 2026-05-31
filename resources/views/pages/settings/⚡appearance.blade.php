{{-- <?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section> --}}







<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">

        <div class="inline-flex rounded-xl bg-zinc-100 p-1 dark:bg-zinc-800">
            <button type="button" data-appearance="light" onclick="setThemeMode('light')"
                class="theme-btn rounded-lg px-4 py-2 text-sm font-medium">
                ☀️ Light
            </button>

            <button type="button" data-appearance="dark" onclick="setThemeMode('dark')"
                class="theme-btn rounded-lg px-4 py-2 text-sm font-medium">
                🌙 Dark
            </button>

            <button type="button" data-appearance="system" onclick="setThemeMode('system')"
                class="theme-btn rounded-lg px-4 py-2 text-sm font-medium">
                🖥️ System
            </button>
        </div>

    </x-pages::settings.layout>
</section>

<script>
    function applyThemeMode(mode) {
        const root = document.documentElement;

        if (mode === 'dark') {
            root.classList.add('dark');
        } else if (mode === 'light') {
            root.classList.remove('dark');
        } else {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                root.classList.add('dark');
            } else {
                root.classList.remove('dark');
            }
        }

        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'dark:bg-zinc-700');
        });

        const active = document.querySelector('[data-appearance="' + mode + '"]');
        if (active) {
            active.classList.add('bg-white', 'shadow-sm', 'dark:bg-zinc-700');
        }
    }

    function setThemeMode(mode) {
        localStorage.setItem('appearance', mode);
        applyThemeMode(mode);
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyThemeMode(localStorage.getItem('appearance') || 'system');
    });
</script>