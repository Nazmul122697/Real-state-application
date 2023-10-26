@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Target Setup</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Settings</a></li>
            <li class="active">Target Setup</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <a type="button" class="btn bg-purple btn-sm pull-right " style="margin-left: 20px; "
                            href="{{ route('import_csv_target_setup') }}" data-title="Import CSV">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Import CSV
                        </a>
                    </div>
                    <div class="box-body">
                        <form id="frmTeamTarget" action="{{ route('store_target_sales_a') }}" method="post">
                            <div class="head_action"
                                style="background-color: #ECF0F5;border: 1px solid #ccc; text-align: center; padding: 3px;">
                                <div class="col-md-3">
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-4"><label>Target Year</label></div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control number_only" name="target_year"
                                                value="{{ date('Y') }}">
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-4"><label for="team_target_month">Target Month</label></div>
                                        <div class="col-md-8">
                                            <select name="team_target_month" id="team_target_month" class="form-control">
                                                <option value="0">Select One</option>
                                                <option <?php if (date('m') == '1') {
    echo 'selected';
} ?> value="1">January</option>
                                                <option <?php if (date('m') == '2') {
    echo 'selected';
} ?> value="2">February</option>
                                                <option <?php if (date('m') == '3') {
    echo 'selected';
} ?> value="3">March</option>
                                                <option <?php if (date('m') == '4') {
    echo 'selected';
} ?> value="4">April</option>
                                                <option <?php if (date('m') == '5') {
    echo 'selected';
} ?> value="5">May</option>
                                                <option <?php if (date('m') == '6') {
    echo 'selected';
} ?> value="6">June</option>
                                                <option <?php if (date('m') == '7') {
    echo 'selected';
} ?> value="7">July</option>
                                                <option <?php if (date('m') == '8') {
    echo 'selected';
} ?> value="8">Augest</option>
                                                <option <?php if (date('m') == '9') {
    echo 'selected';
} ?> value="9">September</option>
                                                <option <?php if (date('m') == '10') {
    echo 'selected';
} ?> value="10">October</option>
                                                <option <?php if (date('m') == '11') {
    echo 'selected';
} ?> value="11">November</option>
                                                <option <?php if (date('m') == '12') {
    echo 'selected';
} ?> value="12">December</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-4"><label>Finc. Year</label></div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" id="finc_year" name="finc_year"
                                                style="width: 100%;" aria-hidden="true">
                                                <option selected="selected" value="0">Select Year</option>
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
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        $(function() {

            $('.select2').select2();

            $(document).on("change", "#team_name", function() {
                blockUI();
                var team_id = $(this).val();
                var team_target_date = $("#team_target_date").val();
                var formdata = $("#frmTeamTarget").serialize();

                $.ajax({
                    data: formdata,
                    url: 'load_team_member_by_team_for_sales_a',
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
            $(document).on("change", "#finc_year", function() {
                blockUI();
                var team_id = $(this).val();
                var team_target_date = $("#team_target_date").val();
                var formdata = $("#frmTeamTarget").serialize();

                $.ajax({
                    data: formdata,
                    url: 'load_team_member_by_team_for_sales_a',
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
