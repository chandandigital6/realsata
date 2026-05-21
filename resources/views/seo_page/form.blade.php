<x-layouts::app :title="isset($seoPage) ? __('Edit SEO Page') : __('Create SEO Page')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    {{ isset($seoPage) ? 'Edit SEO Page' : 'Create SEO Page' }}
                </h1>
            </div>

            <a href="{{ route('seo-pages.index') }}"
               class="rounded-xl border border-neutral-300 px-5 py-3 text-sm font-semibold">
                Back
            </a>

        </div>

        <div class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ isset($seoPage) ? route('seo-pages.update', $seoPage) : route('seo-pages.store') }}"
                  class="space-y-6">

                @csrf

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            Page Key
                        </label>

                        <input type="text"
                               name="page_key"
                               value="{{ old('page_key', $seoPage->page_key ?? '') }}"
                               class="w-full rounded-xl border px-4 py-3">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            Meta Title
                        </label>

                        <input type="text"
                               name="meta_title"
                               value="{{ old('meta_title', $seoPage->meta_title ?? '') }}"
                               class="w-full rounded-xl border px-4 py-3">
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        Meta Description
                    </label>

                    <textarea name="meta_description"
                              rows="4"
                              class="w-full rounded-xl border px-4 py-3">{{ old('meta_description', $seoPage->meta_description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        Meta Keywords
                    </label>

                    <textarea name="meta_keywords"
                              rows="3"
                              class="w-full rounded-xl border px-4 py-3">{{ old('meta_keywords', $seoPage->meta_keywords ?? '') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        Canonical URL
                    </label>

                    <input type="url"
                           name="canonical_url"
                           value="{{ old('canonical_url', $seoPage->canonical_url ?? '') }}"
                           class="w-full rounded-xl border px-4 py-3">
                </div>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            OG Title
                        </label>

                        <input type="text"
                               name="og_title"
                               value="{{ old('og_title', $seoPage->og_title ?? '') }}"
                               class="w-full rounded-xl border px-4 py-3">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            OG Image
                        </label>

                        <input type="file"
                               name="og_image"
                               class="w-full rounded-xl border px-4 py-3">

                        @if(isset($seoPage) && $seoPage->og_image)

                            <img src="{{ asset('storage/' . $seoPage->og_image) }}"
                                 class="mt-4 h-24 rounded-xl border">

                        @endif
                    </div>

                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">
                        OG Description
                    </label>

                    <textarea name="og_description"
                              rows="4"
                              class="w-full rounded-xl border px-4 py-3">{{ old('og_description', $seoPage->og_description ?? '') }}</textarea>
                </div>

                <div class="flex justify-end">

                    <button type="submit"
                            class="rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white dark:bg-white dark:text-black">

                        {{ isset($seoPage) ? 'Update SEO Page' : 'Create SEO Page' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts::app>