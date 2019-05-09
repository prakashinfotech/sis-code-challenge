@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5>Dashboard</h5></div>
                <div class="card-body">
                    <div class="flash-message pt-10">
	                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
					          @if(Session::has('alert-' . $msg))
					          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					          @endif
					    @endforeach
	                  </div>
	                  
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
