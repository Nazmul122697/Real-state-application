@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Team Users</h1>
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
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<table id="team-user-table" width="50%" class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th style="width: 50px;">SL</th>
								<th>User Group</th>
								<th>Total User</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody>
						@if(!empty($team_arr))
							@foreach($team_arr as $key=>$team)
							<tr>
								<td style="width: 50px;">{{ $loop->iteration }}</td>
								<td>{{ $team }}</td>
								<td align="center">{{ (isset($team_user_count[$key]))?$team_user_count[$key]:0 }}</td>
								<td class="text-center">
									<span class="btn bg-info btn-xs update_modal" data-title="Team User Setup" data-id="{{ $key }}" data-action="{{ route('team.edit', $key) }}"><i class="fa fa-plus"></i></span>
								</td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
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
@endpush
@push('js_custom')
<script>
	$(function () {
		//$('.data-table').DataTable();
		$('.data-table').dataTable( {
			"columnDefs": [
			{ "width": "10px", "targets": 0 }
			]
		} );

		$(document).on("change","#user_name",function(){
			var user_id = $("#user_name option:selected").val();
			var user_name = $("#user_name option:selected").text();
			var check_if_row_found = $("#user-table tbody tr").length;
			if(check_if_row_found>0)
			{
				$("#user-table tbody").append("<tr><td>"+user_name+"<input type='hidden' name='txtUserID[]' value="+user_id+" ><input type='hidden' name='teammem_id[]' value='' ></td><td align='center'><input type='checkbox' name='chkIsHod"+user_id+"[]' /></td><td align='center'><input type='checkbox' name='chkIsHot"+user_id+"[]' /></td><td align='center'><input type='checkbox' name='chkIsTL"+user_id+"[]' /></td><td class='text-center'><span class='btn bg-danger btn-xs update_modal' data-id='"+user_id+"' ><i class='fa fa-close'></i></span></td></tr>");
			}
			else
			{
				$("#user-table tbody").html("<tr><td>"+user_name+"<input type='hidden' name='txtUserID[]' value="+user_id+" ><input type='hidden' name='teammem_id[]' value='' ></td><td align='center'><input type='checkbox' name='chkIsHod"+user_id+"[]' /></td><td align='center'><input type='checkbox' name='chkIsHot"+user_id+"[]' /></td><td align='center'><input type='checkbox' name='chkIsTL"+user_id+"[]' /></td><td class='text-center'><span class='btn bg-danger btn-xs update_modal' data-id='"+user_id+"' ><i class='fa fa-close'></i></span></td></tr>");
			}
		});
	});
</script>
@endpush
