@extends('errors::minimal')

@section('title', __('errors.Service Unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'errors.Service Unavailable'))
