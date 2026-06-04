<x-layouts::app :title="'Assign Games'">

    <div class="mx-auto max-w-6xl space-y-6">

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Assign Games To User
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    User select karke multiple games assign ya remove karein.
                </p>
            </div>

            <a href="{{ route('games.index') }}"
               class="rounded-xl bg-neutral-800 px-5 py-2 text-sm font-semibold text-white hover:bg-neutral-700">
                Back To Games
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <form method="GET" action="{{ route('games.assign-users') }}" class="grid gap-4 md:grid-cols-[1fr_auto]">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Select User
                    </label>

                    <select name="user_id"
                            class="w-full rounded-xl border border-neutral-300 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">-- Select User --</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                {{ $user->name ?? 'No Name' }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">
                        Load Games
                    </button>
                </div>
            </form>

        </div>

        @if($selectedUser)
            <form method="POST"
                  action="{{ route('games.assign-users.save') }}"
                  class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

                @csrf

                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">

                <div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
                            Assign Games
                        </h2>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Selected User:
                            <strong>{{ $selectedUser->name }}</strong>
                            {{ $selectedUser->email ? '('.$selectedUser->email.')' : '' }}
                        </p>
                    </div>

                    <button type="submit"
                            class="rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700">
                        Save Assignment
                    </button>
                </div>

                <div class="mb-4 flex gap-3">
                    <button type="button"
                            onclick="document.querySelectorAll('.game-checkbox').forEach(cb => cb.checked = true)"
                            class="rounded-lg bg-neutral-100 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-200">
                        Select All
                    </button>

                    <button type="button"
                            onclick="document.querySelectorAll('.game-checkbox').forEach(cb => cb.checked = false)"
                            class="rounded-lg bg-neutral-100 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-200 dark:bg-neutral-800 dark:text-neutral-200">
                        Remove All
                    </button>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

                    @forelse($games as $game)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-neutral-200 p-4 hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800">

                            <input type="checkbox"
                                   name="game_ids[]"
                                   value="{{ $game->id }}"
                                   @checked(in_array($game->id, $assignedGameIds))
                                   class="game-checkbox h-5 w-5 rounded border-neutral-300 text-blue-600">

                            <div>
                                <div class="font-semibold text-neutral-900 dark:text-white">
                                    {{ $game->name }}
                                </div>

                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Result Time:
                                    {{ $game->result_time ? \Carbon\Carbon::parse($game->result_time)->format('h:i A') : 'N/A' }}
                                </div>
                            </div>

                        </label>
                    @empty
                        <div class="col-span-full rounded-xl bg-yellow-50 p-4 text-sm text-yellow-700">
                            Koi active game nahi mila.
                        </div>
                    @endforelse

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800 dark:bg-white dark:text-black">
                        Save Assignment
                    </button>
                </div>

            </form>
        @else
            <div class="rounded-2xl border border-dashed border-neutral-300 bg-white p-8 text-center dark:border-neutral-700 dark:bg-neutral-900">
                <h3 class="text-lg font-bold text-neutral-900 dark:text-white">
                    Pehle User Select Karein
                </h3>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    User select karne ke baad uske assigned games yahan show honge.
                </p>
            </div>
        @endif

    </div>

</x-layouts::app>