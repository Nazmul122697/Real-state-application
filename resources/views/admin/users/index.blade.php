@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Users</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Setting</a></li>
		<li class="active">Users</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header text-right">
					<span type="button" class="btn bg-purple btn-sm pull-right create_modal" data-action="{{ route('user.create') }}" data-title="Create User">
						<i class="fa fa-plus" style="font-size:12px;"></i> Create User
					</span>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive" id="list-body">
					@include('admin.users.user_list')
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>
</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>


@endpush
@push('js_custom')
<script>
	// $(function () {
		//$('.data-table').DataTable();
		// $('.data-table').dataTable( {
		// 	"columnDefs": [
		// 	{ "width": "10px", "targets": 0 }
		// 	]
        //
		// } );
        // $('.data-table').DataTable( {
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ]
        // } );
	// });
    $(document).ready(function() {
        $('.data-table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print'
            ]
        } );
    } );

     function dprc() {
        var datepickerOptions = {
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: true,
        };

        $('.datepicker').datepicker(datepickerOptions);
    }
</script>
@endpush
