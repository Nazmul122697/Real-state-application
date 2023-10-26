@extends('admin.layouts.app')

@push('css_lib')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style>

</style>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Lead Entry</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('lead.index') }}">Lead Management</a></li>
        <li class="active">Lead Entry</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    @include('admin.lead_management.lead_entry_form')
</section>
<!-- Hidden row for append at plus button event -->
<div style="display: none;">
    <table id="hiddenTable">
        <tfoot id="appendRow">
            <tr>
                <td class="text-center" style="width: 50px;">
                    <button type="button" class="btn bg-red btn-sm rowRemove"><i class="fa fa-remove"></i></button>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control" id="child_name" name="child_name" value="" title="" placeholder="Children Name"/>
                    </div>
                </td>

                <td>
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" id="" name="" value="<?php echo date('d-m-Y'); ?>" title="" readonly="readonly" placeholder=""/>
                    </div>
                </td>
                <td class="text-center" style="width: 50px;">
                    <button type="button" class="btn bg-purple btn-sm rowAdd"><i class="fa fa-plus-square"></i></button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- ********END**********-->
@endsection


@push('js_lib')
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('js_custom')
<script>
    $(function() {
        var datepickerOptions = {
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: true,
        };

        $('.datepicker').datepicker(datepickerOptions);
        $('.select2').select2();

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });


        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });


        $(document).on("click","#chkKyc",function (e) {
            if($(this).is(':checked'))
                $("#more_details").removeClass("hidden");
            else
                $("#more_details").addClass("hidden");
        });

        $(document).on("click","input.hotline:checkbox",function(){
            var group = "input:checkbox[name='"+$(this).attr("name")+"']";
            $(group).attr("checked",false);
            $(this).attr("checked",true);
        });

        $(document).on("change", "#cmb_category", function(e){
            blockUI();
            var cat_id = $(this).val();
            var action = $(this).attr('data-action');
            $.ajax({
                data: { cat_id:cat_id },
                url: action,
                type: "post",
                beforeSend:function(){
                    $("#cmb_area").html("");
                    $("#cmb_project_name").html("");
                    $("#cmb_size").html("");
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    var area_list = size_list = project_list = "";
                    $.each(data.area_arr, function(i, item) {
                        area_list += "<option value='"+i+"'>"+item+"</option>";
                    });
                    $("#cmb_area").append(area_list);

                    $.each(data.size_arr, function(i, item) {
                        size_list += "<option value='"+i+"'>"+item+"</option>";
                    });
                    $("#cmb_size").append(size_list);

                    $.each(data.project_arr, function(i, item) {
                        project_list += "<option value='"+i+"'>"+item+"</option>";
                    });
                    $("#cmb_project_name").append(project_list);
                }

            });
            $.unblockUI();
        });


    });

</script>
@endpush
