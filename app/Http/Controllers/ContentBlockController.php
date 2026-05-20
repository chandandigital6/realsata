<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContentBlockController extends Controller
{
    public function index()
    {
        $contentBlocks = ContentBlock::latest()->paginate(20);

        return view('content_block.index', compact('contentBlocks'));
    }

    public function create()
    {
        return view('content_block.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'       => ['nullable', 'string', 'max:255', 'unique:content_blocks,key'],
            'title'     => ['nullable', 'string', 'max:255'],
            'content'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['key'] = $data['key'] ?: Str::slug($data['title'] ?? 'content-block') . '-' . time();
        $data['is_active'] = $request->boolean('is_active');

        ContentBlock::create($data);

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block created successfully.');
    }

    public function edit(ContentBlock $contentBlock)
    {
        return view('content_block.form', compact('contentBlock'));
    }

    public function update(Request $request, ContentBlock $contentBlock)
    {
        $data = $request->validate([
            'key' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('content_blocks', 'key')->ignore($contentBlock->id),
            ],
            'title'     => ['nullable', 'string', 'max:255'],
            'content'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['key'] = $data['key'] ?: Str::slug($data['title'] ?? 'content-block') . '-' . $contentBlock->id;
        $data['is_active'] = $request->boolean('is_active');

        $contentBlock->update($data);

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block updated successfully.');
    }

    public function destroy(ContentBlock $contentBlock)
    {
        $contentBlock->delete();

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block deleted successfully.');
    }
}