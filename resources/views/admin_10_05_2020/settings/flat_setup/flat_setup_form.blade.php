<form id="frmUser" action="{{ !isset($flat_data)?route('store_flat_setup') : route('update_flat_setup',$flat_data->flatlist_pk_no) }}" method="{{ !isset($flat_data)?'post' : 'post' }}">
    <input type="hidden" id="hdnFlatSetupId" name="hdnFlatSetupId" value=" {{ isset($flat_data)? $flat_data->flatlist_pk_no:'' }} "/>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="flat_id">Flat ID:</label>
                                <input type="text" class="form-control" id="flat_id" name="flat_id" title="Lookup Name" placeholder="Flat ID" tabindex="" readonly="readonly" value="{{ isset($flat_data)? $flat_data->flat_id:'' }}">
                            </div>
                            <div class="form-group">
                                <label>Category<span class="text-danger"> *</span></label>
                                <select class="form-control required" name="category" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Category</option>
                                    @if(!empty($project_cat))
                                    @foreach ($project_cat as $key => $cat)

                                    @if (!empty($flat_data) && $flat_data->category_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $cat }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $cat }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Area<span class="text-danger"> *</span></label>
                                <select class="form-control required" name="area" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Area</option>
                                    @if(!empty($project_area))
                                    @foreach ($project_area as $key => $area)

                                    @if (!empty($flat_data) && $flat_data->area_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $area }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $area }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Project Name<span class="text-danger"> *</span></label>
                                <select class="form-control required" name="project_name" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Project Name</option>
                                    @if(!empty($project_name))
                                    @foreach ($project_name as $key => $pname)

                                    @if (!empty($flat_data) && $flat_data->project_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $pname }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $pname }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Size<span class="text-danger"> *</span></label>
                                <select class="form-control required" name="flat_size" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Flat Size</option>
                                    @if(!empty($project_size))
                                    @foreach ($project_size as $key => $size)

                                    @if (!empty($flat_data) && $flat_data->size_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $size }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $size }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="flat_name">Flat Name : <small style="color:red">*</small></label>
                                <input type="text" class="form-control" id="flat_name" name="flat_name" title="Flat Name" placeholder="Flat Name" tabindex="" value="{{ isset($flat_data)? $flat_data->flat_name:'' }}" />
                            </div>
                            <div class="form-group">
                                <label for="flat_description">Flat Description :</label>
                                <input type="text" class="form-control" id="flat_description" name="flat_description" title="Flat Description" placeholder="Flat Description" value="{{ isset($flat_data)? $flat_data->flat_description:'' }}" tabindex="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
        class="btn bg-purple btn-sm btnSaveUpdate">{{ isset($flat_data)? 'Update':'Create' }}</button>
        <span class="msg"></span>
    </div>
</form>