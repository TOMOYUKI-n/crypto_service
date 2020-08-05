@extends('layouts.app')

@section('content')

<div id="app">

    <trend-component 
    :trends="{{ $trends }}" >
    </trend-component>
</div>
@endsection