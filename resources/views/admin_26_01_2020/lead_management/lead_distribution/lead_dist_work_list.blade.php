<td>{{ $row->lead_id }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
<td>{{ $row->phone1 }}</td>
<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>
<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
<td class="text-center">
    <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}" class="form-control select2 select2-hidden-accessible" style="width: 100%;"
        aria-hidden="true">
        <option selected="selected" value="">Please Select Agent</option>
        @if(!empty($sales_agent) && isset($sales_agent[$row->project_category_pk_no]))
        @foreach($sales_agent[$row->project_category_pk_no] as $key => $agent)
        <option Value="{{ $key }}">{{ $agent }}</option>
        @endforeach
        @endif
    </select>
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