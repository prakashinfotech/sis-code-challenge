@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5>Add New Expense</h5></div>
                <div class="card-body">
	                <div class="flash-message pt-10">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					          @if(Session::has('alert-' . $msg))
					          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					          @endif
					    @endforeach
	                </div>
					{!! Form::open(array('route' =>'employees-expense-addstore','enctype'=>'multipart/form-data','method'=>'POST','files'=>'true')) !!}
						<div class="row">
							<div class="col-md-4">
        						<div class="form-group">
        							<label for="category_id" class=" col-form-label text-md-left">Category<em>*</em></label>
        							<div class="">
        								{!! Form::select('category_id', $categories, null, array('class'=>$errors->has('category_id') ? 'form-control is-invalid' :'form-control','autofocus') ) !!}
        								@if ($errors->has('category_id')) 
        									<span class="invalid-feedback"> <strong>{{ $errors->first('category_id') }} </strong> </span> 
        							   	@endif
        							</div>
        						</div>
        					</div>
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="expense_date" class=" col-form-label text-md-left">Expense Date<em>*</em></label>
        							<div class="input-group">  
                                      	{!! Form::text('expense_date', null, array('class'=>$errors->has('expense_date') ? 'form-control is-invalid datetimepicker' :'form-control datetimepicker','autofocus')) !!}
                                      	@if ($errors->has('expense_date'))
            								<span class="invalid-feedback">
            									<strong>{{ $errors->first('expense_date') }} </strong>
            								</span> 
            						   	@endif
        							</div>
        						</div>
        					</div>
        				</div>	
        				<div class="row">
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="pre_tax_amount" class=" col-form-label text-md-left">Pre Tax Amount<em>*</em></label>
        							<div class="">
        								{!! Form::text('pre_tax_amount', null, array('class'=>$errors->has('pre_tax_amount') ? 'form-control is-invalid' :'form-control','rows'=>5,'cols'=>10, 'autofocus')) !!} 
        								@if($errors->has('pre_tax_amount')) 
        									<span class="invalid-feedback"> <strong>{{$errors->first('pre_tax_amount') }} </strong> </span> 
    									@endif
        							</div>
        						</div>
        					</div>
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="tax_amount" class=" col-form-label text-md-left">Tax Amount<em>*</em></label>
        							<div class="">
        								{!! Form::text('tax_amount', null, array('class'=>$errors->has('tax_amount') ? 'form-control is-invalid' :'form-control','rows'=>5,'cols'=>10, 'autofocus')) !!} 
        								@if($errors->has('tax_amount')) 
        									<span class="invalid-feedback"> <strong>{{$errors->first('tax_amount') }} </strong> </span> 
    									@endif
        							</div>
        						</div>
        					</div>        					
    						<div class="col-md-12">
        						<div class="form-group">
        							<label for="expense_description" class=" col-form-label text-md-left">Expense Description<em>*</em></label>
        							<div class="">
        								{!! Form::textarea('expense_description', null, array('class'=>$errors->has('expense_description') ? 'form-control is-invalid' :'form-control','rows'=>5,'cols'=>10, 'autofocus')) !!} 
        								@if($errors->has('expense_description')) 
        									<span class="invalid-feedback"> <strong>{{$errors->first('expense_description') }} </strong> </span> 
    									@endif
        							</div>
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

@push('scripts') 
<script type="text/javascript">
$(document).ready(function(){
	 $('.datetimepicker').datetimepicker({
         format: 'DD-MM-YYYY'
     });
});
</script>
@endpush 
