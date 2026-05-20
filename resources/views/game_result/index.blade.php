<x-layouts::app :title="__('Game Results')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Game Results
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Manage daily game results from here.
                </p>
            </div>

            <a href="{{ route('game-results.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                + Add Result
            </a>
        </div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Total Results</div>
                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ \App\Models\GameResult::count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Today Results</div>
                <div class="mt-2 text-3xl font-bold text-blue-600">
                    {{ \App\Models\GameResult::whereDate('result_date', today())->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Declared</div>
                <div class="mt-2 text-3xl font-bold text-green-600">
                    {{ \App\Models\GameResult::where('status', 'declared')->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Waiting</div>
                <div class="mt-2 text-3xl font-bold text-yellow-600">
                    {{ \App\Models\GameResult::where('status', 'waiting')->count() }}
                </div>
            </div>

        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative h-full flex-1 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">

                    <thead class="bg-neutral-100 dark:bg-neutral-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Game</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Result Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Result</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($results as $result)

                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $result->id }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-neutral-900 dark:text-white">
                                        {{ $result->game->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-neutral-500">
                                        {{ $result->game->slug ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $result->result_date ? $result->result_date->format('d M Y') : '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($result->result)
                                        <span class="rounded-xl bg-black px-4 py-2 text-sm font-bold text-white dark:bg-white dark:text-black">
                                            {{ $result->result }}
                                        </span>
                                    @else
                                        <span class="text-sm text-neutral-400">Pending</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($result->status === 'declared')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Declared
                                        </span>
                                    @else
                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Waiting
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('game-results.edit', $result) }}"
                                           class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-100">
                                            Edit
                                        </a>

                                        <a href="{{ route('game-results.delete', $result) }}"
                                           onclick="return confirm('Delete this result?')"
                                           class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                            Delete
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                                        No Results Found
                                    </h3>

                                    <p class="mt-1 text-sm text-neutral-500">
                                        Add your first game result.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div>
            {{ $results->links() }}
        </div>

    </div>

</x-layouts::app>