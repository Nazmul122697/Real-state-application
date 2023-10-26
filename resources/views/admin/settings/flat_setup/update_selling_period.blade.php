@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
$ses_lead_type = Session::get('user.ses_lead_type');
$ses_super_admin = Session::get('user.is_super_admin');

@endphp
<style>
    label {
        font-size: 14px;
        margin-bottom: 5px !important;
    }

    .form-control {
        height: 40px !important;
        margin-bottom: 10px !important;
    }

</style>


<form id="sellingPeriod"
    action="{{ route("store_update_selling_period") }}"
    method="post">
    <input type="hidden" id="hdnFlatSetupId" name="hdnFlatSetupId"
        value=" {{ isset($flat_data) ? $flat_data->flatlist_pk_no : '' }} " />
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="form-group">
                    <label>Project Name<span class="text-danger"> *</span></label>
                    <select class="form-control required" name="project_name" style="width: 100%;" aria-hidden="true"
                        required="required">
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
                    <label for="flat_description">Selling Period :</label>
                    <input type="text" class="form-control" id="selling_period" name="selling_period"
                        title="Selling Period" placeholder="Selling Period" value="" tabindex="">
                </div>



            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 text-left">
                <button type="button" class="btn btn-danger btn-sm " data-dismiss="modal">Close</button>

            </div>
            <div class="col-md-6 text-right">
                <button type="submit"
                    class="btn bg-purple btn-sm btnSaveUpdate">{{ isset($flat_data) ? 'Update' : 'Create' }}</button>
                <span class="msg"></span>
            </div>
        </div>
    </section>

</form>
