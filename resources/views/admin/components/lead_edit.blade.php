@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
$is_super_admin = Session::get('user.is_super_admin');
@endphp
<form id="frmLead" action="{{ !isset($lead_data) ? route('lead.store') : route('lead.update', $lead_data->lead_pk_no) }}"
    method="{{ !isset($lead_data) ? 'post' : 'patch' }}">
    <div class="box box-success">
        <div class="box-header with-border ">
            <h3 class="box-title">Customer Information</h3>
            @if ($group_id == 74)
                <a href="{{ route('import_csv') }}" class="btn bg-green btn-sm pull-right">Import CSV</a>
            @endif
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">

                @if ($is_super_admin == 1)
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="txt_lead_id">Lead Type<span class="text-danger">*</span></label>
                        <select name="lead_type" class="form-control select2" id="business_unit">
                            @if (!empty($lead_type))
                                @foreach ($lead_type as $key => $type)
                                        @if ($lead_data->lead_type == $key)
                                            <option value="{{ $key }}" selected>{{ $type }}
                                            </option>
                                            @else
                                            <option value="{{ $key }}">{{ $type }}
                                            </option>
                                        @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @endif

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="txt_lead_id">Lead ID </label>
                        <input type="text" class="form-control" id="txt_lead_id" readonly="readonly"
                            name="txt_lead_id" value="{{ $lead_data->lead_id }}" title=""
                            placeholder="USERGROPCODE+YYMM+99999" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="txt_lead_date">Date<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control datepicker required" id="txt_lead_date"
                            name="txt_lead_date" value="<?php echo date('d-m-Y'); ?>" title="" readonly=""
                            placeholder="Entry Date" />
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="txt_cus_first_name">Customer Name<span class="text-danger"> *</span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control required capitalize-text"
                                    id="customer_first_name" name="customer_first_name"
                                    value="{{ $lead_data->customer_firstname }}" title=""
                                    placeholder="Customer First Name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control required capitalize-text"
                                    id="customer_last_name" name="customer_last_name"
                                    value="{{ $lead_data->customer_lastname }}" title=""
                                    placeholder="Customer Last Name" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="row">
                        {{-- @if ($is_super_admin==1) --}}


                        <div class="col-md-6" style="{{$is_super_admin==1?'':'display: none'}}">
                            <div class="form-group">
                                <div><label for="customer_phone1">Phone Number 1 <span class="text-danger">
                                            *</span></label></div>
                                <div class="col-xs-4" style="padding-left: 0;">
                                    <select class="form-control select2" name="country_code1" aria-hidden="true">
                                        <option selected="selected" value="0">Country Code</option>
                                        @if (!empty($countries))
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->phonecode }}"
                                                    {{ $country->iso == 'BD' ? 'selected' : '' }}>
                                                    {{ $country->name . ' (' . $country->phonecode . ')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-8">
                                    <input type="number" class="form-control number-only required check_phone_no"
                                        id="customer_phone1" value="{{ $lead_data->phone1 }}" name="customer_phone1"
                                        maxlength="10" placeholder="Phone Number 1" />
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div><label for="customer_phone2">Phone Number 2</label></div>
                                <div class="col-xs-4" style="padding-left: 0;">
                                    <select class="form-control select2" name="country_code2" aria-hidden="true">
                                        <option selected="selected" value="0">Country Code</option>
                                        @if (!empty($countries))
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->phonecode }}"
                                                    {{ $country->iso == 'BD' ? 'selected' : '' }}>
                                                    {{ $country->name . ' (' . $country->phonecode . ')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xs-8">
                                    <input type="number" class="form-control number-only check_phone_no"
                                        id="customer_phone2" value="{{ $lead_data->phone2 }}" name="customer_phone2"
                                        maxlength="10" placeholder="Phone Number 2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="txt_cus_email">Customer Email </label>
                        <input type="email" class="form-control email-only" id="customer_email"
                            name="customer_email" value="{{ $lead_data->email_id }}" title="Customer Email"
                            placeholder="e.g. username@bti.com" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Occupation </label>
                        <select class="form-control select2" name="cmb_ocupation" style="width: 100%;"
                            aria-hidden="true" disabled>
                            <option selected="selected" value="0">Select Occupation</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="txt_organization">Organization </label>
                        <input type="email" class="form-control" id="txt_organization" name="txt_organization"
                            value="{{ $lead_data->organization_pk_no }}" title=""
                            placeholder="Organization" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->


        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 pb-15">
                    <button type="submit" class="btn bg-green btn-sm btnSaveUpdate">Update</button>
                    <button class="btn bg-red btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).on("keyup",".check_phone_no",function() {
        var phone_no = $(this).val();
        if(phone_no.charAt(0) == '0')
        {
            $(this).val('');
        }
    });

    $(document).on("focusout", ".check_phone_no", function(e){
        var phone_no = $(this).val();
        if(phone_no != "")
        {
            $.ajax({
                data: { phone_no:phone_no },
                url: "{{ route('check_if_phone_no_exist') }}",
                type: "post",
                beforeSend:function(){

                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if ( data !="" ) {
                        alert('Lead found with this Mobile No.\n\nLead ID: ' + data.lead_id + '\nCustomer Name: '+data.customer_name+'\n\nSales Agent Information:\nGroup: '+data.user_group+'\nName: '+data.agent_name+'\nPhone: '+data.agent_phone);
                        // window.location = "{{ route('lead.index') }}";
                        $("#customer_phone1").val('');
                        $("#customer_phone2").val('');
                    }
                }

            });

        }
    });
</script>
