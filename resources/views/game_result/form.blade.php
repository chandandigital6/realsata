<x-layouts::app :title="isset($gameResult) ? __('Edit Game Result') : __('Create Game Result')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    {{ isset($gameResult) ? 'Edit Game Result' : 'Create Game Result' }}
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ isset($gameResult) ? 'Update daily game result.' : 'Add daily game result.' }}
                </p>
            </div>

            <a href="{{ route('game-results.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-neutral-300 px-5 py-3 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                Back
            </a>
        </div>

        <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <form method="POST"
                  action="{{ isset($gameResult) ? route('game-results.update', $gameResult) : route('game-results.store') }}"
                  class="space-y-6">

                @csrf

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Game <span class="text-red-500">*</span>
                        </label>

                        <select name="game_id"
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                            <option value="">Select Game</option>

                            @foreach($games as $game)
                                <option value="{{ $game->id }}"
                                    @selected(old('game_id', $gameResult->game_id ?? '') == $game->id)>
                                    {{ $game->name }}
                                    @if($game->result_time)
                                        - {{ \Carbon\Carbon::parse($game->result_time)->format('h:i A') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('game_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Result Date <span class="text-red-500">*</span>
                        </label>

                        <input type="date"
                               name="result_date"
                               value="{{ old('result_date', isset($gameResult) && $gameResult->result_date ? $gameResult->result_date->format('Y-m-d') : date('Y-m-d')) }}"
                               class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                        @error('result_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Result
                        </label>

                        <input type="text"
                               name="result"
                               value="{{ old('result', $gameResult->result ?? '') }}"
                               maxlength="10"
                               placeholder="Example: 56"
                               class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm font-bold outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                        @error('result')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Status <span class="text-red-500">*</span>
                        </label>

                        <select name="status"
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                            <option value="waiting" @selected(old('status', $gameResult->status ?? 'waiting') === 'waiting')>
                                Waiting
                            </option>
                            <option value="declared" @selected(old('status', $gameResult->status ?? '') === 'declared')>
                                Declared
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex flex-col gap-3 border-t border-neutral-200 pt-6 md:flex-row md:items-center md:justify-end dark:border-neutral-700">

                    <a href="{{ route('game-results.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-neutral-300 px-5 py-3 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                        {{ isset($gameResult) ? 'Update Result' : 'Create Result' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts::app>