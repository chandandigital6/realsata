<x-layouts::app :title="__('Role Permissions')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $role->name }}</h1>
                <p class="text-sm text-neutral-500">All permissions assigned to this role.</p>
            </div>

            <a href="{{ route('roles.edit', ['current_team' => request()->route('current_team'), 'role' => $role->id]) }}"
               class="rounded-xl bg-black px-5 py-2 text-sm font-semibold text-white">
                Edit Role
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            <h2 class="mb-4 text-lg font-semibold">Permissions</h2>

            <div class="flex flex-wrap gap-3">
                @forelse($role->permissions as $permission)
                    <div class="flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2 text-sm text-blue-700">
                        <span>{{ $permission->name }}</span>

                        <form method="POST"
                              action="{{ route('roles.permissions.remove', [
                                  'current_team' => request()->route('current_team'),
                                  'role' => $role->id,
                                  'permission' => $permission->id
                              ]) }}"
                              onsubmit="return confirm('Remove this permission from role?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="ml-1 rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">
                                ×
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-neutral-500">No permissions assigned.</p>
                @endforelse
            </div>
        </div>

        <a href="{{ route('roles.index', ['current_team' => request()->route('current_team')]) }}"
           class="w-fit rounded-xl bg-neutral-200 px-5 py-2 text-sm font-semibold">
            Back
        </a>

    </div>
</x-layouts::app>