<x-layouts::app :title="__('Create Role')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div>
            <h1 class="text-2xl font-bold">Create Role</h1>
            <p class="text-sm text-neutral-500">Add new role and assign permissions.</p>
        </div>

        <form method="POST"
              action="{{ route('roles.store', ['current_team' => request()->route('current_team')]) }}"
              class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-900">
            @csrf

            <div class="mb-6">
                <label class="mb-2 block text-sm font-semibold">Role Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: manager"
                       class="w-full rounded-xl border border-neutral-300 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800">

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <h2 class="mb-3 text-lg font-semibold">Permissions</h2>

                <div class="grid gap-3 md:grid-cols-3">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-3 rounded-xl border border-neutral-200 p-3 dark:border-neutral-700">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   @checked(in_array($permission->id, old('permissions', [])))>

                            <span class="text-sm">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button class="rounded-xl bg-black px-5 py-2 text-sm font-semibold text-white">
                    Save Role
                </button>

                <a href="{{ route('roles.index', ['current_team' => request()->route('current_team')]) }}"
                   class="rounded-xl bg-neutral-200 px-5 py-2 text-sm font-semibold">
                    Back
                </a>
            </div>
        </form>

    </div>
</x-layouts::app>