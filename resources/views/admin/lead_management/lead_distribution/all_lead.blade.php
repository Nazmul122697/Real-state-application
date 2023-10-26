@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
@endphp
<div class="head_action" style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">
    <strong style="display: inline-block;">Auto Distribution :</strong> &nbsp &nbsp &nbsp &nbsp
    <label class="">
        &nbsp Yes &nbsp
        <input type="radio" value="1" name="auto_distribute" class="auto_distribute"
            {{ $ses_auto_dist == 1 ? 'checked' : '' }}>
    </label>

    <label class="">
        &nbsp No &nbsp
        <input type="radio" value="0" name="auto_distribute" class="auto_distribute"
            {{ $ses_auto_dist == 0 ? 'checked' : '' }}>
    </label>

    <label for="dist_date" style="display: inline-block; margin-left: 50px; margin-right: 10px">Date :</label>
    <div class="form-group" style="display: inline-block; margin-bottom: 0px !important;">
        <input type="text" class="form-control datepicker" id="dist_date" name="dist_date"
            value="{{ $ses_auto_dist == 1 ? date('d-m-Y', strtotime($ses_dist_date)) : date('d-m-Y') }}" title=""
            readonly="readonly" placeholder="" style="display: inline-block;" />
    </div>
</div><br clear="all" />
<div class="tab-pane active table-responsive" id="all_lead">
    <table id="work_list" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">Lead ID</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Mobile</th>
                <th class="text-center">Category</th>
                <th class="text-center">Area</th>
                <th class="text-center">Project</th>
                <th class="text-center">Size</th>
                <th class="text-center">Sales Agent</th>
                <th class="text-center"></th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if (!empty($lead_data))
                @foreach ($lead_data as $row)
                    <tr>
                        <td>{{ $row->lead_id }}</td>
                        <td>{{ $row->customer_firstname . ' ' . $row->customer_lastname }}</td>
                        <td>{{ $row->phone1 }}</td>
                        <td>{{ $row->project_category_name }}</td>
                        <td>{{ $row->project_area }}</td>
                        <td>{{ $row->project_name }}</td>
                        <td>{{ $row->project_size }}</td>
                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                        <td class="text-center">
                            @if ($row->project_category_pk_no > 0 && $row->project_area_pk_no > 0)
                                <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}"
                                    class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                    aria-hidden="true">
                                    <option selected="selected" value="">Please Select Agent</option>
                                    @php
                                        $project_area = explode(',', $row->project_area_pk_no);
                                    @endphp

                                    @foreach ($project_area as $area)
                                        @if (!empty($sales_agent) && isset($sales_agent[$row->project_category_pk_no][$area]))
                                            @foreach ($sales_agent[$row->project_category_pk_no][$area] as $key => $agent)
                                                <option Value="{{ $key }}">{{ $agent }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            @endif

                            @if ($row->project_category_pk_no == 0 && $row->project_area_pk_no > 0)
                                <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}"
                                    class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                    aria-hidden="true">
                                    <option selected="selected" value="">Please Select Agent</option>
                                    @php
                                        $project_area = explode(',', $row->project_area_pk_no);
                                    @endphp

                                    @foreach ($project_area as $area)
                                        @if (!empty($sales_agent_area) && isset($sales_agent_area[$area]))
                                            @foreach ($sales_agent_area[$area] as $key => $agent)
                                                <option Value="{{ $key }}">{{ $agent }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </td>
                        <td class="text-center" style="font-weight: bold;">
                            @if ($row->lead_dist_type == 1)
                                Manual
                            @elseif($row->lead_dist_type == 2)
                                Auto
                            @else
                                Pending
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                                data-id="{{ $row->lead_pk_no }}"
                                data-action="{{ route('lead_view', $row->lead_pk_no) }}"><i
                                    class="fa fa-eye"></i></span>
                            <span class="btn bg-info btn-xs distribute-lead" title="Distribute Lead" data-type=""
                                data-list-action="load_dist_leads" data-target="#all_lead"
                                data-id="{{ $row->leadlifecycle_pk_no }}"
                                data-action="{{ route('distribute_lead', $row->leadlifecycle_pk_no) }}">
                                <i class="fa fa-save"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
