<table id="example1" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th style="min-width:100px">SL#</th>
            <th style="min-width:100px">Starting Year</th>
            <th style="min-width:100px" class="text-center">Starting Month</th>

            <th style="min-width:100px">Closing Year</th>
            <th style="min-width:100px" class="text-center">Closing Month</th>
            <th style="min-width:80px" class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
       
        @if (!empty($year_setup_list))
            @foreach ($year_setup_list as $year)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $year->starting_year }}</td>
                    <td class="text-center">
                        {{ isset($month_arr[$year->starting_month]) ? $month_arr[$year->starting_month] : '' }}</td>
                    <td class="text-center">
                        {{ $year->closing_year }}
                    </td>
                    <td class="text-center">
                        {{ isset($month_arr[$year->closing_month]) ? $month_arr[$year->closing_month] : '' }}
                    </td>
                    <td class="text-center" data-toggle="modal" data-target="#add_currency">
                        <span class="btn bg-info btn-xs create_modal" data-modal="common-modal-sm"
                            data-id="{{ $year->id }}"
                            data-action="{{ route('edit_year_setup', $year->id) }}"><i
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
