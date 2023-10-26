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
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>
        {{-- <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button> --}}
    </div>

    <div class="box-body table-responsive">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead style="background-color:#6e9cce;color:white">
                <tr>
                    <th class="text-center" colspan="6">Team Details</th>
                    <th class="text-center" colspan="7">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="9" style="background-color:#6e9cce;color:white">Year to Date</th>
                </tr>
                <tr>
                    <th class="text-center">SL</th>
                    <th class="text-center">ID</th>
                    <th class="text-center">Sales Persion</th>
                    <th class="text-center">HOT</th>
                    <th class="text-center">DOJ</th>
                    <th class="text-center">DOR</th>
                    <th>Target</th>
                    <th>No of Land Sign</th>
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
                    <th style="background-color:#6e9cce;color:white">YTD No of Land Sign
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

                                <td class="text-center">{{ $member_name[1] }}</td>

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
                                    
                                @endphp


                                <td class="text-center">
                                    {{ isset($doj_arr[$member_name[0]]) ? date('d/m/Y', strtotime($doj_arr[$member_name[0]])) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($dor_arr[$member_name[0]]) ? date('d/m/Y', strtotime($dor_arr[$member_name[0]])) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ isset($target_mm[$member_name[0]]) ? $target_mm[$member_name[0]] : 0 }}
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
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['sold_value']) ? $ach_arr[$member_name[0]]['sold_value'] : 0 }}
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
                                <td class="text-center">
                                    {{ isset($ach_arr[$member_name[0]]['ytb_sold_value']) ? $ach_arr[$member_name[0]]['ytb_sold_value'] : 0 }}
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
                                    $target_total_ytd += isset($target_ytd[$member_name[0]]) ? $target_ytd[$member_name[0]] : 0;
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
                            @endphp





                        @endforeach
                    @endforeach
                    @php
                        $final = 0;
                        try {
                            $res = $target_sale_value - $target_sale_cancel_value;
                            $final = $res / $target_total_mm;
                        } catch (\Exception $e) {
                            $final = 0;
                        }
                    @endphp
                    <tr>
                        <td colspan="6" class="text-center">
                            Total
                        </td>
                        <td class="text-center">{{ $target_total_mm }}</td>
                        <td class="text-center">{{ $target_no_of_sale }}</td>
                        <td class="text-center">{{ $target_sale_value }}</td>
                        <td class="text-center">{{ $target_sale_cancel }}</td>
                        <td class="text-center">{{ $target_sale_cancel_value }}</td>
                        <td class="text-center">{{ $target_sale_value - $target_sale_cancel_value }}</td>

                        <td class="text-center">{{ number_format($final, 2) }} %</td>
                        <td class="text-center">{{ $target_total_ytd }}</td>
                        <td class="text-center">{{ $target_ytd_no_of_sale }}</td>
                        <td class="text-center">{{ $target_ytd_sale_value }}</td>
                        <td class="text-center">{{ $target_ytd_sale_cancel }}</td>
                        <td class="text-center">{{ $target_ytd_cancel_value }}</td>
                        @php
                            $final = 0;
                            try {
                                $res = $target_ytd_sale_value - $target_ytd_cancel_value;
                                $final = $res / $target_total_ytd;
                            } catch (\Exception $e) {
                                $final = 0;
                            }
                        @endphp
                        <td class="text-center">{{ $target_ytd_sale_value - $target_ytd_cancel_value }}</td>
                        <td class="text-center">{{ number_format($final, 2) }} %</td>

                    </tr>
                @else
                    <tr>
                        <td colspan="19" class="text-center">
                            No Data found
                        </td>
                    </tr>
                @endif

            </tbody>


        </table>
    </div>
</div>
