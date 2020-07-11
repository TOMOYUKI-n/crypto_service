@extends('layouts.app')

@section('content')

<div id="app">
    <account-component
    :accountdata="{{ $accountdata }}"
    >
    </account-component>
</div>
@endsection