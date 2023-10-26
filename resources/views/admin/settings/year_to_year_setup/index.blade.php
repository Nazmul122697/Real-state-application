@extends('admin.layouts.app')

@push('css_lib')

    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Year to Year Setup </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setting</a></li>
            <li class="active">Year to Year Setup</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                       
                        <span type="button" class="btn bg-purple btn-sm pull-right create_modal" data-modal="common-modal-sm"
                            data-action="{{ route('create_year_to_year_setup') }}" data-title="Year to Year Setup">
                            <i class="fa fa-plus" style="font-size:12px;"></i> Add New
                        </span>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive"  id="flat_list">
                        @include("admin.settings.year_to_year_setup.year_to_year_setup")
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

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');

            </script>
        @endforeach
    @endif
@endpush
