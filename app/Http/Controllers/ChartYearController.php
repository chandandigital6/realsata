<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\ChartYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartYearController extends Controller
{
    public function index()
    {
        $chartYears = ChartYear::with('game')
            ->latest()
            ->paginate(20);

        return view('chart_year.index', compact('chartYears'));
    }

    public function create()
    {
        $games = Game::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('chart_year.form', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'year' => [
                'required',
                'digits:4',
                Rule::unique('chart_years')->where(fn ($q) =>
                    $q->where('game_id', $request->game_id)
                ),
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        ChartYear::create($data);

        return redirect()->route('chart-years.index')
            ->with('success', 'Chart year created successfully.');
    }

    public function edit(ChartYear $chartYear)
    {
        $games = Game::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('chart_year.form', compact('chartYear', 'games'));
    }

    public function update(Request $request, ChartYear $chartYear)
    {
        $data = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'year' => [
                'required',
                'digits:4',
                Rule::unique('chart_years')
                    ->where(fn ($q) => $q->where('game_id', $request->game_id))
                    ->ignore($chartYear->id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $chartYear->update($data);

        return redirect()->route('chart-years.index')
            ->with('success', 'Chart year updated successfully.');
    }

    public function destroy(ChartYear $chartYear)
    {
        $chartYear->delete();

        return redirect()->route('chart-years.index')
            ->with('success', 'Chart year deleted successfully.');
    }
}