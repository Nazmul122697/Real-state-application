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
        <h1>SMS Campaign List</h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Campaign</a></li>
            <li class="active">SMS Campaign List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header text-right">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive" id="list-body">
                        <table id="user-table" class="table table-bordered table-striped table-hover data-table">
                            <thead>
                            <tr>
                                <th style="width: 50px;">SL</th>
                                <th>Campaign Name</th>
                                <th>Total</th>
                                <th>Sent SMS</th>
                                <th>Remain SMS</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(!empty($sms_list))
                                @foreach($sms_list as $row)
                                    <tr>
                                        <td style="width: 50px;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->campaign_name }}</td>
                                        <td>{{ $row->total }}</td>
                                        <td>{{ $row->send_sms }}</td>
                                        <td>{{ $row->remain_sms }}</td>


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
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush
@push('js_custom')
    <script>
        $(function () {
            //$('.data-table').DataTable();
            $('.data-table').dataTable({
                "columnDefs": [
                    {"width": "10px", "targets": 0}
                ]
            });
        });
    </script>
@endpush
