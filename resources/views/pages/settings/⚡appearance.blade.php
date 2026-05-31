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

        <div class="inline-flex rounded-xl border border-zinc-200 bg-zinc-100 p-1 dark:border-zinc-700 dark:bg-zinc-800">
            <button type="button"
                data-appearance-btn="light"
                onclick="setCustomAppearance('light')"
                class="appearance-btn flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-white dark:text-zinc-200 dark:hover:bg-zinc-700">
                ☀️ {{ __('Light') }}
            </button>

            <button type="button"
                data-appearance-btn="dark"
                onclick="setCustomAppearance('dark')"
                class="appearance-btn flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-white dark:text-zinc-200 dark:hover:bg-zinc-700">
                🌙 {{ __('Dark') }}
            </button>

            <button type="button"
                data-appearance-btn="system"
                onclick="setCustomAppearance('system')"
                class="appearance-btn flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-white dark:text-zinc-200 dark:hover:bg-zinc-700">
                🖥️ {{ __('System') }}
            </button>
        </div>

    </x-pages::settings.layout>
</section>

<script>
    function applyCustomAppearance(value) {
        const root = document.documentElement;

        if (value === 'dark') {
            root.classList.add('dark');
        } else if (value === 'light') {
            root.classList.remove('dark');
        } else {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                root.classList.add('dark');
            } else {
                root.classList.remove('dark');
            }
        }

        document.querySelectorAll('.appearance-btn').forEach(function(btn) {
            btn.classList.remove('bg-white', 'shadow-sm', 'dark:bg-zinc-700');
        });

        const activeBtn = document.querySelector('[data-appearance-btn="' + value + '"]');
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'shadow-sm', 'dark:bg-zinc-700');
        }
    }

    function setCustomAppearance(value) {
        localStorage.setItem('appearance', value);
        applyCustomAppearance(value);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedAppearance = localStorage.getItem('appearance') || 'system';
        applyCustomAppearance(savedAppearance);
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
        const savedAppearance = localStorage.getItem('appearance') || 'system';

        if (savedAppearance === 'system') {
            applyCustomAppearance('system');
        }
    });
</script>
