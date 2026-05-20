<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $search = '';
    public $showModal = false;
    public $viewModal = false;
    public $selectedUser = null;
    public $selectedRoles = [];
    public $selectedPermissions = [];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'User created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        session()->flash('success', 'User updated successfully.');
        $this->closeModal();
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        session()->flash('success', 'User deleted successfully.');
    }
    
    public function viewAccess($id)
    {
        $user = User::findOrFail($id);

        $this->selectedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->selectedRoles = method_exists($user, 'getRoleNames')
            ? $user->getRoleNames()->toArray()
            : [];

        $this->selectedPermissions = method_exists($user, 'getAllPermissions')
            ? $user->getAllPermissions()->pluck('name')->unique()->values()->toArray()
            : [];

        $this->viewModal = true;
    }

    public function closeViewModal()
    {
        $this->viewModal = false;
        $this->selectedUser = null;
        $this->selectedRoles = [];
        $this->selectedPermissions = [];
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $this->resetErrorBag();
    }

    public function with()
    {
        return [
            'users' => User::query()
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10),
        ];
    }
};

?>

<div class="p-6 space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Users</h1>
            <p class="text-sm text-gray-500">Create, edit, update and delete users.</p>
        </div>

        <flux:button variant="primary" wire:click="create">
            Add User
        </flux:button>
    </div>

    @if (session()->has('success'))
        <div class="rounded-xl bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border bg-white p-4 shadow-sm">
        <div class="mb-4">
            <flux:input wire:model.live="search" placeholder="Search by name or email..." />
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-3">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->email }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $user->created_at?->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">

                                    <flux:button size="sm" variant="filled" wire:click="viewAccess({{ $user->id }})">
                                        View
                                    </flux:button>
                                    <flux:button size="sm" wire:click="edit({{ $user->id }})">
                                        Edit
                                    </flux:button>

                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Are you sure you want to delete this user?"
                                    >
                                        Delete
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-bold">
                        {{ $userId ? 'Edit User' : 'Create User' }}
                    </h2>

                    <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-black">
                        ✕
                    </button>
                </div>

                <form wire:submit.prevent="{{ $userId ? 'update' : 'store' }}" class="space-y-4">

                    <flux:input label="Name" wire:model="name" placeholder="Enter name" />
                    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                    <flux:input label="Email" type="email" wire:model="email" placeholder="Enter email" />
                    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                    <flux:input
                        label="Password"
                        type="password"
                        wire:model="password"
                        placeholder="{{ $userId ? 'Leave blank if unchanged' : 'Enter password' }}"
                    />
                    @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                    <flux:input
                        label="Confirm Password"
                        type="password"
                        wire:model="password_confirmation"
                        placeholder="Confirm password"
                    />

                    <div class="flex justify-end gap-3 pt-4">
                        <flux:button type="button" wire:click="closeModal">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            {{ $userId ? 'Update User' : 'Create User' }}
                        </flux:button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    @if ($viewModal && $selectedUser)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-zinc-900">

                <div class="mb-6 flex items-start justify-between border-b pb-4 dark:border-zinc-700">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                            User Access Details
                        </h2>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $selectedUser['name'] }} — {{ $selectedUser['email'] }}
                        </p>
                    </div>

                    <button type="button" wire:click="closeViewModal" class="text-2xl text-zinc-500 hover:text-red-600">
                        ×
                    </button>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="rounded-xl border bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-3 font-semibold text-zinc-900 dark:text-white">
                            Roles
                        </h3>

                        <div class="space-y-2">
                            @forelse ($selectedRoles as $role)
                                <div class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white">
                                    {{ $role }}
                                </div>
                            @empty
                                <p class="text-sm text-zinc-500 dark:text-zinc-300">
                                    No role assigned.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-xl border bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                        <h3 class="mb-3 font-semibold text-zinc-900 dark:text-white">
                            Permissions
                        </h3>

                        <div class="max-h-80 space-y-2 overflow-y-auto pr-1">
                            @forelse ($selectedPermissions as $permission)
                                <div class="rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white">
                                    {{ $permission }}
                                </div>
                            @empty
                                <p class="text-sm text-zinc-500 dark:text-zinc-300">
                                    No permission assigned.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end border-t pt-4 dark:border-zinc-700">
                    <flux:button type="button" wire:click="closeViewModal">
                        Close
                    </flux:button>
                </div>

            </div>
        </div>
    @endif
</div>