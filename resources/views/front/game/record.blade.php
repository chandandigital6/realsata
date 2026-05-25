@extends('front.layouts.app',[
    'seo' => $seo,
])

@section('content')

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>{{ strtoupper($game->name) }} RECORD CHART {{ $year }}</h2>
            </div>
        </div>
    </div>
</section>

<section class="newtable">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="forblack">
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Day</th>
                                <th class="text-center">Result</th>
                            </tr>
                        </thead>

                        <tbody class="colorchange">
                            @forelse($results as $result)
                                <tr>
                                    <td class="text-center forfirtcolor">
                                        {{ $result->result_date->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center">
                                        {{ $result->result_date->format('l') }}
                                    </td>

                                    <td class="text-center">
                                        @if($result->status === 'declared' && $result->result)
                                            {{ $result->result }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        No record found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-center" style="margin:15px 0;">
                    <a href="{{ route('chart') }}" class="btn btn-primary">
                        Back To Chart
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection