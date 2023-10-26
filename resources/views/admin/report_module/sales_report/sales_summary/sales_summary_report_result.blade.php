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
                    <th class="text-center" colspan="4">Team Details</th>
                    <th class="text-center" colspan="7">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="9" style="background-color:#6e9cce;color:white">Year to Date</th>
                </tr>
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">Emp ID</th>
                    <th class="text-center">CHS Name</th>
                    <th class="text-center">Category</th>
                    <th>Target</th>
                    <th>No of Sale</th>
                    <th>Sale Value</th>
                    <th>No of Cancel</th>
                    <th>Cancel Value
                    </th>
                    <th>Resultant Sale
                    </th>
                    <th>Achievement
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Target
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD No of Sale
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Sale Value
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD No of Cancel
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Cancel Value
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Resultant Sales
                    </th>
                    <th style="background-color:#6e9cce;color:white">YTD Achievement %
                    </th>

                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $rand = '#F5C87B';
                    $rand_pre = '';
                    $sr_cons = 0;
                    $cons = 0;
                    $target_sr_cons = 0;
                    $target_cons = 0;
                    $target_mm = 0;
                    $target_no_of_sale = 0;
                    $target_sale_value = 0;
                    $target_sale_cancel = 0;
                    $target_sale_cancel_value = 0;
                    $target_ytd = 0;
                    $target_ytd_no_of_sale = 0;
                    $target_ytd_sale_value = 0;
                    $target_ytd_sale_cancel = 0;
                    $target_ytd_cancel_value = 0;
                @endphp
                @if (!empty($team_build))
                    @foreach ($team_build as $hod)
                        @php

                            $key = 0;
                            $total_new_k1 = 0;
                            $total_k1 = 0;
                            $total_prority = 0;
                            $total_m_con = 0;
                            $total_y_con = 0;
                            $total_new_lead = 0;
                            $total_all_lead = 0;
                        @endphp

                        @php
                            $tl_count = 0;
                            if ($tl_count == 0) {
                                if ($rand != $rand_pre) {
                                    $rand = '#DCECDC';
                                    $rand_pre = '#DCECDC';
                                } else {
                                    $rand = '#F5C87B';
                                }
                            }

                        @endphp


                        <tr style="border-bottom:1px solid black;background-color: {{ $rand }};">
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">{{ $hod['user_pk_no'] }}</td>




                            <td class="text-center">{{ $hod['user_fullname'] }}</td>
                            <td>{{ isset($project_cat_arr[$hod["category_lookup_pk_no"]]) ? $project_cat_arr[$hod["category_lookup_pk_no"]] : '' }}
                            </td>
                            <td>
                                @php
                                    $target_hod_month_target = isset($target_arr[$hod["user_pk_no"]]['month_target']) ? $target_arr[$hod["user_pk_no"]]['month_target'] : 0;

                                    $target_hod_inv_target = isset($inventory_target[$hod["user_pk_no"]]) ? $inventory_target[$hod["user_pk_no"]] : 0;
                                    if ($target_hod_month_target > $target_hod_inv_target) {
                                        $target_mm += isset($target_arr[$hod["user_pk_no"]]['month_target']) ? $target_arr[$hod["user_pk_no"]]['month_target'] : 0;
                                    } else {
                                        $target_mm += $target_hod_inv_target;
                                    }

                                @endphp
                                @if ($target_hod_month_target > $target_hod_inv_target)
                                    {{ isset($target_arr[$hod["user_pk_no"]]['month_target']) ? number_format($target_arr[$hod["user_pk_no"]]['month_target'],2) : '' }}
                                @else
                                    {{ isset($inventory_target[$hod["user_pk_no"]]) ? number_format($inventory_target[$hod["user_pk_no"]],2) : 0 }}
                                @endif
                            </td>

                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['no_of_sale']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['no_of_sale'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_qty']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_qty'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale'] : 0 }}
                            </td>
                            @php
                                $sold_value = isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value'] : 0;
                                $cancel_sold_value = isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale'] : 0;
                                $resultant_value = $sold_value - $cancel_sold_value;
                            @endphp
                            <td class="text-center">
                                {{ $resultant_value }}
                            </td>

                            @php
                                $result = 0;
                                $result1 = 0;
                                try {
                                    $result = isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value'] : 0;
                                    $target_hod_month_target = isset($target_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['month_target']) ? $target_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['month_target'] : 0;

                                    $target_hod_inv_target = isset($inventory_target[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]) ? $inventory_target[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]] : 0;
                                    if ($target_hod_month_target > $target_hod_inv_target) {
                                        $result1 = isset($target_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['month_target']) ? $target_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['month_target'] : 0;
                                    } else {
                                        $result1 = $target_hod_inv_target;
                                    }
                                    $result = ($result / $result1) * 100;
                                } catch (\Exception $e) {
                                    $result = 0;
                                    $result1 = 0;
                                }
                                $target_no_of_sale += isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['no_of_sale']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['no_of_sale'] : 0;
                                $target_sale_value += isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value'] : 0;
                                $target_sale_cancel += isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_qty']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_qty'] : 0;
                                $target_sale_cancel_value += isset($ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale']) ? $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale'] : 0;

                            @endphp

                            <td class="text-center">
                                {{ number_format($result, 2) }} %
                            </td>

                            <td>{{ isset($target_arr[$hod["user_pk_no"]]['ytd_target']) ?number_format( $target_arr[$hod["user_pk_no"]]['ytd_target'],2) : '' }}
                            </td>



                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]]['ytb_no_of_sale']) ? $ach_arr[$hod["user_pk_no"]]['ytb_no_of_sale'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]]['ytb_sold_value']) ? $ach_arr[$hod["user_pk_no"]]['ytb_sold_value'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]]['ytb_cancel_qty']) ? $ach_arr[$hod["user_pk_no"]]['ytb_cancel_qty'] : 0 }}
                            </td>
                            <td class="text-center">
                                {{ isset($ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale']) ? $ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale'] : 0 }}
                            </td>
                            @php
                                $sold_value = isset($ach_arr[$hod["user_pk_no"]]['ytb_sold_value']) ? $ach_arr[$hod["user_pk_no"]]['ytb_sold_value'] : 0;
                                $cancel_sold_value = isset($ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale']) ? $ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale'] : 0;
                                $resultant_value = $sold_value - $cancel_sold_value;
                            @endphp
                            <td class="text-center">
                                {{ $resultant_value }}
                            </td>

                            @php
                                $result = 0;
                                $result1 = 0;
                                try {
                                    $result = isset($ach_arr[$hod["user_pk_no"]]['ytb_sold_value']) ? $ach_arr[$hod["user_pk_no"]]['ytb_sold_value'] : 0;
                                    $result1 = isset($target_arr[$hod["user_pk_no"]]['ytd_target']) ? $target_arr[$hod["user_pk_no"]]['ytd_target'] : 0;
                                    $result = ($result / $result1) * 100;
                                } catch (\Exception $e) {
                                    $result = 0;
                                    $result1 = 0;
                                }

                                $target_ytd += isset($target_arr[$hod["user_pk_no"]]['ytd_target']) ? $target_arr[$hod["user_pk_no"]]['ytd_target'] : 0;
                                $target_ytd_no_of_sale += isset($ach_arr[$hod["user_pk_no"]]['ytb_no_of_sale']) ? $ach_arr[$hod["user_pk_no"]]['ytb_no_of_sale'] : 0;
                                $target_ytd_sale_value += isset($ach_arr[$hod["user_pk_no"]]['ytb_sold_value']) ? $ach_arr[$hod["user_pk_no"]]['ytb_sold_value'] : 0;
                                $target_ytd_sale_cancel += isset($ach_arr[$hod["user_pk_no"]]['ytb_cancel_qty']) ? $ach_arr[$hod["user_pk_no"]]['ytb_cancel_qty'] : 0;
                                $target_ytd_cancel_value += isset($ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale']) ? $ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale'] : 0;

                            @endphp

                            <td class="text-center">
                                {{ number_format($result, 2) }} %
                            </td>
                        </tr>

                        @php
                            $key++;
                            $tl_count++;
                            $i++;

                        @endphp
                    @endforeach
                    @php
                        $final = 0;
                        try {
                            $res = $target_sale_value - $target_sale_cancel_value;
                            $final = ($res / $target_mm) * 100;
                        } catch (\Exception $e) {
                            $final = 0;
                        }
                    @endphp
                      <tr>
                        <td colspan="4" class="text-center">Resign/Promoted</td>

                        <td class="text-left">
                            {{ $target_resize }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_no_of_sale']) ? $ach_arr['r_no_of_sale'] : 0 }}
                        </td>

                        <td class="text-center">
                            {{ isset($ach_arr['r_sold_value']) ? $ach_arr['r_sold_value'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_cancel_qty']) ? $ach_arr['r_cancel_qty'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_cancel_sale']) ? $ach_arr['r_cancel_sale'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_sold_value']) ? $ach_arr['r_sold_value'] : 0 }}
                        </td>


                        <td class="text-center">
                            {{ number_format($result, 2) }} %
                        </td>

                        <td class="text-center">
                            {{ $target_y_resize }}
                        </td>

                        <td class="text-center">
                            {{ isset($ach_arr['r_ytb_no_of_sale']) ? $ach_arr['r_ytb_no_of_sale'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_ytb_sold_value']) ? $ach_arr['r_ytb_sold_value'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_ytb_cancel_qty']) ? $ach_arr['r_ytb_cancel_qty'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_ytb_cancel_sale']) ? $ach_arr['r_ytb_cancel_sale'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($ach_arr['r_ytb_sold_value']) ? $ach_arr['r_ytb_sold_value'] : 0 }}
                        </td>



                        <td class="text-center">
                            {{ number_format($result, 2) }} %
                        </td>

                    </tr>
                    <tr>
                        <td colspan="4" class="text-center">
                            Total
                        </td>
                        <td class="text-center">{{ $target_mm+=$target_resize }}</td>
                        <td class="text-center">{{ $target_no_of_sale+ (isset($ach_arr['r_no_of_sale']) ? $ach_arr['r_no_of_sale'] : 0) }}</td>
                        <td class="text-center">{{ $target_sale_value+ (isset($ach_arr['r_ytb_sold_value']) ? $ach_arr['r_ytb_sold_value'] : 0)  }}</td>
                        <td class="text-center">{{ $target_sale_cancel+ (isset($ach_arr['r_cancel_qty']) ? $ach_arr['r_cancel_qty'] : 0)  }}</td>
                        <td class="text-center">{{ $target_sale_cancel_value+ (isset($ach_arr['r_cancel_sale']) ? $ach_arr['r_cancel_sale'] : 0)  }}</td>
                        <td class="text-center">{{ $target_sale_value - $target_sale_cancel_value- (isset($ach_arr['r_cancel_sale']) ? $ach_arr['r_cancel_sale'] : 0)  }}</td>

                        <td class="text-center">{{ number_format($final, 2) }} %</td>
                        <td class="text-center">{{ $target_ytd+=$target_y_resize }}</td>
                        <td class="text-center">{{ $target_ytd_no_of_sale+(isset($ach_arr['r_ytb_no_of_sale']) ? $ach_arr['r_ytb_no_of_sale'] : 0) }}</td>
                        <td class="text-center">{{ $target_ytd_sale_value+(isset($ach_arr['r_ytb_sold_value']) ? $ach_arr['r_ytb_sold_value'] : 0) }}</td>
                        <td class="text-center">{{ $target_ytd_sale_cancel+(isset($ach_arr['r_ytb_cancel_qty']) ? $ach_arr['r_ytb_cancel_qty'] : 0) }}</td>
                        <td class="text-center">{{ $target_ytd_cancel_value+(isset($ach_arr['r_ytb_cancel_sale']) ? $ach_arr['r_ytb_cancel_sale'] : 0) }}</td>
                        @php
                            $final = 0;
                            try {
                                $res = $target_ytd_sale_value - $target_ytd_cancel_value;
                                $final = ($res / $target_ytd) * 100;
                            } catch (\Exception $e) {
                                $final = 0;
                            }
                        @endphp
                        <td class="text-center">{{ $target_ytd_sale_value - $target_ytd_cancel_value }}</td>
                        <td class="text-center">{{ number_format($final, 2) }} %</td>

                    </tr>
                @else
                    <tr>
                        <td colspan="20" class="text-center">
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
            name: "sales_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
