@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5>Import Expense</h5></div>
                <div class="card-body">
	                <div class="flash-message pt-10">
	                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
					          @if(Session::has('alert-' . $msg))
					          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					          @endif
					    @endforeach
	                  </div>
					{!! Form::open(array('route' =>'employees_expense.store','enctype'=>'multipart/form-data','method'=>'POST','files'=>'true')) !!}
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="image" class=" col-form-label text-md-left">Import File<em>*</em></label>
								<div class="">
	    							{!! Form::file('import_file', array('class'=>$errors->has('import_file') ? 'form-control is-invalid' : 'form-control')) !!}
	    							@if ($errors->has('import_file')) 
	    								<span class="invalid-feedback">
	    									<strong>{{ $errors->first('import_file') }} </strong>
	    								</span> 
	    						   	@endif
								</div>
							</div>
						</div>
    	    			<div class="col-md-12">
    	    				<button type="submit" class="btn btn-primary btn-save" autofocus="autofocus">Save</button>
    	    			</div>    	    			
    	    		</div>	
					{!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
