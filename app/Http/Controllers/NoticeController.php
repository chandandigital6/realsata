<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->paginate(20);

        return view('notice.index', compact('notices'));
    }

    public function create()
    {
        return view('notice.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => ['nullable', 'string', 'max:255'],
            'message'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Notice::create($data);

        return redirect()->route('notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function edit(Notice $notice)
    {
        return view('notice.form', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $request->validate([
            'title'     => ['nullable', 'string', 'max:255'],
            'message'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $notice->update($data);

        return redirect()->route('notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}