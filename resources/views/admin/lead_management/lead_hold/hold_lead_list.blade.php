@extends('admin.layouts.app')

@push('css_lib')
    <link rel="stylesheet"
        href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')

    @php
    $user_id = Session::get('user.ses_user_pk_no');
    $role_id = Session::get('user.ses_role_lookup_pk_no');
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Hold Lead List</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('lead.index') }}">Lead Management</a></li>
            <li class="active">Hold Lead List</li>
        </ol>
    </section>

    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                @if ($role_id == 73)
                    <form action="" id="hold_form" method="POST">
                        @csrf
                        <div class="nav-tabs-custom">
                            <div class="row container">
                                <div class="col-md-3">
                                    <label for="">Select Agent</label>
                                    <select name="salesAgent" id="salesAgent" class="form-control" id="">
                                        <option value="">Select One</option>
                                        @if (!empty($sales_agent))
                                            @foreach ($sales_agent as $row)
                                                <option value="{{ $row->user_pk_no }}">{{ $row->user_fullname }}
                                                </option>

                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="button" class="btn btn-success btn_distribution" style="margin-top: 14px;"
                                        value="Lead Transfer">
                                </div>

                            </div>

                            <div class="tab-content">
                                <table id="hold_lead_list" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Lead Id</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Mobile</th>
                                            <th>Project</th>
                                            <th>Agent</th>
                                            <th>
                                                Action <br>
                                                <span type="button" style="cursor:pointer "
                                                    class="btn-sm btn-success btn_close" style="margin-top: 14px;"
                                                    value="">Close </span>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </form>
                @else
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4 class="pull-left" style="margin-right: 20px;"><i class="icon fa fa-ban"></i> Forbidden!
                        </h4>
                        You are not Authorized to view this page
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('js_lib')
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        $(function() {

            let url = '{{ route('lead_hold_list_table') }}';;
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
            $('#hold_lead_list').DataTable({
                "processing": true,
                "serverSide": true,
                "searchable ": true,
                "ordering": false,
                "ajax": {
                    "url": url,
                    "dataType": "json",
                    "type": "get",
                    //"data":{ _token: "{{ csrf_token() }}"}
                },
                "columnDefs": [

                    {
                        "targets": [-1],
                        "render": function(data, type, full, meta) {
                            return `<input type="checkbox" name="lead_pk_no[]" value="${full[8]}">`;

                        }
                    },

                ]

            });
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: true,
                todayHighlight: true
            });


            $(document).on("click", ".btn_distribution", function(e) {
                if (confirm("Are You Sure?")) {
                    blockUI();
                    var sales_agent = $("#salesAgent").val();
                    if (sales_agent == "") {
                        alert("You did not select any Sales Agent");
                        $.unblockUI();
                        return;
                    }

                    $.ajax({
                        data: $("#hold_form").serialize(),
                        url: "{{ route('lead_transfer_by_cre') }}",
                        type: "post",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            toastr.success(data.message, data.title);
                            $.unblockUI();
                            location.reload(true);
                        }
                    });

                }
            });
            $(document).on("click", ".btn_close", function(e) {
                if (confirm("Are You Sure?")) {
                    blockUI();
                    var sales_agent = $("#salesAgent").val();

                    $.ajax({
                        data: $("#hold_form").serialize(),
                        url: "{{ route('lead_closed_by_cre') }}",
                        type: "post",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            toastr.success(data.message, data.title);
                            $.unblockUI();
                            location.reload(true);
                        }
                    });

                }
            });



        });
    </script>
@endpush
