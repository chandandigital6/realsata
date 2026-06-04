<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class GameResultController extends Controller
{



public function todayUpdateSaveNew(Request $request)
{
    $data = $request->validate([
        'result_date' => ['required', 'date'],
        'results' => ['array'],
        'results.*.game_id' => ['required', 'exists:games,id'],
        'results.*.result' => ['nullable', 'string', 'max:10'],
        'results.*.status' => ['nullable', Rule::in(['waiting', 'declared'])],
        'results.*.show_minutes' => ['nullable', 'integer', 'min:0'],
    ]);

    $assignedGameIds = auth()->user()
        ->games()
        ->pluck('games.id')
        ->toArray();

    DB::transaction(function () use ($data, $assignedGameIds) {
        foreach ($data['results'] ?? [] as $row) {

            if (! in_array((int) $row['game_id'], $assignedGameIds, true)) {
                abort(403, 'You are not allowed to update this game result.');
            }

            $result = trim($row['result'] ?? '');
            $status = $row['status'] ?? 'waiting';

            if ($result !== '') {
                $status = 'declared';
            }

            GameResult::updateOrCreate(
                [
                    'game_id' => $row['game_id'],
                    'result_date' => Carbon::parse($data['result_date'])->format('Y-m-d'),
                ],
                [
                    'result' => $result,
                    'status' => $status,
                    'show_minutes' => $row['show_minutes'] ?? 15,
                ]
            );
        }
    });

    return back()->with('success', 'Today results updated successfully.');
}


public function todayUpdate(Request $request)
{
    $date = $request->date ?? now('Asia/Kolkata')->format('Y-m-d');

    $games = Game::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $existingResults = GameResult::whereDate('result_date', $date)
        ->get()
        ->keyBy('game_id');

    return view('game_result.today_update', compact('games', 'existingResults', 'date'));
}

public function todayUpdateSave(Request $request)
{
    $data = $request->validate([
        'result_date' => ['required', 'date'],
        'results' => ['array'],
        'results.*.game_id' => ['required', 'exists:games,id'],
        'results.*.result' => ['nullable', 'string', 'max:10'],
        'results.*.status' => ['nullable', Rule::in(['waiting', 'declared'])],
        'results.*.show_minutes' => ['nullable', 'integer', 'min:0'],
    ]);

    DB::transaction(function () use ($data) {
        foreach ($data['results'] ?? [] as $row) {

            $result = trim($row['result'] ?? '');
            $status = $row['status'] ?? 'waiting';

            if ($result !== '') {
                $status = 'declared';
            }

            GameResult::updateOrCreate(
                [
                    'game_id' => $row['game_id'],
                    'result_date' => Carbon::parse($data['result_date'])->format('Y-m-d'),
                ],
                [
                    'result' => $result,
                    'status' => $status,
                    'show_minutes' => $row['show_minutes'] ?? 15,
                ]
            );
        }
    });

    return back()->with('success', 'Today results updated successfully.');
}





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