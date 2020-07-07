@extends('layouts.app')

@section('content')

<div id="app">
    <account-component
    :userinfo="{{ $userinfo }}"
    >
    </account-component>
</div>
@endsection