<x-layouts::app :title="__('Roles Management')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    Roles Management
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Manage all roles and permissions from here.
                </p>
            </div>

            <a href="{{ route('roles.create', request()->route('current_team')) }}"
               class="inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                + Create Role
            </a>

        </div>

        {{-- Stats --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Total Roles
                </div>

                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ $roles->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Total Permissions
                </div>

                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ \Spatie\Permission\Models\Permission::count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Admin Roles
                </div>

                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ \Spatie\Permission\Models\Role::where('name','admin')->count() }}
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Super Admin
                </div>

                <div class="mt-2 text-3xl font-bold text-black dark:text-white">
                    {{ \Spatie\Permission\Models\Role::where('name','super-admin')->count() }}
                </div>
            </div>

        </div>

        {{-- Table --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">

                    <thead class="bg-neutral-100 dark:bg-neutral-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                #
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                Role
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                Permissions
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($roles as $role)

                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $role->id }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-sm font-bold text-white dark:bg-white dark:text-black">
                                            {{ strtoupper(substr($role->name,0,1)) }}
                                        </div>

                                        <div>

                                            <div class="font-semibold text-neutral-900 dark:text-white">
                                                {{ ucfirst($role->name) }}
                                            </div>

                                            <div class="text-xs text-neutral-500">
                                                {{ $role->created_at->format('d M Y') }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex flex-wrap gap-2">

                                        @forelse($role->permissions as $permission)

                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                {{ $permission->name }}
                                            </span>

                                        @empty

                                            <span class="text-sm text-neutral-400">
                                                No Permissions
                                            </span>

                                        @endforelse

                                    </div>

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('roles.show',[request()->route('current_team'),$role]) }}"
                                           class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                                            View
                                        </a>

                                        <a href="{{ route('roles.edit',[request()->route('current_team'),$role]) }}"
                                           class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700 transition hover:bg-yellow-100 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                            Edit
                                        </a>

                                        <form action="{{ route('roles.destroy',[request()->route('current_team'),$role]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this role?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-20 text-center">

                                    <div class="flex flex-col items-center justify-center">

                                        <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-10 w-10 text-neutral-400"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="1.5"
                                                      d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />

                                            </svg>

                                        </div>

                                        <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                                            No Roles Found
                                        </h3>

                                        <p class="mt-1 text-sm text-neutral-500">
                                            Create your first role to manage permissions.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts::app>