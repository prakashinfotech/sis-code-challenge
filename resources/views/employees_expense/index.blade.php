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
					<table id="expense_submissions" class="table table-striped table-bordered display nowrap" style="width:100%">
			        <thead>
			            <tr>
			                <th>#</th>
			                <th>Expense Date</th>
			                <th class="no-sort">Category</th>
			                <th class="no-sort">Expense Description</th>
			                <th>Pre tax amount</th>
			                <th>tax amount</th>
			            </tr>
			        </thead>
			    </table>
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
    $('#expense_submissions').DataTable( {
        "responsive":true,
        "ajax":{
        	 "url": "<?php echo route('employees_expense.index'); ?>",
        	 "type": "GET",
             "data":{ _token: "{{csrf_token()}}"}
            },
        "columnDefs": [{
	    	"targets": 'no-sort',
	        "orderable": false},
	        {visible:false, targets:0}
	     ],
	    "processing": true,
	    "serverSide": true,
	    "autoWidth": false,
	    "order": [[0, 'asc']],
	    "columns": [
	        {"name": "id"},
	        {"name": "expense_date"},
	        {"name": "category"},
	        {"name": "expense_description",className: "no-sort"},
	        {"name": "pre_tax_amount",className: "text-center"},
	        {"name": "tax_amount", className: "text-center last-column"}
	    ],
    } );
} );
</script>
@endpush 
