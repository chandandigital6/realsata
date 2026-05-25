@extends('front.layouts.app')

@section('content')
    <!-- =====GAME PRIMARY START=====-->
    <section class="circlebox">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="liveresult">
                        <div class="datetime">
                            <div id="clockbox"></div>
                        </div>
                        <p class="hintext">हा भाई यही आती हे सबसे पहले खबर रूको और देखो </p>

                        <div class="sattaname">
                            @if(!empty($services))
                                <p>{{$services['game_name']}}</p>
                            @else
                                <p>NA</p>
                            @endif
                        </div>
                        <div class="sattaresult"><font><span>@if(!empty($services))
                        @if(is_numeric($services['result']) && $services['result'] <= 9)
                                        {{$services['result']}}
                                        @else
                                          {{$services['result']}}
                                          @endif
                                    @else
                                        <p><strong class="waitimg">
                                                <img class="lazy" src="{{asset('tamplate/admin/upimages/d.gif')}}" alt="waiting" style="display: inline-block;" width="40px" height="40px">
                                            </strong>
                                        </p>
                                    @endif
                                </span></font>
                        </div>
                        <div class="sattaname">
                            @if(!empty($nextservices))
                                <p>  {{$nextservices->title}}</p>
                            @else
                                <p>NA</p>
                            @endif
                        </div>
                        <div class="sattaresult"><font><span>
                                        <p><strong class="waitimg">
                                                <img class="lazy" src="{{asset('tamplate/admin/upimages/d.gif')}}" alt="waiting" style="display: inline-block;" width="40px" height="40px">
                                            </strong>
                                        </p>
                                </span></font>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =====GAME PRIMARY ENDS=====-->


    <section class="octoberresultchart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                        <h2 id="date"></h2>
                </div>
            </div>
        </div>
    </section>
    <section class="newtable">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 nopadding">
                    <div class="table-responsive">
                        <table class="table table-bordered ">
                            @foreach($data as $key=>$result)
                                @if($key === 0)
                                    <thead class=" p-0">
                                    <tr >
                                        @foreach($result as $col)
                                            <th class="table_chart_section_01 forfirtcolor text-center">
                                                <strong class="fon">{{$col}}</strong>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                @else
                                    <tbody class="colorchange">
                                    <tr >
                                    @foreach($result as $col)
                                            @if($loop->first)
                                                <td class=" text-center forfirtcolor">
                                                    @if(!empty($col))
                                                             @if(is_numeric($col) && $col <= 9)
                                        {{$col}}
                                        @else
                                          {{$col}}
                                          @endif
                                                    
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td class=" text-center">
                                                    @if(!empty($col))
                                                                @if(is_numeric($col) && $col <= 9)
                                        {{$col}}
                                        @else
                                          {{$col}}
                                          @endif
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                            @endif
                                    @endforeach
                                    </tr>
                                    </tbody>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="octoberresultchart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="h1">{{ now()->subMonth(1)->format(ucwords('F')) }} Result Chart {{ now()->subMonth(1)->year }}</p>
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
                            @foreach($predata as $key=>$results)
                                @if($key === 0)
                                    <thead class=" p-0">
                                    <tr>
                                        @foreach($results as $col)
                                        
                                            <th class="table_chart_section_01 forfirtcolor text-center">
                                                <strong class="fon">{{$col}}</strong>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                @else
                                    <tbody class="colorchange">
                                    @foreach($results as $col)
                                        @if($loop->first)
                                            <td class=" text-center forfirtcolor">
                                                @if(!empty($col))
                                                   @if(is_numeric($col) && $col <= 9)
                                        {{$col}}
                                        @else
                                          {{$col}}
                                          @endif
                                                
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        @else
                                            <td class=" text-center">
                                                @if(!empty($col))
                                                    {{$col}}
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        @endif
{{--                                        <td class="text-center" >{{$col ?? "-"}}</td>--}}
                                    @endforeach
                                    </tbody>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="octoberresultchart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="h1">{{ now()->subMonth(2)->format('F') }} Result Chart {{ now()->subMonth(2)->year }}</p>
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
                            @foreach($presdata as $key=>$pre)
                                @if($key === 0)
                                    <thead class=" p-0">
                                    <tr >
                                        @foreach($pre as $col)
                                            <th class="table_chart_section_01 forfirtcolor text-center">
                                                <strong class="fon">{{$col}}</strong>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                @else
                                    <tbody class="colorchange">
                                    <tr >
                                    @foreach($pre as $col)
                                            @if($loop->first)
                                                <td class=" text-center forfirtcolor">
                                                    @if(!empty($col))
                                          @if(is_numeric($col) && $col <= 9)
                                        {{$col}}
                                        @else
                                          {{$col}}
                                          @endif
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td class=" text-center">
                                                    @if(!empty($col))
                                        @if(is_numeric($col) && $col <= 9)
                                        {{$col}}
                                        @else
                                          {{$col}}
                                          @endif
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                            @endif
{{--                                        <td class="text-center">{{$col ?? "-"}}</td>--}}
                                    @endforeach
                                    </tr>
                                    </tbody>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .h1{
            font-size:22px;
            
        }
    </style>
@endsection
@section("custom-script")

    <script type="text/javascript">
        function MYDate(){
            var mydate=new Date();
            var day=mydate.getDay();
            var month=mydate.getMonth();
            var year=mydate.getYear();
            var d=mydate.getDate();
            if(year<1000)
                year+=1900;
            if(d<10)
                d="0"+d;
            var darr=new Array("sunday","sunday","sunday","sunday","sunday","sunday","sunday",);
            var marr=new Array("JAMUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVERMBER", "DECEMBER");
            document.getElementById('date').innerText = marr[month]+" "+"RESULT CHART "+year;
        }
        MYDate();
    </script>
{{--    <script type="text/javascript">--}}
{{--        function MYDate(){--}}
{{--            var mydate=new Date();--}}
{{--            var day=mydate.getDay();--}}
{{--            var month=mydate.getMonth()-1;--}}
{{--            var year=mydate.getYear();--}}
{{--            var d=mydate.getDate();--}}
{{--            if(year<1000)--}}
{{--                year+=1900;--}}
{{--            if(d<10)--}}
{{--                d="0"+d;--}}
{{--            var darr=new Array("sunday","sunday","sunday","sunday","sunday","sunday","sunday",);--}}
{{--            var marr=new Array("JAMUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVERMBER", "DECEMBER");--}}
{{--            document.getElementById('predate').innerText = marr[month]+" "+"RESULT CHART "+year;--}}
{{--        }--}}
{{--        MYDate();--}}
{{--    </script>--}}
{{--    <script type="text/javascript">--}}
{{--        function MYDate(){--}}
{{--            var mydate=new Date();--}}
{{--            var day=mydate.getDay();--}}
{{--            var month=mydate.getMonth()-2;--}}
{{--            var year=mydate.getYear()-1;--}}
{{--            var d=mydate.getDate();--}}
{{--            if(year<1000)--}}
{{--                year+=1900;--}}
{{--            if(d<10)--}}
{{--                d="0"+d;--}}
{{--            var darr=new Array("sunday","sunday","sunday","sunday","sunday","sunday","sunday",);--}}
{{--            var marr=new Array("JAMUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVERMBER", "DECEMBER");--}}
{{--            document.getElementById('lastprer').innerText = marr[month]+" "+"RESULT CHART "+year;--}}
{{--        }--}}
{{--        MYDate();--}}
{{--    </script>--}}
@endsection
