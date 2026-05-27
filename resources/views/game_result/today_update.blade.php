
<x-layouts::app :title="__('Today Result Update')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Today Result Update
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Ek hi page se sabhi games ka result update karein.
                </p>
            </div>

            <a href="{{ route('game-results.index') }}"
               class="rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white dark:bg-white dark:text-black">
                Back
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <form method="GET" action="{{ route('game-results.today-update') }}"
                  class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">

                <input type="date"
                       name="date"
                       value="{{ $date }}"
                       class="rounded-xl border border-neutral-300 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Filter
                </button>
            </form>

            <form method="POST" action="{{ route('game-results.today-update.save') }}">
                @csrf

                <input type="hidden" name="result_date" value="{{ $date }}">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-100 dark:bg-neutral-800">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">
                                    Game
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">
                                    Result
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">
                                    Status
                                </th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">
                                    Show Time
                                </th>
                                <th class="px-5 py-4 text-right text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                            @foreach($games as $index => $game)
                                @php
                                    $oldResult = $existingResults[$game->id] ?? null;
                                    $gameTime = $game->time
                                        ?? $game->timing
                                        ?? $game->result_time
                                        ?? $game->open_time
                                        ?? null;
                                @endphp

                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-neutral-900 dark:text-white">
                                            {{ $game->name }}
                                        </div>

                                        <div class="mt-0.5">
    <span class="inline-flex rounded bg-blue-50 px-1.5 py-0.5 text-[9px] font-semibold leading-none text-blue-700">
        {{ $gameTime ? \Carbon\Carbon::parse($gameTime)->format('h:i A') : 'N/A' }}
    </span>
</div>

                                        <input type="hidden"
                                               name="results[{{ $index }}][game_id]"
                                               value="{{ $game->id }}">
                                    </td>

                                    <td class="px-5 py-4">
                                        <input type="text"
                                               name="results[{{ $index }}][result]"
                                               value="{{ old("results.$index.result", $oldResult->result ?? '') }}"
                                               placeholder="Result"
                                               class="w-32 rounded-xl border border-neutral-300 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                    </td>

                                    <td class="px-5 py-4">
                                        <select name="results[{{ $index }}][status]"
                                                class="rounded-xl border border-neutral-300 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                            <option value="declared" @selected(old("results.$index.status", $oldResult->status ?? 'declared') === 'declared')>
                                                Declared
                                            </option>
                                            <option value="waiting" @selected(old("results.$index.status", $oldResult->status ?? 'declared') === 'waiting')>
                                                Waiting
                                            </option>
                                        </select>
                                    </td>

                                    <td class="px-5 py-4">
                                        <input type="number"
                                               name="results[{{ $index }}][show_minutes]"
                                               value="{{ old("results.$index.show_minutes", $oldResult->show_minutes ?? 15) }}"
                                               min="0"
                                               class="w-28 rounded-xl border border-neutral-300 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <button type="submit"
                                                class="rounded-xl bg-green-600 px-4 py-2 text-sm font-bold text-white hover:bg-green-700">
                                            Update
                                        </button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800 dark:bg-white dark:text-black">
                        Update All Results
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-layouts::app>

