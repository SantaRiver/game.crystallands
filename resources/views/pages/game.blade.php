@extends('layouts.game')

@section('app')
    <app
        :user="'{{ json_encode($user) }}'">
    </app>
@endsection
