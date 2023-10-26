{{-- Customer Basic --}}
<div class="box box-success">
    <div class="box-header with-border ">
        <h3 class="box-title">Customer Information</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <label for="lead_id">Lead ID :</label>
                <h5>{{ $lead_data->lead_id }}</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_entry_date">Date :</label>
                <h5>{{ date("d-m-Y") }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Customer Name :</label>
                <h5 style="text-transform: capitalize;">{{ $lead_data->customer_firstname }}</h5>
                <h5 style="text-transform: capitalize;">{{ $lead_data->customer_lastname }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Phone Number1 :</label>
                <h5>{{ $lead_data->phone1 }}</h5>
                <label for="">Phone Number2 :</label>
                <h5>{{ $lead_data->phone2 }}</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_email">Customer Email :</label>
                <h5>{{ $lead_data->email_id }}</h5>
            </div>

            <div class="col-md-4">
                <label>Occupation :</label>
                <h5>{{ $lead_data->occup_name }}</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_occ_org">Organization :</label>
                <h5>{{ $lead_data->org_name }}</h5>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>

{{-- Project Detail --}}
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Project Detail</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <label>Category :</label>
                <h5>{{ $lead_data->project_category_name }}</h5>
            </div>
            <div class="col-md-3">
                <label>Area :</label>
                <h5>{{ $lead_data->project_area }}</h5>
            </div>

            <div class="col-md-3">
                <label>Project Name :</label>
                <h5>{{ $lead_data->project_name }}</h5>
            </div>

            <div class="col-md-3">
                <label>Size :</label>
                <h5>{{ $lead_data->project_size }}</h5>
            </div>
        </div>
    </div>
</div>

{{-- Source Detail (Auto) --}}
<div class="box" style="border-color:#ff851b;">
    <div class="box-header with-border">
        <h3 class="box-title">Source Detail (Auto)</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <label>Source Title :</label>
                <h5>{{ $lead_data->source_auto_usergroup }}</h5>
            </div>

            <div class="col-md-6">
                <label>Source Name :</label>
                <h5>{{ $lead_data->user_full_name }}</h5>
            </div>
            @if($lead_data->source_auto_usergroup_pk_no == 73)
            <div class="col-md-4">
                <label>Sub Source Name :</label>
                <h5>{{ $lead_data->source_auto_sub }}</h5>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Source Detail --}}
@if($lead_data->source_auto_usergroup_pk_no == 119)
<div class="box" style="border: 0px;">
    <div class="box-header">
        <h3 class="box-title">SAC</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <label for="src_name">Name :</label>
                <h5>{{ $lead_data->source_sac_name }}</h5>
            </div>
            <div class="col-md-12">
                <label for="src_note">Note :</label>
                <h5>{{ $lead_data->source_sac_note }}</h5>
            </div>
        </div>
    </div>
</div>
@endif

@if($lead_data->source_auto_usergroup_pk_no == 74)
@php
$digi_mkt = explode(",",$lead_data->source_digital_marketing);
@endphp
<div class="box" style="border: 0px;">
    <div class="box-header">
        <h3 class="box-title">Digital Marketing</h3>
    </div>
    <div class="box-body">
        @foreach($digi_mkt as $digi_id)
        @if(isset($digital_mkt[$digi_id]))
        <div class="form-group" style="margin: 0;">
            <label style="cursor:pointer;">
                <span style="font-size:18px; margin-top:-5px;">
                    {{ $digital_mkt[$digi_id]  }}
                </span>
            </label>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif

@if($lead_data->source_auto_usergroup_pk_no == 75)
<div class="box" style="border: 0px;">
    <div class="box-header">
        <h3 class="box-title">Internal Reference</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <label for="sale_agent">Emp ID :</label>
                <h5>{{ $lead_data->source_ir_emp_id }}</h5>
            </div>

            <div class="col-md-6">
                <label for="emp_name">Name :</label>
                <h5>{{ $lead_data->source_ir_name }}</h5>
            </div>

            <div class="col-md-6">
                <label for="emp_position">Position :</label>
                <h5>{{ $lead_data->source_ir_position }}</h5>
            </div>

            <div class="col-md-6">
                <label for="emp_contact">Contact Number :</label>
                <h5>{{ $lead_data->source_ir_contact_no }}</h5>
            </div>
        </div>
    </div>
</div>
@endif


@if($lead_data->source_auto_usergroup_pk_no == 76)
@php
$source_hotline = $lead_data->source_hotline;
@endphp
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Hotline: </h3>
        <h5>{{ $source_hotline }}</h5>
    </div>
</div>
@endif

{{-- Sales Executive --}}
@if($lead_data->source_auto_usergroup_pk_no == 75)
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Sales Executive</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sale_executive_user_group">User Group :</label>
                    <input type="text" class="form-control" id="sale_executive_user_group"
                    name="sale_executive_user_group" value="" readonly="readonly" title="User Group"
                    placeholder="User Group"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="sale_ex_user">User Name :</label>
                    <input type="text" class="form-control" id="sale_ex_user" name="sale_ex_user" value=""
                    readonly="readonly" title="User Name" placeholder="User Name"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- More Details --}}
<div class="box" style="border-color:#9384ff;">
    <div class="box-header with-border">
        <h3 class="box-title">More Datails (KYC) </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <label for="">Customer DOB :</label>
                <h5>{{ ($lead_data->Customer_dateofbirth!='0000-01-01')?date("d-m-Y", strtotime($lead_data->Customer_dateofbirth)):'' }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Wife Name :</label>
                <h5>{{ $lead_data->customer_wife_name }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Wife DOB</label>
                <h5>{{ ($lead_data->customer_wife_dataofbirth!='0000-01-01')?date("d-m-Y", strtotime($lead_data->customer_wife_dataofbirth)):'' }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Marriage Anniversary :</label>
                <h5>{{ ($lead_data->Marriage_anniversary!='0000-01-01')?date("d-m-Y", strtotime($lead_data->Marriage_anniversary)):'' }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Children Name :</label>
                <h5>{{ $lead_data->children_name1 }}</h5>
            </div>

            <div class="col-md-4">
                <label for="">Children DOB :</label>
                <h5>{{ ($lead_data->children_dateofbirth1!='0000-01-01')?date("d-m-Y", strtotime($lead_data->children_dateofbirth1)):'' }}</h5>
            </div>
        </div>
    </div>
</div>
<div class="box" style="border-color:#9384ff;">
    <div class="box-header with-border">
        <h3 class="box-title">Remarks</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">{{ $lead_data->remarks }}</div>
        </div>
    </div>
</div>