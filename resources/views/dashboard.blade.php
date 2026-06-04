<x-layouts::app :title="__('Dashboard')">

    @php
        $totalGames = \App\Models\Game::count();

        $activeGames = \App\Models\Game::where('is_active', 1)->count();

        $todayResults = \App\Models\GameResult::whereDate('result_date', today())->count();

        $declaredResults = \App\Models\GameResult::where('status', 'declared')->count();

        $waitingResults = \App\Models\GameResult::where('status', 'waiting')->count();

        $chartYears = \App\Models\ChartYear::count();

        $advertisements = \App\Models\Advertisement::count();

        $notices = \App\Models\Notice::count();

        $latestResults = \App\Models\GameResult::with('game')
            ->latest('result_date')
            ->take(10)
            ->get();

        // $games = \App\Models\Game::with('todayResult')
        //     ->where('is_active', true)
        //     ->orderBy('sort_order')
        //     ->get();

        $games = auth()->user()
    ->games()
    ->with('todayResult')
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();



            $date = request('date', now('Asia/Kolkata')->format('Y-m-d'));

// $existingResults = \App\Models\GameResult::whereDate('result_date', $date)
//     ->get()
//     ->keyBy('game_id');

$existingResults = \App\Models\GameResult::whereDate('result_date', $date)
    ->whereIn('game_id', $games->pluck('id'))
    ->get()
    ->keyBy('game_id');
    @endphp




    @can('update game dashboard')


    {{-- Dashboard Result Update Form --}}
<div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

    <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
                Today Result Update
            </h2>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Dashboard se directly game result update karein.
            </p>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="flex gap-2">
            <input type="date"
                   name="date"
                   value="{{ $date }}"
                   class="rounded-xl border border-neutral-300 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

            <button type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white">
                Filter
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('game-results.today-update-new.save') }}">
        @csrf

        <input type="hidden" name="result_date" value="{{ $date }}">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-100 dark:bg-neutral-800">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">Game</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">Result</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">Status</th>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">Show Time</th>
                        <th class="px-5 py-4 text-right text-xs font-bold uppercase text-neutral-600 dark:text-neutral-300">Action</th>
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

                                <div class="mt-1">
                                    <span class="inline-flex rounded bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-700">
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
        
    @endcan













@can('view dashboard')
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header --}}
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
                Dashboard
            </h1>

            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Welcome to Real Satta Admin Dashboard.
            </p>
        </div>

        {{-- Stats --}}
        <div class="grid gap-4 md:grid-cols-4">

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Total Games
                </div>

                <div class="mt-3 text-4xl font-bold text-black dark:text-white">
                    {{ $totalGames }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Active Games
                </div>

                <div class="mt-3 text-4xl font-bold text-green-600">
                    {{ $activeGames }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Today Results
                </div>

                <div class="mt-3 text-4xl font-bold text-blue-600">
                    {{ $todayResults }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Declared Results
                </div>

                <div class="mt-3 text-4xl font-bold text-purple-600">
                    {{ $declaredResults }}
                </div>
            </div>

        </div>

        {{-- More Stats --}}
        <div class="grid gap-4 md:grid-cols-4">

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Waiting Results
                </div>

                <div class="mt-3 text-4xl font-bold text-yellow-500">
                    {{ $waitingResults }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Chart Years
                </div>

                <div class="mt-3 text-4xl font-bold text-pink-600">
                    {{ $chartYears }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Advertisements
                </div>

                <div class="mt-3 text-4xl font-bold text-orange-600">
                    {{ $advertisements }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Notices
                </div>

                <div class="mt-3 text-4xl font-bold text-red-600">
                    {{ $notices }}
                </div>
            </div>

        </div>

        {{-- Live Result Cards --}}
        <div class="grid gap-4 md:grid-cols-3">

            @forelse($games as $game)

                <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm transition hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-900">

                    <div class="border-b border-neutral-200 bg-black px-5 py-4 dark:border-neutral-700 dark:bg-white">

                        <div class="flex items-center justify-between">

                            <div>
                                <h3 class="text-lg font-bold text-white dark:text-black">
                                    {{ strtoupper($game->name) }}
                                </h3>

                                <p class="text-xs text-neutral-300 dark:text-neutral-700">
                                    {{ $game->result_time ? \Carbon\Carbon::parse($game->result_time)->format('h:i A') : '-' }}
                                </p>
                            </div>

                            @if($game->todayResult && $game->todayResult->status === 'declared')
                                <span class="rounded-full bg-green-500 px-3 py-1 text-xs font-semibold text-white">
                                    Live
                                </span>
                            @else
                                <span class="rounded-full bg-yellow-500 px-3 py-1 text-xs font-semibold text-white">
                                    Waiting
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="flex flex-col items-center justify-center p-8">

                        @if($game->todayResult && $game->todayResult->status === 'declared')

                            <div class="text-6xl font-black tracking-wider text-black dark:text-white">
                                {{ $game->todayResult->result }}
                            </div>

                        @else

                            <img src="/m/d.gif"
                                 alt="Waiting"
                                 class="h-12">

                        @endif

                    </div>

                </div>

            @empty

                <div class="col-span-3 rounded-2xl border border-neutral-200 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white">
                        No Games Found
                    </h3>

                </div>

            @endforelse

        </div>

        {{-- Latest Results Table --}}
        <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">

                <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
                    Latest Results
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">

                    <thead class="bg-neutral-100 dark:bg-neutral-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">
                                Game
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">
                                Date
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">
                                Result
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-neutral-600 dark:text-neutral-300">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($latestResults as $result)

                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">

                                <td class="px-6 py-4 font-semibold text-neutral-900 dark:text-white">
                                    {{ $result->game->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $result->result_date ? $result->result_date->format('d M Y') : '-' }}
                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-xl bg-black px-4 py-2 text-sm font-bold text-white dark:bg-white dark:text-black">
                                        {{ $result->result ?? '-' }}
                                    </span>

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

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-10 text-center text-neutral-500">
                                    No results found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    @endcan



</x-layouts::app>