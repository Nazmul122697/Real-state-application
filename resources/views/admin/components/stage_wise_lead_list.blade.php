@extends('admin.layouts.app')
@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
@php
$ses_other_user_id    = Session::get('user.ses_other_user_pk_no');
$ses_other_user_name  = Session::get('user.ses_other_full_name');
$role_id = Session::get('user.ses_role_lookup_pk_no');

$is_ses_hod     = Session::get('user.is_ses_hod');
$is_ses_hot     = Session::get('user.is_ses_hot');
$is_team_leader = Session::get('user.is_team_leader');
$is_super_admin = Session::get('user.is_super_admin');
$status="";
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
	@if($ses_other_user_id =="")
	<h1>Lead List </h1>
	@else
	<h1>
		Lead List :: <span class="text-danger">{{ $ses_other_user_name }}</span>
		| <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard',$ses_other_user_id) }}">Back</a>
		| <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard') }}">Back To My Dashboard</a>
	</h1>
	@endif

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Dashboard</a></li>
		<li class="active">Lead List</li>
	</ol>
</section>
@if(session()->has('err'))
    <div class="alert alert-danger">{{session()->get('err')}}</div>
@endif

@if(session()->has('msg'))
    <div class="alert alert-success">{{session()->get('msg')}}</div>
@endif
<section id="product_details" class="content">
	<div class="row">
		<div class="col-sm-10">
			<div class="box box-info">
                <input type="hidden" name="stage_type" id="stage_type" value="{{$type}}" />
                <input type="hidden" name="role_id" id="role_id" value="{{$role_id}}" />
                <input type="hidden" name="is_super_admin" id="is_super_admin" value="{{$is_super_admin}}" />
				<div class="box-body" id="list-body">
					<table id="stage_wise_lead_list" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th class="text-center">SL</th>
								<th class="text-center">Code</th>
								<th style=" width: 80x;" class="text-center">Date</th>
								<th>Customer</th>
								<th>Mobile</th>
								<th>Project</th>
								<th>Agent</th>
								<th>Stage</th>
								<th>Source</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center" width="80"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-center" width="100px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@if ($ses_other_user_id == '' && ($is_ses_hod != 0 || $is_ses_hot != 0 || $is_team_leader != 0))
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="teamname">Team</label>
                        <select name="teamname" id="teamname" data-action="{{ route('get_team_users') }}"
                            class="form-control" style="width: 100%;" required="required" aria-hidden="true">
                            <option value="0">Select</option>
                            @foreach ($team_arr as $key => $team)
                                <option value="{{ $key }}">{{ $team }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" {{ $is_ses_hod == 1 ? '' : 'disabled' }} id="user_type"
                                    value="hod" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">HOD&nbsp;</span>
                        </label>
                        <select name="team_name" id="team_hod" class="form-control" style="width: 100%;"
                            required="required" aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="hot" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">CSH&nbsp;</span>
                        </label>
                        <select name="team_hot" id="team_hot" class="form-control" style="width: 100%;" required="required"
                            aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="tl" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">HOT&nbsp;</span>
                        </label>
                        <select name="team_tl" id="team_tl" class="form-control" style="width: 100%;"
                            required="required" aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="agent" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">Sales Person&nbsp;</span>
                        </label>
                        <select name="team_agent" id="team_agent" class="form-control" style="width: 100%;"
                            required="required" aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    <div>
                        <span id="switch_dashboard" data-action="{{ route('admin.dashboard') }}"
                            class="btn bg-green btn-xs">View Dashboard</span>
                    </div>
                </div>
            @endif
	</div>
</section>
@endsection

@push('js_custom')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
	$(function () {
        let stage_type = $('#stage_type').val();
        let url = '{{route("stage_wise_lead_list", ":id")}}';;
        url = url.replace(':id',stage_type);
        let role_id = $('#role_id').val();
        let stage_arr = [
	        '1',
	        '2',
	        '3',
	        '4',
	        '5',
	        '6',
	        '7',
	        '8',
		// 9 => 'Junk',
		    '10',
		    '11',
		    '12',
	    ]
        $('#stage_wise_lead_list').DataTable({
            "processing": true,
            "serverSide": true,
            "searchable ": true,
            "ordering": false,
            "ajax":{
                "url": url,
                "dataType": "json",
                "type": "get",
                //"data":{ _token: "{{csrf_token()}}"}
            },
            "columnDefs": [
                    { "visible": false,"targets": 7},
                    {
                            "targets": [-1],
                            "render": function (data,type,full,meta) {
                                 let url = '';
                                // url = url.replace(':id',full[0]);
                                // url = url.replace(':type',stage_type);

                                let lead_view = '{{route("lead_view", ":id")}}';
                                lead_view = lead_view.replace(':id',full[0]);

                                let lead_edit = '{{route("lead.edit", ":id")}}';
                                lead_edit = lead_edit.replace(':id',full[0]);

                                let delete_close_lead = '{{route("delete_closed_lead", ":id")}}';
                                delete_close_lead = delete_close_lead.replace(':id',full[0]);

                                let followup_url='{{route("lead_follow_up_from_dashboard", [":id", ":type"])}}';
                                followup_url = followup_url.replace(':id',full[0]);
                                followup_url = followup_url.replace(':type',stage_type);
                                let role_html = '';
                                if(role_id == 77&&full[8]!=6) {
                                        role_html = `<span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup" data-id="${full[0]}" data-action="${followup_url}"> <i class="fa fa-list"></i></span>`
                                }
                                let actioncontainer="";
                                // if($('#is_super_admin').val()==1){
                                    actioncontainer=`<span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details" data-id="${full[0]}" data-action="${lead_view}"><i class="fa fa-eye"></i></span>
									<span class="btn bg-info btn-xs lead-edit" data-title="Lead Edit" title="Lead Edit" data-id="${full[0]}" data-action="${lead_edit}"><i class="fa fa-edit"></i></span>
                                    <!-- <a  href="${delete_close_lead}" onclick="return confirm('Are you sure you want to delete the lead?')" class="btn bg-info btn-xs"><i class="fa fa-trash"></i></a> -->
                                    ${role_html}
									`;
                                // }else{
                                //     actioncontainer=`<span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details" data-id="${full[0]}" data-action="${lead_view}"><i class="fa fa-eye"></i></span>
                                //     <a  href="${delete_close_lead}" onclick="return confirm('Are you sure you want to delete the lead?')" class="btn bg-info btn-xs"><i class="fa fa-trash"></i></a>
                                //     ${role_html}
								// 	`;
                                // }

                                return actioncontainer;

                            }
                        },
                        {
                            "targets": [8],
                            "render": function (data,type,full,meta) {

                                return stage_type == 6 ? 'Closed' : null;
                            }
                        },
                    ]

        });
		//Flat red color scheme for iCheck
		$('input[type="radio"].flat-red').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});
		$('#datatable').DataTable({
			"order": false,
			bSort: false,
			"pageLength": 30
		});
		$(document).on("change", "#teamname", function(e){
			blockUI();
			var team_id = $(this).val();
			var action = $(this).attr('data-action');
			$.ajax({
				data: { team_id:team_id },
				url: action,
				type: "post",
				beforeSend:function(){
					$("#team_hod").html("");
					$("#team_hot").html("");
					$("#team_tl").html("");
					$("#team_agent").html("");
				},
				success: function (data) {
					data = $.parseJSON(data);
					var hod_list = hot_list = tl_list = agent_list = "";
					$.each(data.hod_arr, function(i, item) {
						hod_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_hod").append(hod_list);

					$.each(data.hot_arr, function(i, item) {
						hot_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_hot").append(hot_list);

					$.each(data.tl_arr, function(i, item) {
						tl_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_tl").append(tl_list);

					$.each(data.agent_arr, function(i, item) {
						agent_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_agent").append(agent_list);
				}

			});
			$.unblockUI();
		});

		$(document).on("click","#switch_dashboard",function(){
			var action = $(this).attr("data-action");
			var selected_user = $('input[name="user_type"]:checked');
			if (!selected_user.val()) {
				alert('You did not select any user.');
			}
			else {
				var selected_user_id = selected_user.parents("label").siblings("select").val();
				window.location.href = action + "/" + selected_user_id;
			}


		});

		$(document).on("click",".next-followup",function (e) {
			var id = $(this).attr("data-id");
			var action = $(this).attr("data-action");
			var title = $(this).attr("data-title");

			$.ajax({
				url: action,
				type: "get",
				beforeSend:function(){
					blockUI();
					$('.common-modal').modal('show');
					$('.common-modal .modal-body').html("Loading...");
					$('.common-modal .modal-title').html(title);
				},
				success: function (data) {
					$.unblockUI();
					$('.common-modal .modal-body').html(data);
					var date = new Date();
					date.setDate(date.getDate());
					$('#txt_followup_date').datepicker({
						startDate: date,
						todayHighlight: true
					});
					$('#txt_followup_date_time').timepicker();
				}

			});
		});

		$(document).on("click",".btnSaveUpdateFollowup",function (e) {
			e.preventDefault();
			var formID = $(this).parents("form").attr("id");
			var formAction = $(this).parents("form").attr("action");
			var formMethod = $(this).parents("form").attr("method");
			var responseAction = $(this).attr("data-response-action");
			var tab_type = $("ul#tab_container li.active a").attr("data-type");
			var validation_check = 0;
			var validation_array = [];

			$('.required').each(function() {
				if($(this).val() == '' || $(this).val() == 0) {
					validation_array.push(1);
					$(this).attr('style', 'border:2px solid #D44F49 !important');
				}
			});

			if(validation_array.length > 0) {
				toastr.error('You must fill up required fields', 'Validation Error');
				return;
			}

			$.ajax({
				data: $('#'+formID).serialize(),
				url: formAction,
				type: formMethod,
				beforeSend:function(){
					blockUI();
				},
				success: function (data) {
					$.unblockUI();
					if(data.type == 'error')
					{
						toastr.error(data.message, data.title);
					}
					else
					{
						toastr.success(data.message, data.title);
						if(responseAction)
						{
							window.location.href = responseAction;
						}
					}

				},
				error: function (data) {
					var errors = jQuery.parseJSON(data.responseText).errors;
					for (messages in errors) {
						var field_name = $("#"+messages).siblings("label").html();
						error_messages =  field_name + ' ' + errors[messages];
						toastr.error(data.message, error_messages);
					}
					$.unblockUI();
				}
			});
		});

	});

</script>
@endpush
