@extends('front.layouts.app')

@section('content')

<section class="circlebox">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="liveresult">
                    <div class="datetime">
                        <div id="clockbox"></div>
                    </div>

                    <p class="hintext">हा भाई यही आती हे सबसे पहले खबर रूको और देखो</p>

                    <div class="sattaname">
                        <p>DELHI BAZAR</p>
                    </div>
                    <div class="sattaresult">
                        <font><span>25</span></font>
                    </div>

                    <div class="sattaname">
                        <p>SHRI GANESH</p>
                    </div>
                    <div class="sattaresult">
                        <font><span>08</span></font>
                    </div>

                    <div class="sattaname">
                        <p>FARIDABAD</p>
                    </div>
                    <div class="sattaresult">
                        <font>
                            <span>
                                <p>
                                    <strong class="waitimg">
                                        <img class="lazy"
                                             src="/m/d.gif"
                                             alt="waiting"
                                             width="40"
                                             height="40">
                                    </strong>
                                </p>
                            </span>
                        </font>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="h1">MAY RESULT CHART 2026</p>
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

                        <thead>
                            <tr>
                                <th class="table_chart_section_01 forfirtcolor text-center">
                                    <strong class="fon">DATE</strong>
                                </th>
                                <th class="table_chart_section_01 forfirtcolor text-center">
                                    <strong class="fon">DELHI BAZAR</strong>
                                </th>
                                <th class="table_chart_section_01 forfirtcolor text-center">
                                    <strong class="fon">SHRI GANESH</strong>
                                </th>
                                <th class="table_chart_section_01 forfirtcolor text-center">
                                    <strong class="fon">FARIDABAD</strong>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="colorchange">
                            <tr>
                                <td class="text-center forfirtcolor">01</td>
                                <td class="text-center">25</td>
                                <td class="text-center">08</td>
                                <td class="text-center">-</td>
                            </tr>

                            <tr>
                                <td class="text-center forfirtcolor">02</td>
                                <td class="text-center">45</td>
                                <td class="text-center">12</td>
                                <td class="text-center">34</td>
                            </tr>

                            <tr>
                                <td class="text-center forfirtcolor">03</td>
                                <td class="text-center">60</td>
                                <td class="text-center">22</td>
                                <td class="text-center">18</td>
                            </tr>

                            <tr>
                                <td class="text-center forfirtcolor">04</td>
                                <td class="text-center">11</td>
                                <td class="text-center">40</td>
                                <td class="text-center">65</td>
                            </tr>

                            <tr>
                                <td class="text-center forfirtcolor">05</td>
                                <td class="text-center">90</td>
                                <td class="text-center">31</td>
                                <td class="text-center">27</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .h1 {
        font-size: 22px;
    }
</style>

@endsection