@extends('errors::minimal')

@section('title', __('errors.Access Denied'))
@section('code', '401')
@section('message', __($exception->getMessage() ?: 'errors.Access Denied'))
