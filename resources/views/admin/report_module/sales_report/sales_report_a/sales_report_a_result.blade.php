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

    <div class="box-body table-responsive" id="tbl_search_result">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color:#6e9cce;color:white">
                <tr>
                    <th class="text-center" colspan="7">Team Details</th>
                    <th class="text-center" colspan="7">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="9" style="background-color:#6e9cce;color:white">Year to Date</th>
                </tr>
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">ID</th>
                    <th class="text-center">HOT</th>
                    <th class="text-center">Sales Persion</th>
                    <th class="text-center">Designation</th>
                    <th class="text-center">DOJ</th>
                    <th class="text-center">DOR</th>
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

                    $target_total_mm = 0;
                    $target_no_of_sale = 0;
                    $target_sale_value = 0;
                    $target_sale_cancel = 0;
                    $target_sale_cancel_value = 0;
                    $target_total_ytd = 0;
                    $target_ytd_no_of_sale = 0;
                    $target_ytd_sale_value = 0;
                    $target_ytd_sale_cancel = 0;
                    $target_ytd_cancel_value = 0;
                @endphp
                @if (!empty($tl_wise_mem_arr))
                    @foreach ($tl_wise_mem_arr as $member => $value)

                        @php
                            $tl = explode('_', $member);
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

                        @foreach ($value as $key => $row)
                            @php
                                $member_name = explode('_', $row);
                            @endphp
                            <tr style="border-bottom:1px solid black;background-color: {{ $rand }};">
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $member_name[0] }}</td>



                                @if ($tl_count == 0)
                                    <td class="text-center" rowspan="{{ count($value) }}">
                                        {{ $tl[1] }}
                                    </td>
                                @endif

                                @php
                                    $result = 0;
                                    try {
                                        $result = $report_data['con'][$mem_id] / $report_data['new_k1'][$mem_id];
                                    } catch (\Exception $e) {
                                        $result = 0;
                                    }
                                    $result = round($result * 100);
                                    $result1 = 0;
                                    try {
                                        $result1 = $report_data['t_con'][$mem_id] / $report_data['total_k1'][$mem_id];
                                    } catch (\Exception $e) {
                                        $result1 = 0;
                                    }
                                    $result1 = round($result1 * 100);

                                    if (isset($target_desgination[$member_name[0]])) {
                                        if ($target_desgination[$member_name[0]] == 'Sr. Consultant') {
                                            $sr_cons++;
                                            $target_sr_cons += isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                        } else {
                                            $cons++;
                                            $target_cons += isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                        }
                                    }

                                @endphp

                                <td class="text-center">{{ $member_name[1] }}</td>
                                <td> {{ isset($target_desgination[$member_name[0]]) ? $target_desgination[$member_name[0]] : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($doj_arr[$member_name[0]]) ? date('d/m/Y', strtotime($doj_arr[$member_name[0]])) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($dor_arr[$member_name[0]]) ? date('d/m/Y', strtotime($dor_arr[$member_name[0]])) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($target_mm[$member_name[0]]) && !empty($target_mm[$member_name[0]])? $target_mm[$member_name[0]] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['no_of_sale']) ? $ach_arr[$member_name[0]]['no_of_sale'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['sold_value']) ? $ach_arr[$member_name[0]]['sold_value'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['cancel_qty']) ? $ach_arr[$member_name[0]]['cancel_qty'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['cancel_sale']) ? $ach_arr[$member_name[0]]['cancel_sale'] : 0 }}
                                </td>
                                @php
                                    $sold_value = isset($ach_arr[$member_name[0]]['sold_value']) ? $ach_arr[$member_name[0]]['sold_value'] : 0;
                                    $cancel_sold_value = isset($ach_arr[$member_name[0]]['cancel_sale']) ? $ach_arr[$member_name[0]]['cancel_sale'] : 0;
                                    $resultant_value = $sold_value - $cancel_sold_value;
                                @endphp
                                <td class="text-center">
                                    {{ $resultant_value }}
                                </td>

                                @php
                                    $result = 0;
                                    $result1 = 0;
                                    try {
                                        $result = isset($ach_arr[$member_name[0]]['sold_value']) ? $ach_arr[$member_name[0]]['sold_value'] : 0;
                                        $result1 = isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                        $result = ($result / $result1) * 100;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                        $result1 = 0;
                                    }
                                    $target_total_mm += isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0;
                                    $target_no_of_sale += isset($ach_arr[$member_name[0]]['no_of_sale']) ? $ach_arr[$member_name[0]]['no_of_sale'] : 0;
                                    $target_sale_value += isset($ach_arr[$member_name[0]]['sold_value']) ? $ach_arr[$member_name[0]]['sold_value'] : 0;
                                    $target_sale_cancel += isset($ach_arr[$member_name[0]]['cancel_qty']) ? $ach_arr[$member_name[0]]['cancel_qty'] : 0;
                                    $target_sale_cancel_value += isset($ach_arr[$member_name[0]]['cancel_sale']) ? $ach_arr[$member_name[0]]['cancel_sale'] : 0;

                                @endphp

                                <td class="text-center">
                                    {{ number_format($result, 2) }} %
                                </td>

                                <td class="text-center">
                                    {{ isset($target_ytd[$member_name[0]]) ? $target_ytd[$member_name[0]] : 0 }}
                                </td>

                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['ytb_no_of_sale']) ? $ach_arr[$member_name[0]]['ytb_no_of_sale'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['ytb_sold_value']) ? $ach_arr[$member_name[0]]['ytb_sold_value'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['ytb_cancel_qty']) ? $ach_arr[$member_name[0]]['ytb_cancel_qty'] : 0 }}
                                </td>
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['ytb_cancel_sale']) ? $ach_arr[$member_name[0]]['ytb_cancel_sale'] : 0 }}
                                </td>
                                @php
                                    $sold_value = isset($ach_arr[$member_name[0]]['ytb_sold_value']) ? $ach_arr[$member_name[0]]['ytb_sold_value'] : 0;
                                    $cancel_sold_value = isset($ach_arr[$member_name[0]]['ytb_cancel_sale']) ? $ach_arr[$member_name[0]]['ytb_cancel_sale'] : 0;
                                    $resultant_value = $sold_value - $cancel_sold_value;
                                @endphp
                                <td class="text-center">
                                    {{ $resultant_value }}
                                </td>
                                @php
                                    $result = 0;
                                    $result1 = 0;
                                    try {
                                        $result = isset($ach_arr[$member_name[0]]['ytb_sold_value']) ? $ach_arr[$member_name[0]]['ytb_sold_value'] : 0;
                                        $result1 = isset($target_ytd[$member_name[0]]) ? $target_ytd[$member_name[0]] : 0;
                                        $result = ($result / $result1) * 100;
                                    } catch (\Exception $e) {
                                        $result = 0;
                                        $result1 = 0;
                                    }
                                    $target_total_ytd += isset($target_ytd[$member_name[0]]) && !empty($target_ytd[$member_name[0]])? $target_ytd[$member_name[0]] : 0;
                                    $target_ytd_no_of_sale += isset($ach_arr[$member_name[0]]['ytb_no_of_sale']) ? $ach_arr[$member_name[0]]['ytb_no_of_sale'] : 0;
                                    $target_ytd_sale_value += isset($ach_arr[$member_name[0]]['ytb_sold_value']) ? $ach_arr[$member_name[0]]['ytb_sold_value'] : 0;
                                    $target_ytd_sale_cancel += isset($ach_arr[$member_name[0]]['ytb_cancel_qty']) ? $ach_arr[$member_name[0]]['ytb_cancel_qty'] : 0;
                                    $target_ytd_cancel_value += isset($ach_arr[$member_name[0]]['ytb_cancel_sale']) ? $ach_arr[$member_name[0]]['ytb_cancel_sale'] : 0;

                                @endphp

                                <td class="text-center">
                                    {{ number_format($result, 2) }} %
                                </td>
                            </tr>

                            @php
                                $key++;
                                $tl_count++;
                                $i++;
                                //     $total_new_k1 += isset($report_data['new_k1'][$mem_id]) ? $report_data['new_k1'][$mem_id] : 0;
                                //     $total_k1 += isset($report_data['total_k1'][$mem_id]) ? $report_data['total_k1'][$mem_id] : 0;
                                //     $total_prority += isset($report_data['priority'][$mem_id]) ? $report_data['priority'][$mem_id] : 0;
                                //     $total_m_con += isset($report_data['con'][$mem_id]) ? $report_data['con'][$mem_id] : 0;
                                //     $total_y_con += isset($report_data['t_con'][$mem_id]) ? $report_data['t_con'][$mem_id] : 0;
                                //     $total_new_lead += isset($report_data['new_lead'][$mem_id]) ? $report_data['new_lead'][$mem_id] : 0;
                                //     $total_all_lead += isset($report_data['total_new_lead'][$mem_id]) ? $report_data['total_new_lead'][$mem_id] : 0;
                            @endphp





                        @endforeach


                    @endforeach

                    <tr>
                        <td colspan="7" class="text-center">Resigned</td>
                        <td>{{ isset($resize_arr['target_month']) ? $resize_arr['target_month'] : 0 }}</td>
                        <td>{{ isset($resize_arr['total_sale']) ? $resize_arr['total_sale'] : 0 }}</td>

                        <td>{{ isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] : 0 }}</td>
                        <td>{{ isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] : 0 }}</td>
                        <td>{{ isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] : 0 }}</td>
                        @php
                            $sold_value = isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] : 0;
                            $cancel_sold_value = isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] : 0;
                            $resultant_value = $sold_value - $cancel_sold_value;
                            $result = 0;
                            $result1 = isset($resize_arr['target_month']) ? $resize_arr['target_month'] : 0;
                            try {
                                $result = ($sold_value / $result1) * 100;
                            } catch (\Exception $e) {
                                $result = 0;
                                $result1 = 0;
                            }
                        @endphp
                        <td>{{ $resultant_value }}</td>
                        <td>{{ number_format($result, 2) }}</td>
                        <td>{{ isset($resize_arr['target_ytd']) ? $resize_arr['target_ytd'] : 0 }}</td>
                        <td>{{ isset($resize_arr['t_total_sale']) ? $resize_arr['t_total_sale'] : 0 }}</td>
                        <td>{{ isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] : 0 }}</td>
                        <td>{{ isset($resize_arr['t_cancel_qty']) ? $resize_arr['t_cancel_qty'] : 0 }}</td>
                        <td>{{ isset($resize_arr['t_cancel_sale']) ? $resize_arr['t_cancel_sale'] : 0 }}</td>

                        @php
                            $target_total_mm += isset($resize_arr['target_month']) ? $resize_arr['target_month'] : 0;
                            $target_no_of_sale += isset($resize_arr['total_sale']) ? $resize_arr['total_sale'] : 0;
                            $target_sale_value += isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] : 0;
                            $target_sale_cancel += isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] : 0;
                            $target_sale_cancel_value += isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] : 0;

                            $target_total_ytd += isset($resize_arr['target_ytd']) ? $resize_arr['target_ytd'] : 0;
                            $target_ytd_no_of_sale += isset($resize_arr['t_total_sale']) ? $resize_arr['t_total_sale'] : 0;
                            $target_ytd_sale_value += isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] : 0;
                            $target_ytd_sale_cancel += isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] : 0;
                            $target_ytd_cancel_value += isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] : 0;

                        @endphp

                        @php
                            $sold_value = isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] : 0;
                            $cancel_sold_value = isset($resize_arr['t_cancel_sale']) ? $resize_arr['t_cancel_sale'] : 0;
                            $resultant_value = $sold_value - $cancel_sold_value;

                            $result = 0;
                            $result1 = isset($resize_arr['target_ytd']) ? $resize_arr['target_ytd'] : 0;
                            try {
                                $result = ($sold_value / $result1) * 100;
                            } catch (\Exception $e) {
                                $result = 0;
                                $result1 = 0;
                            }
                        @endphp
                        <td>{{ $resultant_value }}</td>
                        <td>{{ number_format($result, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-center"> Additional</td>
                        <td id="addititonal_target"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="7" class="text-center"> Grand Total</td>
                        <td>{{ $target_total_mm }}</td>
                        <td>{{ $target_no_of_sale }}</td>
                        <td>{{ $target_sale_value }}</td>
                        <td>{{ $target_sale_cancel }}</td>
                        <td>{{ $target_sale_cancel_value }}</td>
                        <td>{{ $target_sale_value - $target_sale_cancel_value }}</td>
                        @php
                            $final = 0;
                            try {
                                $res = $target_sale_value - $target_sale_cancel_value;
                                $final = ($res / $target_total_mm) * 100;
                            } catch (\Exception $e) {
                                $final = 0;
                            }
                        @endphp
                        <td class="text-center">{{ number_format($final, 2) }} %</td>
                        <td>{{ $target_total_ytd }}</td>
                        <td>{{ $target_ytd_no_of_sale }}</td>
                        <td>{{ $target_ytd_sale_value }}</td>
                        <td>{{ $target_ytd_sale_cancel }}</td>
                        <td>{{ $target_ytd_cancel_value }}</td>
                        <td>{{ $target_ytd_sale_value - $target_ytd_cancel_value }}</td>
                        @php
                            $final = 0;
                            try {
                                $res = $target_ytd_sale_value - $target_ytd_cancel_value;
                                $final = ($res / $target_total_ytd) * 100;
                            } catch (\Exception $e) {
                                $final = 0;
                            }
                        @endphp
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
        <br>
        <br>
        <br>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#6e9cce;color:white">
                <tr>
                    <th>Sl</th>
                    <th>Project Name</th>
                    <th>No of Flat</th>
                    <th>Inventory Value</th>
                    <th>Remaining Sales Period</th>
                    <th>Inventory Per Month Target</th>
                    <th>Sales unit</th>
                    <th>Cancel unit</th>
                    <th>Remaining unit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $number_of_flat = 0;
                    $inventory_value = 0;
                    $remaining_sales_period = 0;
                    $target_per_month = 0;
                    $total_sale_unit = 0;
                    $total_cancel_unit = 0;
                    $total_rem_unit = 0;
                    $total_howeverManyMonths=0;
                    $total_per_month_target=0;
                    $total_target_per_month=0;

                @endphp
                @if (!empty($project_name))
                    @foreach ($project_name as $row)

                        {{-- @php
                            $date1 = date('Y-m-d');
                            $date2 = $row->target_closing_month;
                            $d1 = new DateTime($date2);
                            $d2 = new DateTime($date1);
                            $Months = $d2->diff($d1);
                            $howeverManyMonths = $Months->y * 12 + $Months->m;
                            $per_month_target = 0;
                            $project_sale_value = isset($project_wise_sale_value[$row->lookup_pk_no]) ? $project_wise_sale_value[$row->lookup_pk_no] : 0;
                            $project_sale_count = isset($project_wise_sale_count[$row->lookup_pk_no]) ? $project_wise_sale_count[$row->lookup_pk_no] : 0;
                            $project_sale_cancel = isset($project_wise_sale_cancel[$row->lookup_pk_no]) ? $project_wise_sale_cancel[$row->lookup_pk_no] : 0;
                            try {
                                $per_month_target = $row->inventory_value / $howeverManyMonths;
                            } catch (\Exception $e) {
                                $per_month_target = 0;
                            }
                        @endphp --}}

                        @php
                            $per_month_target = 0;
                            $inventory = isset($total_sell_arr[$row->project_id]['share_unit']) ? $total_sell_arr[$row->project_id]['share_unit'] : 0;
                            $cancel_u = isset($total_sell_arr[$row->project_id]['cancel_unit']) ? $total_sell_arr[$row->project_id]['cancel_unit'] : 0;
                            $t_sale = isset($total_sell_arr[$row->project_id]['t_sale']) ? $total_sell_arr[$row->project_id]['t_sale'] : 0;
                            $number_of_flat += $inventory;
                            $inventory_value += isset($total_sell_arr[$row->project_id]['inventory_value']) ? $total_sell_arr[$row->project_id]['inventory_value'] : 0;
                            $total_sale_unit += $t_sale;
                            $rem_unit = $inventory + $cancel_u - $t_sale;
                            $total_cancel_unit += $cancel_u;
                            $total_rem_unit += $rem_unit;
                            $date1 = date('Y-m-d');
                            $date2 = $row->target_closing_month;
                            $d1 = new DateTime($date2);
                            $d2 = new DateTime($date1);
                            $Months = $d2->diff($d1);
                            $howeverManyMonths = $Months->y * 12 + $Months->m;
                            try {
                                $per_month_target = (isset($total_sell_arr[$row->project_id]['inventory_value']) ? $total_sell_arr[$row->project_id]['inventory_value'] : 0) / $howeverManyMonths;
                            } catch (\Exception $e) {
                                $per_month_target = 0;
                            }
                            $total_howeverManyMonths+=$howeverManyMonths;
                            $total_per_month_target+=$per_month_target;


                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ isset($total_sell_arr[$row->project_id]['project_name'])? $total_sell_arr[$row->project_id]['project_name']: 0 }}
                            </td>
                            <td>{{ isset($total_sell_arr[$row->project_id]['share_unit']) ? $total_sell_arr[$row->project_id]['share_unit'] : 0 }}
                            </td>
                            <td>{{ isset($total_sell_arr[$row->project_id]['inventory_value'])? $total_sell_arr[$row->project_id]['inventory_value']: 0 }}
                            </td>
                            <td>{{ $howeverManyMonths }}</td>
                            <td>{{ number_format($per_month_target, 2) }}</td>
                            <td> {{ isset($total_sell_arr[$row->project_id]['t_sale']) ? $total_sell_arr[$row->project_id]['t_sale'] : 0 }}
                            </td>
                            <td> {{ isset($total_sell_arr[$row->project_id]['cancel_unit'])? $total_sell_arr[$row->project_id]['cancel_unit']: 0 }}
                            </td>
                            <td> {{ $rem_unit }}</td>
                            @php
                                // $number_of_flat += $row->number_of_inventory;
                                // $inventory_value += $row->inventory_value;
                                $remaining_sales_period += $howeverManyMonths;
                                $total_target_per_month += $per_month_target;
                                // $total_sale_unit += $project_sale_count;
                                // $total_cancel_unit += $project_sale_cancel;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-center" colspan="2">Grand Total</td>
                        <td>{{ $number_of_flat }}</td>
                        <td>{{ $inventory_value }}</td>
                        <td>{{ $remaining_sales_period }}</td>
                        <td>{{ $total_target_per_month }}</td>
                        <td>{{ $total_sale_unit }}</td>
                        <td>{{ $total_cancel_unit }}</td>
                        <td>{{ $total_rem_unit }}</td>
                    </tr>

                @else
                    <tr>
                        <td colspan="9" class="text-center">
                            No Data found
                        </td>
                    </tr>
                @endif
            </tbody>
            <input type="hidden" id="inventory_value" value="{{ $inventory_value }}">
            <input type="hidden" id="target_total_mm" value="{{ $target_total_mm }}">
        </table>
        <br>
        <br>
        <br>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#6e9cce;color:white">
                <tr>
                    <th>Sl</th>
                    <th>No Of Position</th>
                    <th>Target Per Employee</th>
                    <th>No of Employee</th>
                    <th>Target</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $target_per_sr_cons = 0;
                    $target_per_cons = 0;
                    try {
                        $target_per_sr_cons = $target_sr_cons / $sr_cons;
                    } catch (\Exception $e) {
                        $target_per_sr_cons = 0;
                    }
                    try {
                        $target_per_cons = $target_cons / $cons;
                    } catch (\Exception $e) {
                        $target_per_cons = 0;
                    }
                @endphp
                <tr>
                    <td>1</td>
                    <td>Sr. Consultant</td>
                    <td>{{ $target_per_sr_cons }}</td>
                    <td>{{ $sr_cons }}</td>
                    <td>{{ $target_sr_cons }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Consultant</td>
                    <td> {{ $target_per_cons }} </td>
                    <td>{{ $cons }}</td>
                    <td> {{ $target_cons }} </td>
                </tr>
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{ $target_per_sr_cons + $target_per_cons }}</td>
                    <td>{{ $sr_cons + $cons }}</td>
                    <td>{{ $target_sr_cons + $target_cons }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var target_total_mm = $("#target_total_mm").val();
        var inventory_value = $("#inventory_value").val();
        if (inventory_value > target_total_mm) {
            var additional = inventory_value - target_total_mm;
            $("#addititonal_target").html(additional);

        }

    });

    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "sales_report.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
