@extends('layouts.app')
@push('styles')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5>Uploaded Expense Data</h5></div>
                <div class="card-body">
	                <div class="flash-message pt-10">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					          @if(Session::has('alert-' . $msg))
					          <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					          @endif
					    @endforeach
	                </div>
	                <div class="row">
	                	<div class="col-md-12">
	                		<div class="table-responsive">
            					<table id="monthly_expense" class="table" style="width:100%">
                			        <thead>
                			            <tr>
                			            	<th class="no-sort">Year</th>
                			            	<th class="no-sort">Month</th>
                			                <th class="no-sort">Pre Tax Amount</th>
                			                <th class="no-sort">Tax Amount</th>
                			                <th class="no-sort">Total</th>
                			            </tr>
                			        </thead>
                			        <tbody>
                			        	<?php 
                			        	if(count($uploadedData) > 0){
                			        	    foreach ($uploadedData as $valData){?>
                			        	        <tr>
                			        	        	<td><?php echo $valData['expense_year']; ?></td>
                			        	        	<td><?php echo $valData['expense_month']; ?></td>
                			        	        	<td><?php echo $valData['total_pre_tax_amount']; ?></td>
                			        	        	<td><?php echo $valData['total_tax_amount']; ?></td>
                			        	        	<td><?php echo $valData['total']; ?></td>
                			        	        </tr><?php
                			        	    }
                			        	}
                			        	?>            			        	
                			        </tbody>
                			        <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
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
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
jQuery(document).ready(function ($) {	
    $('#monthly_expense').DataTable({
		"paging": false,
	    "bInfo" : false,
	    "searching": false,
	    "ordering": false,
	    "autoWidth": false,
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
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(total.toFixed(2));
        }
    });
});
</script>
@endpush