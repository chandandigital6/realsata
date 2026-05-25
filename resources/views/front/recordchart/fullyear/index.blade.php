@extends('front.layouts.app')

@section('content')
    <section class="forfirtcolor">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 >{{$category->title}} Satta  - {{$year}} Chart & Results Record</h1>
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
                            <tr class="forfirtcolor sigleyear">
                                <th>Date</th>
                                <th>JANUARY</th>
                                <th>FEBRUARY</th>
                                <th>MARCH</th>
                                <th>APRIL</th>
                                <th>MAY</th>
                                <th>JUNE</th>
                                <th>JULY</th>
                                <th>AUGUST</th>
                                <th>SEPTEMBER</th>
                                <th>OCTOBER</th>
                                <th>NOVEMBER</th>
                                <th>DECEMBER</th>
                            </tr>
                            @for( $i=1; $i<=31; $i++)
                                <tr>
                                    <td class="text-center forfirtcolor">{{$i}}</td>
                                    @foreach($results as $result)
                                        <td class="text-center">
                                            @if(isset($result[$i]) && is_numeric($result[$i]) && $result[$i] <= 9 )
    {{ str_pad($result[$i], 2, '0', STR_PAD_LEFT) }}
@else
    {{isset($result[$i]) ? $result[$i] :" --"}}
@endif
                                            <!--{{isset($result[$i]) ? $result[$i] :" --"}}-->
                                            </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
      @if(isset($meta->faq) && $meta->faq !== '')
     <div class="article" style="padding:10px">
        
         {!! $meta->faq !!}
         </div>
         @endif
@endsection

