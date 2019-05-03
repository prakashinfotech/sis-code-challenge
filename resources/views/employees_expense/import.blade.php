@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Import Expense</div>
                <div class="card-body">
	                <div class="flash-message pt-10">
	                    @if (session('status'))
	                        <div class="alert alert-success" role="alert">
	                            {{ session('status') }}
	                        </div>
	                    @endif
	                  </div>
					{!! Form::open(array('route' =>'employees_expense.store','enctype'=>'multipart/form-data','method'=>'POST','files'=>'true')) !!}
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
	    			<div class="row mt-3">
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
