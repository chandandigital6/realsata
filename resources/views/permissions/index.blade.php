<x-layouts::app :title="__('Permissions')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Permissions</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Assign permissions to selected role or user.
            </p>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl bg-red-100 px-4 py-3 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Create Permission --}}
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">
                Create New Permission
            </h2>

            <form method="POST"
                  action="{{ route('permissions.store', request()->route('current_team')) }}"
                  class="flex flex-col gap-3 md:flex-row">
                @csrf

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: invoice create"
                       class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                <button type="submit"
                        class="rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                    Create
                </button>
            </form>
        </div>

        {{-- Assign Permissions --}}
        <form method="POST"
              action="{{ route('permissions.assign', request()->route('current_team')) }}"
              class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            @csrf

            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">
                Assign Permissions
            </h2>

            <div class="mb-6 grid gap-4 md:grid-cols-3">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Assign To
                    </label>
                    <select name="assign_type"
                            id="assign_type"
                            class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">Select Type</option>
                        <option value="role" @selected(old('assign_type') === 'role')>Role</option>
                        <option value="user" @selected(old('assign_type') === 'user')>User</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Select Role
                    </label>
                    <select name="role_id"
                            id="role_id"
                            class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Select User
                    </label>
                    <select name="user_id"
                            id="user_id"
                            class="w-full rounded-xl border border-neutral-300 px-4 py-3 text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-md font-semibold text-neutral-900 dark:text-white">
                    All Permissions
                </h3>

                <label class="flex items-center gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                    <input type="checkbox" id="check_all">
                    Select All
                </label>
            </div>

            <div class="grid gap-3 md:grid-cols-3">
                @forelse($permissions as $permission)
                    <label class="flex items-center gap-3 rounded-xl border border-neutral-200 p-3 dark:border-neutral-700">
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->id }}"
                               class="permission-checkbox"
                               @checked(is_array(old('permissions')) && in_array($permission->id, old('permissions')))>

                        <span class="text-sm text-neutral-800 dark:text-neutral-200">
                            {{ $permission->name }}
                        </span>
                    </label>
                @empty
                    <div class="col-span-3 rounded-xl border border-dashed border-neutral-300 p-6 text-center text-sm text-neutral-500">
                        No permissions found.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                    Assign Permissions
                </button>
            </div>
        </form>

        {{-- Delete Permissions Separate Section --}}
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-white">
                Manage Permissions
            </h2>

            <div class="grid gap-3 md:grid-cols-3">
                @forelse($permissions as $permission)
                    <div class="flex items-center justify-between rounded-xl border border-neutral-200 p-3 dark:border-neutral-700">
                        <span class="text-sm text-neutral-800 dark:text-neutral-200">
                            {{ $permission->name }}
                        </span>

                        <form method="POST"
                              action="{{ route('permissions.destroy', [request()->route('current_team'), $permission]) }}"
                              onsubmit="return confirm('Delete this permission?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-lg bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 hover:bg-red-200">
                                Delete
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-sm text-neutral-500">
                        No permissions found.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        const checkAll = document.getElementById('check_all');

        if (checkAll) {
            checkAll.addEventListener('change', function (e) {
                document.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
                    checkbox.checked = e.target.checked;
                });
            });
        }

        const assignType = document.getElementById('assign_type');

        if (assignType) {
            assignType.addEventListener('change', function () {
                if (this.value === 'role') {
                    document.getElementById('user_id').value = '';
                }

                if (this.value === 'user') {
                    document.getElementById('role_id').value = '';
                }
            });
        }
    </script>
</x-layouts::app>