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
        {{-- <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button> --}}
        <button type="button" class="btn bg-blue btn-xs pull-right" id="btnExportLead" onclick="ExportCSV()">Export
            to CSV</button>
    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#6e9cce;color:white">
                <tr>
                    <th class="text-center" colspan="6">Team Details</th>
                    <th class="text-center" colspan="3">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="3" style="background-color:#6e9cce;color:white">Year to Date</th>
                </tr>
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">Sales Agent</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Area</th>
                    <th class="text-center">DOJ</th>
                    <th class="text-center">DOR</th>
                    <th>Sales Unit</th>
                    <th>Month Target</th>
                    <th>Achievement
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Target
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Achv.
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Achv.(%)
                    </th>

                </tr>


            </thead>
            <tbody>
                @php
                    $total_target_mm = 0;
                    $target_no_of_sale = 0;
                    $total_target_ytd = 0;
                    $target_ytd_no_of_sale = 0;
                @endphp
                @if (!empty($get_all_members))
                    @foreach ($get_all_members as $row)
                        @php
                            
                            $area_ids = '';
                            $area_ids_arr = explode(',', $row->area_lookup_pk_no);
                            if (!empty($area_ids_arr)) {
                                foreach ($area_ids_arr as $area_id) {
                                    $area_ids .= $project_area[$area_id] . ', ';
                                }
                            }
                            $area_ids = rtrim($area_ids, ', ');
                        @endphp

                        <tr style="border-bottom:1px solid black;">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $row->user_fullname }}</td>
                            <td>
                                {{ isset($project_cat[$row->category_lookup_pk_no]) ? $project_cat[$row->category_lookup_pk_no] : '' }}

                            </td>
                            <td>{{ $area_ids }}</td>
                            <td class="text-center">
                                {{ isset($doj_arr[$row->user_pk_no]) ? date('d/m/Y', strtotime($doj_arr[$row->user_pk_no])) : '' }}
                            </td>
                            <td class="text-center">
                                {{ isset($dor_arr[$row->user_pk_no]) ? date('d/m/Y', strtotime($dor_arr[$row->user_pk_no])) : '' }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$row->user_pk_no]['no_of_sale']) ? $ach_arr[$row->user_pk_no]['no_of_sale'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : 0 }}
                            </td>

                            @php
                                $result1 = isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : 0;
                                $result2 = isset($ach_arr[$row->user_pk_no]['no_of_sale']) ? $ach_arr[$row->user_pk_no]['no_of_sale'] : 0;
                                $fin_res = 0;
                                try {
                                    $fin_res = ($result2 / $result1) * 100;
                                } catch (\Exception $e) {
                                    $fin_res = 0;
                                }
                            @endphp
                            <td>{{ number_format($fin_res, 2) }} %</td>
                            @php
                                $result1 = isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : 0;
                                $result2 = isset($ach_arr[$row->user_pk_no]['ytb_ach']) ? $ach_arr[$row->user_pk_no]['ytb_ach'] : 0;
                                $fin_res = 0;
                                try {
                                    $fin_res = ($result2 / $result1) * 100;
                                } catch (\Exception $e) {
                                    $fin_res = 0;
                                }
                            @endphp
                            <td class="text-center">
                                {{ isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$row->user_pk_no]['ytb_ach']) ? $ach_arr[$row->user_pk_no]['ytb_ach'] : 0 }}
                            </td>
                            <td>{{ number_format($fin_res, 2) }} %</td>

                        </tr>
                        @php
                            $total_target_mm += isset($target_mm[$row->user_pk_no]) ? $target_mm[$row->user_pk_no] : 0;
                            $target_no_of_sale += isset($ach_arr[$row->user_pk_no]['no_of_sale']) ? $ach_arr[$row->user_pk_no]['no_of_sale'] : 0;
                            $total_target_ytd += isset($target_ytd[$row->user_pk_no]) ? $target_ytd[$row->user_pk_no] : 0;
                            $target_ytd_no_of_sale += isset($ach_arr[$row->user_pk_no]['ytb_ach']) ? $ach_arr[$row->user_pk_no]['ytb_ach'] : 0;
                        @endphp






                    @endforeach
                    <tr>
                        <td colspan="6" class="text-center">
                            Resigned/Promoted
                        </td>
                        <td class="text-center">
                            {{ $ach_arr['resign_total']['resign_total'] }}

                        </td>
                        <td class="text-center">
                            {{ $target_mm['resize'] }}
                        </td>
                        @php
                            $result1 = $target_mm['resize'];
                            $result2 = $ach_arr['resign_total']['resign_total'];
                            $fin_res = 0;
                            try {
                                $fin_res = ($result2 / $result1) * 100;
                            } catch (\Exception $e) {
                                $fin_res = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($fin_res, 2) }} %
                        </td>
                        <td class="text-center">
                            {{ $target_ytd['resize_t'] }}
                        </td>
                        <td class="text-center">
                            {{ $ach_arr['resign_y_total']['resign_y_total'] }}
                        </td>
                        @php
                            $result1 = $target_ytd['resize_t'];
                            $result2 = $ach_arr['resign_y_total']['resign_y_total'];
                            $fin_res = 0;
                            try {
                                $fin_res = ($result2 / $result1) * 100;
                            } catch (\Exception $e) {
                                $fin_res = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($fin_res, 2) }} %
                        </td>
                        @php
                            //  dd( $target_ytd ,   $target_ytd_no_of_sale,   $target_no_of_sale , $target_ytd['resize_t'],  $target_mm['resize'], $ach_arr['resign_total']['resign_total'],$ach_arr['resign_y_total']['resign_y_total'] );
                            $target_no_of_sale += $ach_arr['resign_total']['resign_total'];
                            $total_target_mm += $target_mm['resize'];
                            $total_target_ytd += $target_ytd['resize_t'];
                            $target_ytd_no_of_sale += $ach_arr['resign_y_total']['resign_y_total'];
                            
                        @endphp

                    </tr>

                    <tr>
                        <td colspan="6" class="text-center">
                            Grand Total
                        </td>
                        <td class="text-center">
                            {{ $target_no_of_sale }}
                        </td>
                        <td class="text-center">
                            {{ $total_target_mm }}
                        </td>
                        @php
                            
                            $fin_res = 0;
                            try {
                                $fin_res = ($target_no_of_sale / $total_target_mm) * 100;
                            } catch (\Exception $e) {
                                $fin_res = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($fin_res, 2) }} %
                        </td>
                        <td class="text-center">
                            {{ $total_target_ytd }}
                        </td>
                        <td class="text-center">
                            {{ $target_ytd_no_of_sale }}
                        </td>

                        @php
                           
                            $fin_res = 0;
                            try {
                                $fin_res = ($target_ytd_no_of_sale / $total_target_ytd) * 100;
                            } catch (\Exception $e) {
                                $fin_res = 0;
                            }
                        @endphp
                        <td>
                            {{ number_format($fin_res, 2) }} %
                        </td>

                    </tr>
                @else
                    <tr>
                        <td colspan="11" class="text-center">
                            No Data found
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
            name: "hollow_block_sales.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
