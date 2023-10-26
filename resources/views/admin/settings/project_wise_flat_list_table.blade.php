<table id="example1" class="table table-bordered table-striped table-hover">
    <thead>
        <button style="margin-bottom: 10px" class="btn btn-danger delete_all" data-url="{{ route('delete_project_wise_flat') }}">Delete All Selected</button>
        <tr>
            <th style="min-width:100px">SL# Select</th>
            <th style="min-width:100px">Inventory Type</th>
            <th style="min-width:100px" class="text-center">Flat Number</th>
            <th style="min-width:100px" class="text-center">Category</th>
            <th style="min-width:100px" class="text-center">Project Name</th>
            <th style="min-width:100px" class="text-center">Flat Size</th>

            <th style="min-width:100px" class="text-center">Inventory Cost</th>
            <th style="min-width:50px" class="text-center">Status</th>

            <th style="min-width:80px" class="text-center">Action</th>

        </tr>
    </thead>

    <tbody>
        @if (!empty($flat_list))
            @foreach ($flat_list as $flat)
                <tr>
                    <td>{{ $loop->iteration }}
                        @if ($flat->flat_status != 1)
                         <input type="checkbox" class="sub_chk" data-id="{{ $flat->flatlist_pk_no }}">
                        @endif
                        </td>
                    <td>{{ isset($lead_type[$flat->lead_type]) ? $lead_type[$flat->lead_type] : '' }}</td>
                    <td class="text-center">{{ $flat->flat_name }}</td>
                    <td class="text-center">
                        {{ isset($project_cat[$flat->category_lookup_pk_no]) ? $project_cat[$flat->category_lookup_pk_no] : '' }}
                    </td>
                    <td class="text-center">
                        {{ isset($project_name[$flat->project_lookup_pk_no]) ? $project_name[$flat->project_lookup_pk_no] : '' }}
                    </td>
                    <td class="text-center">
                        {{ isset($project_size[$flat->size_lookup_pk_no]) ? $project_size[$flat->size_lookup_pk_no] : '' }}
                    </td>
                    <td>
                        {{ $flat->flat_cost }}
                    </td>
                    <td class="text-center">
                        @if ($flat->flat_status == 1)
                            <span class="btn btn-block btn-xs bg-red">Sold</span>
                        @else
                            <span class="btn btn-block btn-xs bg-green">Available</span>
                        @endif
                    </td>

                    <td class="text-center" data-toggle="modal" data-target="#add_currency">
                        <span class="btn bg-info btn-xs update_modal" data-id="{{ $flat->flatlist_pk_no }}"
                            data-action="{{ route('edit_project_wise_flat', $flat->flatlist_pk_no) }}"><i
                                class="fa fa-pencil"></i></span>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $('#example1').DataTable();
</script>

