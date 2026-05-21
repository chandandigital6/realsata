<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GameResultController extends Controller
{
    public function index()
    {
        $results = GameResult::with('game')
            ->latest('result_date')
            ->paginate(30);

        return view('game_result.index', compact('results'));
    }

    public function create()
    {
        $games = Game::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('game_result.form', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id'     => ['required', 'exists:games,id'],
            'result_date' => [
                'required',
                'date',
                Rule::unique('game_results')->where(fn ($q) =>
                    $q->where('game_id', $request->game_id)
                ),
            ],
            'result'      => ['nullable', 'string', 'max:10'],
            'status'      => ['required', Rule::in(['waiting', 'declared'])],
            'show_minutes' => ['required', 'integer', 'min:0'],
        ]);

        GameResult::create($data);

        return redirect()->route('game-results.index')
            ->with('success', 'Game result created successfully.');
    }

    public function edit(GameResult $gameResult)
    {
        $games = Game::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('game_result.form', compact('gameResult', 'games'));
    }

    public function update(Request $request, GameResult $gameResult)
    {
        $data = $request->validate([
            'game_id'     => ['required', 'exists:games,id'],
            'result_date' => [
                'required',
                'date',
                Rule::unique('game_results')
                    ->where(fn ($q) => $q->where('game_id', $request->game_id))
                    ->ignore($gameResult->id),
            ],
            'result'      => ['nullable', 'string', 'max:10'],
            'status'      => ['required', Rule::in(['waiting', 'declared'])],
            'show_minutes' => ['required', 'integer', 'min:0'],
        ]);

        $gameResult->update($data);

        return redirect()->route('game-results.index')
            ->with('success', 'Game result updated successfully.');
    }

    public function destroy(GameResult $gameResult)
    {
        $gameResult->delete();

        return redirect()->route('game-results.index')
            ->with('success', 'Game result deleted successfully.');
    }
}