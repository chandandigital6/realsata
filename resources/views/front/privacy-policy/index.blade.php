@extends('front.layouts.app',[
    'seo' => $seo ?? null
])

@section('content')
    @include('front.privacy-policy.content')
@endsection
