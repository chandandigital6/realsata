<x-layouts::app :title="__('Games Management')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Games Management
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Manage all games, result time and status from here.
                </p>
            </div>

            <a href="{{ route('games.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                + Create Game
            </a>
        </div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Total Games</div>
                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ $games->total() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Active Games</div>
                <div class="mt-2 text-3xl font-bold text-green-600">
                    {{ \App\Models\Game::where('is_active', 1)->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Inactive Games</div>
                <div class="mt-2 text-3xl font-bold text-red-600">
                    {{ \App\Models\Game::where('is_active', 0)->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">Today Results</div>
                <div class="mt-2 text-3xl font-bold text-blue-600">
                    {{ \App\Models\GameResult::whereDate('result_date', today())->count() }}
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
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Result Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Sort Order</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($games as $game)

                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $game->id }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-sm font-bold text-white dark:bg-white dark:text-black">
                                            {{ strtoupper(substr($game->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <div class="font-semibold text-neutral-900 dark:text-white">
                                                {{ $game->name }}
                                            </div>
                                            <div class="text-xs text-neutral-500">
                                                {{ $game->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $game->result_time ? \Carbon\Carbon::parse($game->result_time)->format('h:i A') : '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $game->sort_order }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($game->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('games.edit', $game) }}"
                                           class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-100">
                                            Edit
                                        </a>

                                        <a href="{{ route('games.delete', $game) }}"
                                           onclick="return confirm('Delete this game?')"
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
                                        No Games Found
                                    </h3>

                                    <p class="mt-1 text-sm text-neutral-500">
                                        Create your first game.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div>
            {{ $games->links() }}
        </div>

    </div>

</x-layouts::app>