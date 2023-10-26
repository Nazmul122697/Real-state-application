<td>{{ $row->lead_id }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
<td>{{ $row->phone1 }}</td>
<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>
<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
<td class="text-center">
    @if($row->project_category_pk_no > 0 && $row->project_area_pk_no > 0)
        <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}" class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
            <option selected="selected" value="">Please Select Agent</option>
            @php
            $project_area = explode(",", $row->project_area_pk_no);
            @endphp

            @foreach($project_area as $area)
            @if(!empty($sales_agent) && isset($sales_agent[$row->project_category_pk_no][$area]))
            @foreach($sales_agent[$row->project_category_pk_no][$area] as $key => $agent)
            <option Value="{{ $key }}">{{ $agent }}</option>
            @endforeach
            @endif

            @endforeach
        </select>
    
    @endif

    @if($row->project_category_pk_no == 0 && $row->project_area_pk_no > 0)
        <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}" class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
            <option selected="selected" value="">Please Select Agent</option>
            @php
            $project_area = explode(",", $row->project_area_pk_no);
            @endphp
            
            @foreach($project_area as $area)
            @if(!empty($sales_agent_area) && isset($sales_agent_area[$area]))
            @foreach($sales_agent_area[$area] as $key => $agent)
            <option Value="{{ $key }}">{{ $agent }}</option>
            @endforeach
            @endif
            @endforeach
        </select>
    
    @endif
</td>
<td class="text-center" style="font-weight: bold;">
    @if($row->lead_dist_type == 1)
    Manual
    @elseif($row->lead_dist_type == 2)
    Auto
    @else
    Pending
    @endif
</td>