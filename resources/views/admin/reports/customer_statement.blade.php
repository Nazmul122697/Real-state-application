@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Customer Statement </h1>

        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Report</a></li>
            <li class="active">Customer Statement</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right"></div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="min-width: 20px;">SL#</th>
                                <th style="min-width: 90px;">No. of Order</th>
                                <th style="min-width: 150px;">Customer Name</th>
                                <th style="min-width: 70px;">Total Item</th>
                                <th style="min-width: 70px;">Balance</th>
                                <th style="min-width: 70px;" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>01</td>
                                <td>19</td>
                                <td>Sunil yadav</td>
                                <td>20</td>
                                <td><small>(RM)</small>499.00</td>
                                <td class="text-center">
                                    <a href="customer_statement_view.php" title="View"><i class="fa fa-eye"></i></a> &nbsp;
                                    <a href="#" title="View Note"><i class="fa fa-list-alt"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>02</td>
                                <td>20</td>
                                <td>Sunil yadav</td>
                                <td>21</td>
                                <td><small>(RM)</small>499.00</td>
                                <td class="text-center">
                                    <a href="#" title="View"><i class="fa fa-eye"></i></a> &nbsp;
                                    <a href="#" title="View Note"><i class="fa fa-list-alt"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>03</td>
                                <td>21</td>
                                <td>Sunil yadav</td>
                                <td>22</td>
                                <td><small>(RM)</small>499.00</td>
                                <td class="text-center">
                                    <a href="#" title="View"><i class="fa fa-eye"></i></a> &nbsp;
                                    <a href="#" title="View Note"><i class="fa fa-list-alt"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>04</td>
                                <td>22</td>
                                <td>Sunil yadav</td>
                                <td>23</td>
                                <td><small>(RM)</small>499.00</td>
                                <td class="text-center">
                                    <a href="#" title="View"><i class="fa fa-eye"></i></a> &nbsp;
                                    <a href="#" title="View Note"><i class="fa fa-list-alt"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>05</td>
                                <td>23</td>
                                <td>Sunil yadav</td>
                                <td>24</td>
                                <td><small>(RM)</small>499.00</td>
                                <td class="text-center">
                                    <a href="#" title="View"><i class="fa fa-eye"></i></a> &nbsp;
                                    <a href="#" title="View Note"><i class="fa fa-list-alt"></i></a>
                                </td>
                            </tr>
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
    <!-- SlimScroll -->
@endpush

@push('js_custom')
    <script>
        $(function() {
            $('#example1').DataTable();
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': false
            });


            $('.input-phone').intlInputPhone();


            $("#alert_success").on("click", function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Do you want to continue',
                    type: 'success',
                    confirmButtonText: 'Cool'
                });

            });
        })

    </script>
@endpush
