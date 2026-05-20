<x-layouts::app :title="isset($contentBlock) ? __('Edit Content Block') : __('Create Content Block')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    {{ isset($contentBlock) ? 'Edit Content Block' : 'Create Content Block' }}
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ isset($contentBlock) ? 'Update content block details.' : 'Add new content block.' }}
                </p>
            </div>

            <a href="{{ route('content-blocks.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-neutral-300 px-5 py-3 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                Back
            </a>
        </div>

        <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <form method="POST"
                  action="{{ isset($contentBlock) ? route('content-blocks.update', $contentBlock) : route('content-blocks.store') }}"
                  class="space-y-6">

                @csrf

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Title
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title', $contentBlock->title ?? '') }}"
                               placeholder="Enter title"
                               class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                            Key
                        </label>

                        <input type="text"
                               name="key"
                               value="{{ old('key', $contentBlock->key ?? '') }}"
                               placeholder="example: home-about-section"
                               class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">

                        @error('key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Content
                    </label>

                    <textarea name="content"
                              rows="8"
                              placeholder="Enter content here..."
                              class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm outline-none focus:border-black dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">{{ old('content', $contentBlock->content ?? '') }}</textarea>

                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           id="is_active"
                           @checked(old('is_active', $contentBlock->is_active ?? true))
                           class="h-5 w-5 rounded border-neutral-300 text-black focus:ring-black">

                    <label for="is_active" class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">
                        Active Content
                    </label>
                </div>

                <div class="flex flex-col gap-3 border-t border-neutral-200 pt-6 md:flex-row md:items-center md:justify-end dark:border-neutral-700">

                    <a href="{{ route('content-blocks.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-neutral-300 px-5 py-3 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 dark:bg-white dark:text-black">
                        {{ isset($contentBlock) ? 'Update Content' : 'Create Content' }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts::app>