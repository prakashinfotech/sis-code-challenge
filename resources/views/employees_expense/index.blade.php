@extends('layouts.app')
@push('styles')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                	@if (Auth::user ()->hasRole ('Admin')) 
                	 Employees Expense 
                	<a class="btn btn-primary" href="{{ route('employees_expense.create') }}">Import File</a>
                	@elseif(Auth::user ()->hasRole ( 'Employee' ))
                	  My Expense
                	<a class="btn btn-primary" href="{{ route('employees-expense-add') }}">Add New Record</a>
                	@endif
                </div>
                <div class="card-body">
	                <div class="flash-message pt-10">
	                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
					          @if(Session::has('alert-' . $msg))
					          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					          @endif
					    @endforeach
	                  </div>
	                <div class="row">
	                	<div class="col-md-4">
    						<div class="form-group">
    							<div class="">
    								{!! Form::select('year', $years, $currentYear, array('id' => 'year','class'=>'form-control','autofocus') ) !!}
    							</div>
    						</div>
    					</div>
	                	<div class="col-md-4">
    						<div class="form-group">
    							<div class="">
    								{!! Form::select('months', $months, $currentMonth, array('id' => 'months','class'=>'form-control','autofocus') ) !!}
    							</div>
    						</div>
    					</div>              	
    					<div class="col-md-4">
    						<div class="form-group">
    							<div class="">
      							{!! Form::select('category_id', $categories, null, array('id' => 'categoryList','class'=>'form-control','autofocus') ) !!}
  							</div>
    						</div>
    					</div>		
    				</div>
    			  <div class="row">
	               	<div class="col-md-12">
	               		<div class="table-responsive">
							<table id="expense_submissions" class="table table-striped table-bordered display nowrap" style="width:100%">
					        <thead>
					            <tr>
					                <th>#</th>
					                <th class="no-sort">Employee Name</th>
					                <th>Expense Date</th>
					                <th class="no-sort">Category</th>
					                <th class="no-sort">Expense Description</th>
					                <th>Pre tax amount</th>
					                <th>tax amount</th>
					                <th class="no-sort">Total</th>
					            </tr>
					        </thead>
					        <tfoot>
		                      <tr>
		                        <th colspan="7" style="text-align:right">Total:</th>
		                        <th></th>
		                      </tr>
		                  </tfoot>
					    </table>
					  </div>
			    </div>
			    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/datatable.js') }}"></script>
<script>
jQuery(document).ready(function ($) {
	   $('#expense_submissions').DataTable({
		   "responsive":true,
			"ajax":{
	        	"url": "<?php echo route('employees_expense_list'); ?>",
	        	"type": "POST",
	            "data": function(d){ 
	                d.year = $("#year").val();
	                d.month = $("#months").val();
	                d.categoryList = $("#categoryList").val();
	                d._token = "{{csrf_token()}}";
				}
	       	},
	        "columnDefs": [{
		    	"targets": 'no-sort',
		        "orderable": false},
		        {visible:false, targets:0}
		     ],
		    "paging": false,
			"bInfo" : false,
			"searching": false,
		    "processing": true,
		    "serverSide": true,
		    "autoWidth": false,
		    "order": [[0, 'asc']],
		    "columns": [
			    {"name": "id"},
			    {"name": "employee_name"},
			    {"name": "expense_date"},
			    {"name": "category"},
		        {"name": "expense_description"},	
			    {"name": "pre_tax_amount"},
			    {"name": "tax_amount"},
			    {"name": "total"}		        
		    ],
		    "footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api(), data;
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	            // Total over all pages
	            total = api
	                .column( 7 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	            // Update footer
	            $( api.column( 7 ).footer() ).html(total.toFixed(2));
	        }
	    });
	    
	   $("#year").change(function(){
	    	$('#expense_submissions').DataTable().ajax.reload();
	  	});
	   $("#months").change(function(){
	    	$('#expense_submissions').DataTable().ajax.reload();
	  });
	  $("#categoryList").change(function(){
	    	$('#expense_submissions').DataTable().ajax.reload();
	  });
} );
</script>
@endpush 
