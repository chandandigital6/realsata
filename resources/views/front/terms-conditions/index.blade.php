@extends('front.layouts.app',[
    'seo' => $seo ?? null
])

@section('content')
    @include('front.terms-conditions.content')
@endsection
