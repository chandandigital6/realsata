@extends('front.layouts.app', ['seo' => $seo ?? null])

@section('content')

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>SATTA CHART</h2>
            </div>
        </div>
    </div>
</section>

<section class="tabel3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <tbody>

                            @forelse($games as $game)

                                <tr>
                                    <td class="forfirtcolor">
                                        <strong>{{ strtoupper($game->name) }}</strong>
                                    </td>

                                    @forelse($game->chartYears as $chartYear)
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, $chartYear->year]) }}"
                                                   style="color:black;">
                                                    {{ $chartYear->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @empty
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, now()->year]) }}"
                                                   style="color:black;">
                                                    {{ now()->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @endforelse
                                </tr>

                            @empty

                                <tr>
                                    <td class="text-center">
                                        No chart found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection