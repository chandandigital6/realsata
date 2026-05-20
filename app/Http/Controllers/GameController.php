<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('latestResult')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(20);

        return view('game.index', compact('games'));
    }

    public function create()
    {
        return view('game.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:games,slug'],
            'result_time' => ['nullable', 'date_format:H:i'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Game::create($data);

        return redirect()->route('games.index')->with('success', 'Game created successfully.');
    }

    public function edit(Game $game)
    {
        return view('game.form', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('games', 'slug')->ignore($game->id),
            ],
            'result_time' => ['nullable', 'date_format:H:i'],
            'is_active'   => ['nullable', 'boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $game->update($data);

        return redirect()->route('games.index')->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('games.index')->with('success', 'Game deleted successfully.');
    }
}
