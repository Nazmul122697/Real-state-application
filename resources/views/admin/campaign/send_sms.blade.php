@extends('admin.layouts.app')

@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>SMS CAMPAIGN</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Campaign</a></li>
        <li class="active">Send SMS</li>
    </ol>
</section>


<section class=" mt-20">
    <form id="sendsms" action="{{route('send-sms-to-gateway')}}" method="post">
        @csrf
        <?php if (session()->has('success')) { ?>
            <div class="row">
                <div class="col-md-12" style="margin: 10px;">
                    <div class="alert alert-success">{{session()->get('success')}}</div>
                </div>
            </div>
        <?php } ?>
    <div class="form-row">

        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <label for="" >Campaign Name</label>
                        <select class="form-control select2 select2-hidden-accessible requird" required id="campaign_name" name="campaign_name" style="width: 100%;">
                                <option value="">Select</option>
                                @if(!empty($campaign))
                                @foreach ($campaign as $value)

                                <option value="{{ $value->campaign_name }}">{{ $value->campaign_name }}</option>

                                @endforeach
                                @endif
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <label class="" for="sms_title">Title</label>
                        <input type="text" class="form-control" id="sms_title" name="sms_title" value="" placeholder="Title">
                    </div> --}}


                    <div class="form-group">
                        <label class="" for="write_massage">Massage</label>
                        <textarea class="form-control " required id="write_massage" name="write_massage" rows="10" placeholder="Write Your Massage Here"></textarea>
                    </div>

                    <div class="form-group mt-20" style="margin-bottom: 0px!important; text-align:right;">
                        <button type="submit" class="btn btn-danger btn-xs"> Cancel</button>
                        <button type="submit" class="btn btn-success btn-xs"> Submit</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</form>
</section>

@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endpush
@push('js_custom')
    <script>
        $("document").ready(function (){
            $('.select2').select2();
        });
    </script>
@endpush
