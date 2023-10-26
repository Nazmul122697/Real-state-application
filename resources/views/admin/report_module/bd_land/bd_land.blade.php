@extends('admin.layouts.app')

@push('css_lib')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
        .content_text {
            padding: 15px;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        .heading {
            text-align: center;
        }

    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>BD Land</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Report Module</a></li>
            <li class="active">BD Land</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="search_details" class="content_text" style="padding-bottom: 0px; ">
        <div class="row">
            <div class="col-xs-12">
                <form action="" id="frmSearch" method="post">
                    {{ csrf_field() }}
                    <div class="box box-success mb-0">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search Panel</h3>
                        </div>

                        {{-- Search Engin --}}
                        <div class="box-body">
                            <div class="form-row">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cluster_head">Project Category</label>
                                        <select class="form-control select2" style="width: 100%;" aria-hidden="true"
                                            id="project_category" name="project_category">
                                            <option value="">Select</option>
                                            @if (!empty($project_cateory))
                                                @foreach ($project_cateory as $value)
                                                    <option value="{{ $value->lookup_pk_no }}">{{ $value->lookup_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>



                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cluster_head">Finc. Year </label>
                                        <select name="team_target_year" id="target_year" class="form-control select2">
                                            <option value="0">Select </option>
                                            @if (!empty($target_year))
                                                @foreach ($target_year as $data)
                                                    <option value="{{ $data->closing_year }}">{{ $data->closing_year }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Year</label>
                                        <input type="text" name="target_year" class="form-control"
                                            value="{{ date('Y') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cluster_head">Month </label>
                                        <select name="target_month" id="target_month" class="form-control select2">
                                            <option value="-1">Select month</option>
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

                                <div class="col-md-2">
                                    <label></label>
                                    <button type="button" class="btn bg-green btn-sm form-control" id="btnSearchReport">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12" id="search_result_con">
                @include("admin.report_module.bd_land.bd_land_result")
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@push('js_lib')

    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
@endpush
@push('js_custom')
    <script>
        $(function() {
            var datepickerOptions = {
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: true,
                todayHighlight: true,
            };
            $('.select2').select2();

            $('.datepicker').datepicker(datepickerOptions);

            $(document).on("click", "#btnSearchReport", function(e) {
                e.preventDefault();
                var report_type = $("#team_name").val();

                if ($("#project_category").val() == "") {
                    alert("Please Select Project Category");
                    return;
                }

                if ($("#target_year").val() == "0") {
                    alert("You did not select any Year");
                    return;
                }
                if ($("#target_month").val() == "-1") {
                    alert("You did not select any Month");
                    return;
                }
                if (report_type == "") {
                    alert("You did not select any Report Type");
                } else {

                    $.ajax({
                        data: $('#frmSearch').serialize(),
                        url: 'bd_land_result',
                        type: 'post',
                        beforeSend: function() {
                            $.blockUI({
                                message: '<i class="icon-spinner4 spinner"></i>',
                                overlayCSS: {
                                    backgroundColor: '#1b2024',
                                    opacity: 0.8,
                                    zIndex: 999999,
                                    cursor: 'wait'
                                },
                                css: {
                                    border: 0,
                                    color: '#fff',
                                    padding: 0,
                                    zIndex: 9999999,
                                    backgroundColor: 'transparent'
                                }
                            });
                        },
                        success: function(data) {
                            $.unblockUI();
                            $("#search_result_con").html(data);
                            /*$('#tbl_search_result').DataTable({
                                "order": false,
                                bSort: false,
                                "pageLength": 50
                            });*/
                            $('.loader_con').addClass("hidden");
                            $('html, body').animate({
                                scrollTop: $("#search_result_con").offset().top
                            }, 1000);

                        },
                        error: function(data) {

                        }
                    });
                }
            });

            $(document).on("click", "#btnExportLeads", function(e) {
                $('#frmSearch').submit()
                /*$.ajax({
                    data: $('#frmSearch').submit(),
                    url: 'export_report',
                    type: 'post',
                    beforeSend:function(){
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        //window.open('', '_blank');
                    }
                });*/
            });


        });

        // function getTeamMembers(value) {
        //     $.ajax({
        //         data: {
        //             cluster_head_id: value
        //         },
        //         url: '{ route('getTLTeamMembers') }}',
        //         type: 'post',
        //         success: function(data) {
        //             $.unblockUI();
        //             $("#team_member_dropdown").html(data);


        //         },
        //         error: function(data) {

        //         }
        //     });
        // }
    </script>
@endpush
