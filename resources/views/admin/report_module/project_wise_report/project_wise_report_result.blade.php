@php
$is_super_admin = Session::get('user.is_super_admin');
@endphp

<style type="text/css">
    .table thead tr td,
    .table thead tr th,
    .table tbody tr td,
    .table tbody tr th,
    .table tfoot tr td,
    .table tfoot tr th {
        vertical-align: middle !important;
    }

</style>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>
        <button type="button" class="btn bg-blue btn-xs pull-right" id="btnExportLead" onclick="ExportCSV()">Export
            to CSV</button>
    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#5f5fd4;color:white">
                <tr>
                    <th class="text-center" rowspan="2">Project Name</th>
                    <th class="text-center" rowspan="2">Handover Date

                    </th>
                    <th rowspan="2">
                        CHS Name
                    </th>
                    <th rowspan="2">
                        BTI Share Unit
                    </th>
                    <th class="text-center" colspan="6">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year) ? $target_year : '2021' }}
                    </th>
                </tr>
                <tr>
                    <th>
                        Sale Unit
                    </th>
                    <th>
                        Cancel Unit
                    </th>
                    <th>
                        Total Sale Unit Till {{ isset($month) ? $month : 'January' }}'
                        {{ isset($target_year) ? $target_year : '2021' }}
                    </th>
                    <th>Remaining Unit
                    </th>
                    <th>
                        Total K
                    </th>
                    <th>
                        Priority
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $bti_share = 0;
                    $sale_unit = 0;
                    $cancel_unit = 0;
                    $total_sale_unit = 0;
                    $total_k = 0;
                    $total_prority = 0;
                @endphp
                @if (!empty($project_name))
                    @foreach ($project_name as $row)
                        @php
                            $total_sale = isset($total_sell_arr[$row->project_id]['t_sale']) ? $total_sell_arr[$row->project_id]['t_sale'] : 0;
                            $share_unit = isset($total_sell_arr[$row->project_id]['share_unit']) ? $total_sell_arr[$row->project_id]['share_unit'] : 0;
                            $remaining = $share_unit - $total_sale;
                        @endphp
                        <tr>
                            <td   class="text-center">{{ $row->project_id }} {{ $row->project_name }}</td>
                            <td   class="text-center"> </td>



                            <td   class="text-center">{{ $row->cate }}</td>

                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['share_unit']) ? $total_sell_arr[$row->project_id]['share_unit'] : 0 }}</td>
                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['sale']) ? $total_sell_arr[$row->project_id]['sale'] : 0 }}
                            </td>
                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['cancel_unit']) ? $total_sell_arr[$row->project_id]['cancel_unit'] : 0 }}
                            </td>
                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['t_sale']) ? $total_sell_arr[$row->project_id]['t_sale'] : 0 }}
                            </td>
                            <td   class="text-center">{{ $remaining }}</td>

                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['k']) ? $total_sell_arr[$row->project_id]['k'] : 0 }}
                            </td>
                            <td   class="text-center">{{ isset($total_sell_arr[$row->project_id]['priority']) ? $total_sell_arr[$row->project_id]['priority'] : 0 }}
                            </td>

                        </tr>
                        @php
                            $bti_share += isset($total_sell_arr[$row->project_id]['share_unit']) ? $total_sell_arr[$row->project_id]['share_unit'] : 0;
                            $sale_unit += isset($total_sell_arr[$row->project_id]['sale']) ? $total_sell_arr[$row->project_id]['sale'] : 0;
                            $cancel_unit += isset($total_sell_arr[$row->project_id]['cancel_unit']) ? $total_sell_arr[$row->project_id]['cancel_unit'] : 0;
                            $total_sale_unit += isset($total_sell_arr[$row->project_id]['t_sale']) ? $total_sell_arr[$row->project_id]['t_sale'] : 0;
                            $total_k += isset($total_sell_arr[$row->project_id]['k']) ? $total_sell_arr[$row->project_id]['k'] : 0;
                            $total_prority += isset($total_sell_arr[$row->project_id]['priority']) ? $total_sell_arr[$row->project_id]['priority'] : 0;
                        @endphp

                    @endforeach
                    <tr>
                        <td class="text-center" colspan="3">
                            Grand Total
                        </td>
                        <td class="text-center">
                            {{ $bti_share }}
                        </td>
                        <td class="text-center">
                            {{ $sale_unit }}
                        </td>
                        <td class="text-center">
                            {{ $cancel_unit }}
                        </td>
                        <td class="text-center">
                            {{ $total_sale_unit }}
                        </td>
                        <td class="text-center">
                            {{ $bti_share + $cancel_unit - $total_sale_unit }}
                        </td>
                        <td class="text-center">
                            {{ $total_k }}
                        </td>
                        <td class="text-center">
                            {{ $total_prority }}
                        </td>

                    </tr>
                @endif

            </tbody>


        </table>
    </div>
</div>
<script type="text/javascript">
    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "Project_wise_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
