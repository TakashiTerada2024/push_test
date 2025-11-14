@extends('errors::minimal')

@section('title', 'Bad Request')
@section('code', '400')
@section('message', __($exception->getMessage() ?: 'Bad Request'))
