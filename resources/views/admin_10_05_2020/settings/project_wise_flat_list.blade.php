@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
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
                    <span type="button" class="btn bg-purple btn-sm pull-right create_modal" data-action="{{ route('create_project_wise_flat') }}" data-title="Project Wise Flat Setup">
                    <i class="fa fa-plus" style="font-size:12px;"></i> Add New
                </span>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="min-width:100px">SL#</th>
                            <th style="min-width:100px">ID</th>
                            <th style="min-width:100px" class="text-center">Flat Number</th>
                            <th style="min-width:100px" class="text-center">Category</th>
                            <th style="min-width:100px" class="text-center">Project Name</th>
                            <th style="min-width:100px" class="text-center">Flat Size</th>
                            <th style="min-width:100px" class="text-center">Status</th>
                            <th style="min-width:80px" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(!empty($flat_list))
                        @foreach($flat_list as $flat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $flat->flat_id }}</td>
                            <td class="text-center">{{ $flat->flat_name }}</td>
                            <td class="text-center">{{ isset($project_cat[$flat->category_lookup_pk_no])?$project_cat[$flat->category_lookup_pk_no]:'' }}</td>
                            <td class="text-center">{{ isset($project_name[$flat->project_lookup_pk_no])?$project_name[$flat->project_lookup_pk_no]:'' }}</td>
                            <td class="text-center">{{ isset($project_size[$flat->size_lookup_pk_no])?$project_size[$flat->size_lookup_pk_no]:'' }}</td>
                            <td class="text-center">
                                @if($flat->flat_status==1)
                                <span class="btn btn-block btn-xs bg-red">Sold</span>
                                @else
                                <span class="btn btn-block btn-xs bg-green">Available</span>
                                @endif
                            </td>
                            <td class="text-center" data-toggle="modal" data-target="#add_currency">
                                <span class="btn bg-info btn-xs update_modal" data-id="{{ $flat->flatlist_pk_no }}" data-action="{{ route('edit_project_wise_flat',$flat->flatlist_pk_no) }}"><i class="fa fa-pencil"></i></span>
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
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
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
        $(function () {
            $('#example1').DataTable()
            $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : false,
                'info'        : true,
                'autoWidth'   : false
            })
        })
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

