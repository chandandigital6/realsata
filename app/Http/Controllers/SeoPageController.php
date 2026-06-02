<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Game;

class SeoPageController extends Controller
{
    public function index()
    {
        $seoPages = SeoPage::with('game')
            ->latest()
            ->paginate(20);

        return view('seo_page.index', compact('seoPages'));
    }

    public function create()
    {
        $games = Game::where('is_active', 1)->orderBy('sort_order')->get();
        return view('seo_page.form', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page_key'          => ['nullable', 'string', 'max:255', 'unique:seo_pages,page_key'],
            'game_id'           => ['nullable', 'exists:games,id'],
            'year'              => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string'],
            'meta_keywords'     => ['nullable', 'string'],
            'canonical_url'     => ['nullable', 'url'],
            'og_title'          => ['nullable', 'string', 'max:255'],
            'og_description'    => ['nullable', 'string'],
            'og_image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'schema_markup' => ['nullable', 'string'],

        ]);

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        SeoPage::create($data);

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page created successfully.');
    }

    public function edit(SeoPage $seoPage)
    {
        $games = Game::where('is_active', 1)->orderBy('sort_order')->get();
        return view('seo_page.form', compact('seoPage', 'games'));
    }

    public function update(Request $request, SeoPage $seoPage)
    {
        $data = $request->validate([
            'page_key' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('seo_pages', 'page_key')->ignore($seoPage->id),
            ],
            'game_id'           => ['nullable', 'exists:games,id'],
            'year'              => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string'],
            'meta_keywords'     => ['nullable', 'string'],
            'canonical_url'     => ['nullable', 'url'],
            'og_title'          => ['nullable', 'string', 'max:255'],
            'og_description'    => ['nullable', 'string'],
            'og_image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'schema_markup' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('og_image')) {

            if ($seoPage->og_image) {
                Storage::disk('public')->delete($seoPage->og_image);
            }

            $data['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        $seoPage->update($data);

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page updated successfully.');
    }

    public function destroy(SeoPage $seoPage)
    {
        if ($seoPage->og_image) {
            Storage::disk('public')->delete($seoPage->og_image);
        }

        $seoPage->delete();

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page deleted successfully.');
    }
}
