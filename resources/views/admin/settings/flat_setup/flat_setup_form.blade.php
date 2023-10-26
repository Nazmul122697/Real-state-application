@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
$ses_lead_type = Session::get('user.ses_lead_type');
$ses_super_admin = Session::get('user.is_super_admin');

@endphp



<form id="frmUser"
    action="{{ !isset($flat_data) ? route('store_flat_setup') : route('update_flat_setup', $flat_data->flatlist_pk_no) }}"
    method="{{ !isset($flat_data) ? 'post' : 'post' }}">
    <input type="hidden" id="hdnFlatSetupId" name="hdnFlatSetupId"
        value=" {{ isset($flat_data) ? $flat_data->flatlist_pk_no : '' }} " />
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="flat_id">Flat ID:</label>
                                <input type="text" class="form-control" id="flat_id" name="flat_id" title="Lookup Name"
                                    placeholder="Flat ID" tabindex="" readonly="readonly"
                                    value="{{ isset($flat_data) ? $flat_data->flat_id : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="">Inventory Type</label>
                                <select class="form-control select2" id="lead_type" name="lead_type" style="width: 100%;"
                                    aria-hidden="true" required="required">
                                    <option value="">Select Type</option>
                                    @if (!empty($lead_type))
                                        @foreach ($lead_type as $key => $type)
                                            @if ($ses_super_admin != 1)
                                                @if ($ses_lead_type == $key)
                                                    <option value="{{ $key }}" selected>{{ $type }}
                                                    </option>
                                                @elseif($group_id=='440')
                                                    <option value="{{ $key }}"
                                                        {{ isset($flat_data->lead_type) ? ($key == $flat_data->lead_type ? 'selected' : '') : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $key }}"
                                                    {{ isset($flat_data->lead_type) ? ($key == $flat_data->lead_type ? 'selected' : '') : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif




                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category<span class="text-danger"> *</span></label>
                                <select class="form-control required select2" name="category" style="width: 100%;"
                                    aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Category</option>
                                    @if (!empty($project_cat))
                                        @foreach ($project_cat as $key => $cat)

                                            @if (!empty($flat_data) && $flat_data->category_lookup_pk_no == $key)
                                                <option value="{{ $key }}" selected>{{ $cat }}
                                                </option>
                                            @else
                                                <option value="{{ $key }}">{{ $cat }}</option>
                                            @endif

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Area<span class="text-danger"> *</span></label>
                                <select class="form-control required select2" name="area" style="width: 100%;"
                                    aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Area</option>
                                    @if (!empty($project_area))
                                        @foreach ($project_area as $key => $area)

                                            @if (!empty($flat_data) && $flat_data->area_lookup_pk_no == $key)
                                                <option value="{{ $key }}" selected>{{ $area }}
                                                </option>
                                            @else
                                                <option value="{{ $key }}">{{ $area }}</option>
                                            @endif

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Project Name<span class="text-danger"> *</span></label>
                                <select class="form-control required select2" name="project_name" style="width: 100%;"
                                    aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Project Name</option>
                                    @if (!empty($project_name))
                                        @foreach ($project_name as $key => $pname)

                                            @if (!empty($flat_data) && $flat_data->project_lookup_pk_no == $key)
                                                <option value="{{ $key }}" selected>{{ $pname }}
                                                </option>
                                            @else
                                                <option value="{{ $key }}">{{ $pname }}</option>
                                            @endif

                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Size<span class="text-danger"> *</span></label>
                                <select class="form-control required select2" name="flat_size" style="width: 100%;"
                                    aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Flat Size</option>
                                    @if (!empty($project_size))
                                        @foreach ($project_size as $key => $size)

                                            @if (!empty($flat_data) && $flat_data->size_lookup_pk_no == $key)
                                                <option value="{{ $key }}" selected>{{ $size }}
                                                </option>
                                            @else
                                                <option value="{{ $key }}">{{ $size }}</option>
                                            @endif

                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="flat_name">Flat Name : <small style="color:red">*</small></label>
                                <input type="text" class="form-control" id="flat_name" name="flat_name"
                                    title="Flat Name" placeholder="Flat Name" tabindex=""
                                    value="{{ isset($flat_data) ? $flat_data->flat_name : '' }}" />
                            </div>

                            <div class="form-group">
                                <label for="flat_description">Flat Description :</label>
                                <input type="text" class="form-control" id="flat_description" name="flat_description"
                                    title="Flat Description" placeholder="Flat Description"
                                    value="{{ isset($flat_data) ? $flat_data->flat_description : '' }}" tabindex="">
                            </div>
                            <div class="form-group">
                                <label for="flat_description">Flat Cost :</label>
                                <input type="text" class="form-control" id="flat_cost" name="flat_cost"
                                    title="Flat Cost" placeholder="Flat Cost"
                                    value="{{ isset($flat_data) ? $flat_data->flat_cost : '' }}" tabindex="">
                            </div>

                            <div class="form-group">
                                <label for="flat_description">Selling Period :</label>
                                <input type="text" class="form-control" id="selling_period" name="selling_period"
                                    title="Selling Period" placeholder="Selling Period"
                                    value="{{ isset($flat_data) ? $flat_data->selling_period : '' }}" tabindex="">
                            </div>
                            @if ($ses_super_admin == 1 || $group_id == '440' || $ses_lead_type == 2 || $ses_lead_type == 11 || $ses_lead_type == 7 || $ses_lead_type == 9)

                                <div class="form-group">
                                    <label for="flat_description">Bed Count :</label>
                                    <input type="text" class="form-control" id="bed_count" name="bed_count"
                                        title="Bed Count" placeholder="Bed Count"
                                        value="{{ isset($flat_data) ? $flat_data->bed_count : '' }}" tabindex="">
                                </div>

                                <div class="form-group">
                                    <label for="flat_description">Rent Value :</label>
                                    <input type="text" class="form-control" id="rent_value" name="rent_value"
                                        title="Selling Period" placeholder="Rent Value"
                                        value="{{ isset($flat_data) ? $flat_data->rent_value : '' }}" tabindex="">
                                </div>

                                <div class="form-group">
                                    <label for="flat_description">Common Service Charge :</label>
                                    <input type="text" class="form-control" id="c_service_charge"
                                        name="c_service_charge" title="Common Service Charge "
                                        placeholder="Common Service Charge "
                                        value="{{ isset($flat_data) ? $flat_data->c_service_charge : '' }}"
                                        tabindex="">
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
            class="btn bg-purple btn-sm btnSaveUpdate">{{ isset($flat_data) ? 'Update' : 'Create' }}</button>
        <span class="msg"></span>
    </div>
</form>
