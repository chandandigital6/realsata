<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::latest()->paginate(20);

        return view('advertisement.index', compact('advertisements'));
    }

    public function create()
    {
        return view('advertisement.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => ['nullable', 'string', 'max:255'],
            'position'  => ['nullable', 'string', 'max:255'],
            'content'   => ['nullable', 'string'],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'link'      => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('advertisements', 'public');
        }

        Advertisement::create($data);

        return redirect()->route('advertisements.index')
            ->with('success', 'Advertisement created successfully.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('advertisement.form', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $data = $request->validate([
            'title'     => ['nullable', 'string', 'max:255'],
            'position'  => ['nullable', 'string', 'max:255'],
            'content'   => ['nullable', 'string'],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'link'      => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($advertisement->image) {
                Storage::disk('public')->delete($advertisement->image);
            }

            $data['image'] = $request->file('image')->store('advertisements', 'public');
        }

        $advertisement->update($data);

        return redirect()->route('advertisements.index')
            ->with('success', 'Advertisement updated successfully.');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->image) {
            Storage::disk('public')->delete($advertisement->image);
        }

        $advertisement->delete();

        return redirect()->route('advertisements.index')
            ->with('success', 'Advertisement deleted successfully.');
    }
}