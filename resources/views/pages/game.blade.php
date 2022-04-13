@extends('layouts.game')

@section('app')
    <app
        :logged-user="'{{ json_encode($user) }}'">
    </app>
@endsection
