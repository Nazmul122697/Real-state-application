@extends('admin.layouts.app')

@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/morris.js/morris.css') }}">
@endpush

@section('content')

@php
$ses_other_user_id  = Session::get('user.ses_other_user_pk_no');
$ses_other_user_name  = Session::get('user.ses_other_full_name');
$user_type  = Session::get('user.user_type');
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
	@if($ses_other_user_id =="")
	<h1>Dashboard</h1>
	@else
	<h1>
		Dashboard :: <span class="text-danger">{{ $ses_other_user_name }}</span>
		| <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard') }}">Back To My Dashboard</a>
	</h1>
	@endif

	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">LeadAgent-Dashboard</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<!-- Small boxes (Stat box) -->
	<div class="row">

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>Leads</h3>

					<p>{{ $lead_count[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-person"></i>
				</div>
				<a href="{{ route('lead_list', 1) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>K1</h3>

					<p>{{ $k1[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="{{ route('lead_list', 3) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>Priority</h3>

					<p>{{ $priority[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-graph-up-right"></i>
				</div>
				<a href="{{ route('lead_list', 4) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-maroon">
				<div class="inner">
					<h3>Transferred</h3>
					<p>{{ $transferred[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-android-train"></i>
				</div>
				<a href="{{ route('lead_list', 13) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-orange">
				<div class="inner">
					<h3>Accepted</h3>

					<p>{{ $accepted[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-checkmark"></i>
				</div>
				<a href="{{ route('lead_list', 14) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-teal">
				<div class="inner">
					<h3>Sold</h3>

					<p>{{ $sold[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
				<a href="{{ route('lead_list', 7) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-purple">
				<div class="inner">
					<h3>Hold</h3>

					<p>{{ $hold[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-pause"></i>
				</div>
				<a href="{{ route('lead_list', 5) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>Closed</h3>

					<p>{{ $closed[0]->total_lead }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-power"></i>
				</div>
				<a href="{{ route('lead_list', 6) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->


	</div>
	<!-- /.row -->
	<!-- Main row -->
	<div class="row">
		<!-- Left col -->
		<section class="col-lg-7 connectedSortable">
			<!-- Custom tabs (Charts with tabs)-->
			<div class="nav-tabs-custom">
				<!-- Tabs within a box -->
				<ul class="nav nav-tabs pull-right">
					<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: AVT</li>
				</ul>
				<div class="tab-content no-padding">
					<div class="chart tab-pane active" id="avt_list">
						@include('admin.components.avt_user_list')
					</div>
				</div>
			</div>
			<!-- /.nav-tabs-custom -->
			@if($user_type == 2)
			<!-- Custom tabs (Charts with tabs)-->
			<div class="nav-tabs-custom">
				<!-- Tabs within a box -->
				<ul class="nav nav-tabs pull-right">
					<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: APT</li>
				</ul>
				<div class="tab-content no-padding">
					<div class="chart tab-pane active" id="avt_list">
						@include('admin.components.apt_user_list')
					</div>
				</div>
			</div>
			<!-- /.nav-tabs-custom -->
			<!-- Custom tabs (Charts with tabs)-->
			<div class="nav-tabs-custom">
				<!-- Tabs within a box -->
				<ul class="nav nav-tabs pull-right">
					<li class="pull-left header"><i class="fa fa-area-chart"></i> KPI :: ACR</li>
				</ul>
				<div class="tab-content no-padding">
					<div class="chart tab-pane active" id="avt_list">
						@include('admin.components.acr_user_list')
					</div>
				</div>
			</div>
			<!-- /.nav-tabs-custom -->
			@endif

		</section>
		<section class="col-lg-5 connectedSortable">
			<!-- TO DO List -->
			<div class="box box-primary">
				<div class="box-header">
					<i class="ion ion-clipboard"></i>
					<h3 class="box-title">To Do List</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<ul class="todo-list">
						<li>
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">Next Followup</span>
							<small class="label label-info pull-right">{{ $next_followup->next_followup_cnt }}</small>
						</li>
						<li>
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">Missed Followup</span>
							<small class="label label-danger pull-right">{{ $missed_followup->missed_followup_cnt }}</small>
						</li>
						<li>
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">KYC Reminder</span>
							<small class="label label-warning pull-right">0</small>
						</li>
						<li>
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">Birthday wish</span>
							<small class="label label-warning pull-right">0</small>
						</li>
					</ul>
					<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->

				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</section>
		<!-- /.Left col -->
	</div>
	<!-- /.row (main row) -->

</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- Morris.js charts -->
<script src="{{ asset('backend/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/morris.js/morris.min.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('backend/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('backend/dist/js/demo.js') }}"></script>
@endpush

@push('js_custom')
<script>
	$(function () {
		$('#dataTable_1, #dataTable_2, #dataTable_3, #dataTable_4, #dataTable_5, #dataTable_6, #dataTable_7, #dataTable_8').DataTable();
	});
</script>

@endpush
