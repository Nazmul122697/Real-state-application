@extends('admin.layouts.app')

@push('css_lib')
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    @php
    $group_id = Session::get('user.ses_role_lookup_pk_no');
    $ses_lead_type = Session::get('user.ses_lead_type');
    $ses_super_admin = Session::get('user.is_super_admin');

    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Project Wise Flat Setup </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setting</a></li>
            <li class="active">Project Wise Flat Setup</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Flat Type</label>
                                <select name="cmbLookupTypeMst" id="cmbLookupTypeMst"
                                    class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                    aria-hidden="true" onchange="getFlatListByType(this.value)">
                                    <option value="">Select</option>
                                    @if (!empty($lead_type))
                                        @foreach ($lead_type as $key => $type)
                                            @if ($ses_super_admin != 1)
                                                @if ($ses_lead_type == $key)
                                                    <option value="{{ $key }}" selected>{{ $type }}
                                                    </option>
                                                @elseif($group_id == '440')
                                                    <option value="{{ $key }}">{{ $type }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $key }}">{{ $type }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <span type="button" class="btn bg-purple btn-sm pull-right create_modal" style="margin-left: 20px; "
                            data-modal="common-modal-sm" data-action="{{ route('update_selling_period') }}"
                            data-title="Update Selling Period">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Update Selling Period
                        </span> &nbsp;

                        <a type="button" class="btn bg-purple btn-sm pull-right " style="margin-left: 20px; "
                            href="{{ route('import_csv_flat_setup') }}" data-title="Import CSV">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Import CSV
                        </a> &nbsp;
                        <span type="button" class="btn bg-purple btn-sm pull-right create_modal"
                            data-action="{{ route('create_project_wise_flat') }}" data-title="Project Wise Flat Setup">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Add New
                        </span>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive" id="flat_list">
                        @include("admin.settings.project_wise_flat_list_table")
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
    <!-- Select2 -->
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        //Initialize Select2 Elements
        $('.select2').select2();
        //Date picker
        $('.date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
    </script>
    <script>
        $(function() {
            $('#example1').DataTable()
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': true,
                'autoWidth': false
            })
        })

        function getFlatListByType(value) {
            $.ajax({
                url: "{{ route('getFlatListByType') }}",
                type: "post",
                data: {
                    value: value
                },
                beforeSend: function() {
                    blockUI();
                },
                success: function(data) {
                    $.unblockUI();
                    $("#flat_list").html(data);

                }

            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    {{-- for delete option --}}
    <script type="text/javascript">
        $(document).ready(function() {


            $('.delete_all').on('click', function(e) {


                var allVals = [];
                $(".sub_chk:checked").each(function() {

                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {


                    var check = confirm("Are you sure you want to delete this row?");
                    if (check == true) {


                        var join_selected_values = allVals.join(",");


                        $.ajax({
                            url: $(this).data('url'),
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data['success']) {
                                    $(".sub_chk:checked").each(function() {
                                        $(this).parents("tr").remove();
                                    });
                                    alert(data['success']);
                                } else if (data['error']) {
                                    alert(data['error']);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                alert(data.responseText);
                            }
                        });


                        $.each(allVals, function(index, value) {
                            $('table tr').filter("[data-row-id='" + value + "']").remove();
                        });
                    }
                }
            });


            $('[data-toggle=confirmation]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                onConfirm: function(event, element) {
                    element.trigger('confirm');
                }
            });


            $(document).on('confirm', function(e) {
                var ele = e.target;
                e.preventDefault();


                $.ajax({
                    url: ele.href,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data['success']) {
                            $("#" + data['tr']).slideUp("slow");
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function(data) {
                        alert(data.responseText);
                    }
                });


                return false;
            });
        });
    </script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif
@endpush
