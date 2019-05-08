@extends('layouts.app')
@push('styles')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Monthly Expense Report</div>
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
    								{!! Form::select('year', $years, $currentYear, array('id' => 'year','class'=>$errors->has('year') ? 'form-control is-invalid' :'form-control','autofocus') ) !!}
    								@if ($errors->has('year')) 
    									<span class="invalid-feedback"> <strong>{{ $errors->first('year') }} </strong> </span> 
    							   	@endif
    							</div>
    						</div>
    					</div>
    				</div>
	                <div class="row">
	                	<div class="col-md-12">
        					<table id="monthly_expense" class="table table-striped table-bordered" style="width:100%">
            			        <thead>
            			            <tr>
            			            	<th class="no-sort">Month</th>
            			                <th class="no-sort">Pre Tax Amount</th>
            			                <th class="no-sort">Tax Amount</th>
            			                <th class="no-sort">Total</th>
            			            </tr>
            			        </thead>
            			        <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right">Total:</th>
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
@endsection

@push('scripts') 
<script src="{{ asset('js/datatable.js') }}"></script>
<script>
jQuery(document).ready(function ($) {	
    $('#monthly_expense').DataTable({
		"ajax":{
        	"url": "<?php echo route('monthly-expense-report'); ?>",
        	"type": "POST",
            "data": function(d){ 
                d.year = $("#year").val();
                d._token = "{{csrf_token()}}";
			}
       	},
       	"columnDefs": [
			{"targets": 'no-sort', "orderable": false}
		],       	
        "processing": true,
	    "serverSide": true,
	    "autoWidth": false,
	    "paging": false,
	    "bInfo" : false,
	    "searching": false,
	    "ordering": false,
	    "columns": [
		    {"name": "expense_month"},
		    {"name": "total_pre_tax_amount"},
		    {"name": "total_tax_amount"},
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
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(total);
        }
    });
    $("#year").change(function(){
    	$('#monthly_expense').DataTable().ajax.reload();
  	});
});
</script>
@endpush