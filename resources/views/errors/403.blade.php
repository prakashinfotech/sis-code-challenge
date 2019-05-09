@extends('errors::minimal')

@section('title', __('errors.Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'errors.Forbidden'))
