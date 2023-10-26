@extends('admin.layouts.app')

@push('css_lib')
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
                <div class="box-body table-responsive" id="list-body">
                    @include('admin.settings.flat_setup.flat_list')
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


