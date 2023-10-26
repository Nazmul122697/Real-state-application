@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Report Setup</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Settings</a></li>
            <li class="active">Report Setup</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <a type="button" class="btn bg-purple btn-sm pull-right " style="margin-left: 20px; "
                            href="{{ route('import_csv_report_setup') }}" data-title="Import CSV">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Import CSV
                        </a>
                    </div>
                    <div class="box-body">
                        <form id="frmTeamTarget" action="{{ route('store_report_target') }}" method="post">
                            <div class="head_action"
                                style="background-color: #ECF0F5;border: 1px solid #ccc; text-align: center; padding: 3px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-4"><label>Team Name</label></div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" id="team_name" name="team_name"
                                                style="width: 100%;" aria-hidden="true">
                                                <option selected="selected" value="0">Select Team</option>

                                                @foreach ($team_arr as $team_id => $team)
                                                    <option value="{{ $team_id }}">{{ $team }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-4"><label for="team_target_date">Target Year</label></div>
                                        <div class="col-md-8">
                                            <select name="team_target_date" id="team_target_date" class="form-control">
                                                <option value="0">Select </option>
                                                @if (!empty($target_year))
                                                    @foreach ($target_year as $data)
                                                        <option value="{{ $data->id }}">{{ $data->fin_year }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br clear="all" />
                            </div>
                            <div class="row" id="team_list"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_custom')
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        $(function() {



            $(document).on("change", "#team_name", function() {
                blockUI();
                var team_id = $(this).val();
                var team_target_date = $("#team_target_date").val();
                $.ajax({
                    data: {
                        team_id: team_id
                    },
                    url: 'load_team_lead_by_team',
                    type: "post",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $("#team_name_td").html(data);
                    }

                });


                $.ajax({
                    data: {
                        team_id: team_id,
                        team_target_date: team_target_date
                    },
                    url: 'load_team_list_by_team_report',
                    type: "post",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $("#team_list").html(data);
                        $('.table').DataTable({
                            "bPaginate": false
                        });
                    }

                });

                $.unblockUI();
            });
            $(document).on("change", "#team_target_date", function() {
                blockUI();
                var team_id = $("#team_name").val();
                var team_target_date = $("#team_target_date").val();
                $.ajax({
                    data: {
                        team_id: team_id,
                    },
                    url: 'load_team_lead_by_team',
                    type: "post",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $("#team_name_td").html(data);
                    }

                });
                $.ajax({
                    data: {
                        team_id: team_id
                    },
                    url: 'load_ch_by_team',
                    type: "post",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $("#ch_name_td").html(data);
                    }

                });

                $.ajax({
                    data: {
                        team_id: team_id,
                        team_target_date: team_target_date
                    },
                    url: 'load_team_list_by_team_report',
                    type: "post",
                    beforeSend: function() {

                    },
                    success: function(data) {
                        $("#team_list").html(data);
                        $('.table').DataTable({
                            "bPaginate": false
                        });
                    }

                });

                $.unblockUI();
            });
        });
    </script>

@endpush
