<x-layouts::app :title="__('SEO Pages')">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                    SEO Pages
                </h1>

                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Manage all SEO meta pages from here.
                </p>
            </div>

            <a href="{{ route('seo-pages.create') }}"
               class="rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white dark:bg-white dark:text-black">
                + Create SEO Page
            </a>

        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">

                    <thead class="bg-neutral-100 dark:bg-neutral-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
                                #
                            </th>

                           <th class="px-6 py-4 text-left text-xs font-semibold uppercase">
    Page Key
</th>

<th class="px-6 py-4 text-left text-xs font-semibold uppercase">
    Game
</th>

<th class="px-6 py-4 text-left text-xs font-semibold uppercase">
    Year
</th>

<th class="px-6 py-4 text-left text-xs font-semibold uppercase">
    SEO Type
</th>

<th class="px-6 py-4 text-left text-xs font-semibold uppercase">
    Meta Title
</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">

                        @forelse($seoPages as $seoPage)

                            <tr>

                                <td class="px-6 py-4">
                                    {{ $seoPage->id }}
                                </td>

                                <td class="px-6 py-4 font-semibold">
    {{ $seoPage->page_key ?: 'No Page Key' }}
</td>

<td class="px-6 py-4">
    {{ $seoPage->game->name ?? 'All Games / Default' }}
</td>

<td class="px-6 py-4">
    {{ $seoPage->year ?? 'All Years' }}
</td>

<td class="px-6 py-4">
    @if($seoPage->game_id && $seoPage->year)
        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
            Game + Year SEO
        </span>
    @elseif($seoPage->game_id)
        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
            Game SEO
        </span>
    @elseif($seoPage->page_key)
        <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
            Page Key SEO
        </span>
    @else
        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700">
            Default SEO
        </span>
    @endif
</td>

<td class="px-6 py-4">
    {{ $seoPage->meta_title ?: '-' }}
</td>

                                <td class="px-6 py-4 text-right">

                                    <div class="flex items-center justify-end gap-3">

                                        <a href="{{ route('seo-pages.edit', $seoPage) }}"
                                           class="rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
                                            Edit
                                        </a>

                                        <a href="{{ route('seo-pages.delete', $seoPage) }}"
                                           onclick="return confirm('Delete this SEO page?')"
                                           class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600">
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-20 text-center">

                                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">
                                        No SEO Pages Found
                                    </h3>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div>
            {{ $seoPages->links() }}
        </div>

    </div>

</x-layouts::app>