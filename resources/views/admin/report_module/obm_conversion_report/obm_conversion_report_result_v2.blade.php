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
            <thead style="background-color:#5f5fd4;color:white">


                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Category</th>
                    <th class="text-center" colspan="8">{{ isset($month) ? $month : 'January' }} -
                        {{ isset($target_year1) ? $target_year1 : '2021' }}</th>
                    <th class="text-center" colspan="8" style="background-color:#a97e2f;color:white">Year to Date
                    </th>
                </tr>
                <tr>

                    <th>New Lead</th>
                    <th>New K</th>
                    <th>Total K</th>
                    <th>Prority</th>
                    <th>Sales</th>
                    <th>Lead to K Con.</th>
                    <th>K to Sale Con.</th>
                    <th>Lead to Sales Con.</th>
                    <th style="background-color:#a97e2f;color:white">Total New Lead</th>
                    <th style="background-color:#a97e2f;color:white">Total New K</th>
                    <th style="background-color:#a97e2f;color:white">Sales</th>
                    <th style="background-color:#a97e2f;color:white">Lead to K Con.</th>
                    <th style="background-color:#a97e2f;color:white">K to Sale Con.</th>
                    <th style="background-color:#a97e2f;color:white">Lead to Sales Con.</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    $rand = '#F5C87B';
                    $rand_pre = '';
                    $newk = 0;
                    $totalk = 0;
                    $prority = 0;
                    $sales = 0;
                    $tnewk = 0;
                    $new_lead = 0;
                    $total_new_lead = 0;
                    $total_new_k1 = 0;

                @endphp
                @if (!empty($category_name))
                    @foreach ($category_name as $category)
                        @php
                            $result = 0;
                            try {
                                $total_k1 = isset($report_data['new_lead'][$category->lookup_pk_no]) ? $report_data['new_lead'][$category->lookup_pk_no] : 0;
                                $sales = isset($report_data['new_k1'][$category->lookup_pk_no]) ? $report_data['new_k1'][$category->lookup_pk_no] : 0;
                                $result = ($sales / $total_k1) * 100;
                            } catch (\Exception $e) {
                                $result = 0;
                            }
                            $result1 = 0;
                            try {
                                $total_k1 = isset($report_data['new_total_k1'][$category->lookup_pk_no]) ? $report_data['new_total_k1'][$category->lookup_pk_no] : 0;
                                $sales = isset($report_data['con'][$category->lookup_pk_no]) ? $report_data['con'][$category->lookup_pk_no] : 0;
                                $result1 = ($sales / $total_k1) * 100;
                            } catch (\Exception $e) {
                                $result1 = 0;
                            }
                            $result2 = 0;
                            try {
                                $total_k1 = isset($report_data['new_lead'][$category->lookup_pk_no]) ? $report_data['new_lead'][$category->lookup_pk_no] : 0;
                                $sales = isset($report_data['con'][$category->lookup_pk_no]) ? $report_data['con'][$category->lookup_pk_no] : 0;
                                $result2 = ($sales / $total_k1) * 100;
                            } catch (\Exception $e) {
                                $result2 = 0;
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->lookup_name }}</td>
                            <td>{{ isset($report_data['new_lead'][$category->lookup_pk_no]) ? $report_data['new_lead'][$category->lookup_pk_no] : 0 }}
                            </td>

                            <td>{{ isset($report_data['new_k1'][$category->lookup_pk_no]) ? $report_data['new_k1'][$category->lookup_pk_no] : 0 }}
                            </td>
                            <td>{{ isset($report_data['new_total_k1'][$category->lookup_pk_no]) ? $report_data['new_total_k1'][$category->lookup_pk_no] : 0  }}
                            </td>
                            <td>{{ isset($report_data[$category->lookup_pk_no]['priority']) ? $report_data[$category->lookup_pk_no]['priority'] : 0 }}
                            </td>
                            <td>{{ isset($report_data['con'][$category->lookup_pk_no]) ? $report_data['con'][$category->lookup_pk_no] : 0 }}
                            </td>
                            <td>{{ number_format($result, 2) }} %</td>
                            <td>{{ number_format($result1, 2) }} %</td>
                            <td>{{ number_format($result2, 2) }} %</td>
                            <td>{{ isset($report_data[$category->lookup_pk_no]['total_new_lead']) ? $report_data[$category->lookup_pk_no]['total_new_lead'] : 0 }}
                            </td>
                            <td>{{ isset($report_data[$category->lookup_pk_no]['total_k1']) ? $report_data[$category->lookup_pk_no]['total_k1'] : 0 }}
                            </td>
                            <td>{{ isset($report_data[$category->lookup_pk_no]['t_con']) ? $report_data[$category->lookup_pk_no]['t_con'] : 0 }}
                            </td>
                            @php
                                $result2 = 0;
                                try {
                                    $total_k1 = isset($report_data[$category->lookup_pk_no]['total_new_lead']) ? $report_data[$category->lookup_pk_no]['total_new_lead'] : 0;
                                    $sales = isset($report_data[$category->lookup_pk_no]['total_k1']) ? $report_data[$category->lookup_pk_no]['total_k1'] : 0;
                                    $result2 = ($sales / $total_k1) * 100;
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                            @endphp
                            <td>{{ number_format($result2, 2) }} %
                            </td>
                            <td>{{ number_format($result1, 2) }} %</td>
                            @php
                                $result2 = 0;
                                try {
                                    $total_k1 = isset($report_data[$category->lookup_pk_no]['total_new_lead']) ? $report_data[$category->lookup_pk_no]['total_new_lead'] : 0;
                                    $sales = isset($report_data[$category->lookup_pk_no]['t_con']) ? $report_data[$category->lookup_pk_no]['t_con'] : 0;
                                    $result2 = ($sales / $total_k1) * 100;
                                } catch (\Exception $e) {
                                    $result2 = 0;
                                }
                            @endphp
                            <td>{{ number_format($result2, 2) }} %</td>
                        </tr>
                        @php
                            $total_new_lead += isset($report_data[$category->lookup_pk_no]['total_new_lead']) ? $report_data[$category->lookup_pk_no]['total_new_lead'] : 0;
                            $newk = array_sum($report_data['new_k1']);
                            $new_lead = array_sum($report_data['new_lead']);
                            $totalk += isset($report_data[$category->lookup_pk_no]['total_k1']) ? $report_data[$category->lookup_pk_no]['total_k1'] : 0;
                            $prority += isset($report_data[$category->lookup_pk_no]['priority']) ? $report_data[$category->lookup_pk_no]['priority'] : 0;
                            $sales = array_sum($report_data['con']);
                            $tnewk += isset($report_data[$category->lookup_pk_no]['t_con']) ? $report_data[$category->lookup_pk_no]['t_con'] : 0;
                            $total_new_k1 += isset($report_data['new_total_k1'] [$category->lookup_pk_no]) ? $report_data['new_total_k1'][$category->lookup_pk_no] : 0;
                        @endphp

                    @endforeach

                    @php
                        $result = 0;
                        try {
                            $result = ($newk / $new_lead) * 100;
                        } catch (\Exception $e) {
                            $result = 0;
                        }
                        $result1 = 0;
                        try {
                            $result1 = ($sales / $newk) * 100;
                        } catch (\Exception $e) {
                            $result1 = 0;
                        }
                        $result2 = 0;
                        try {
                            $result2 = ($sales / $new_lead) * 100;
                        } catch (\Exception $e) {
                            $result2 = 0;
                        }
                    @endphp
                    <tr>
                        <td colspan='2' class="text-center">Total</td>
                        <td>{{ $new_lead }}</td>
                        <td>{{ $newk }}</td>
                        <td>{{ $total_new_k1 }}</td>

                        <td>{{ $prority }}</td>
                        <td>{{ $sales }}</td>
                        <td>{{ number_format($result, 2) }} %</td>
                        <td>{{ number_format($result1, 2) }} %</td>
                        <td>{{ number_format($result2, 2) }} %</td>
                        @php
                            $result = 0;
                            try {
                                $result = ($totalk / $total_new_lead) * 100;
                            } catch (\Exception $e) {
                                $result = 0;
                            }
                            $result1 = 0;
                            try {
                                $result1 = ($tnewk / $totalk) * 100;
                            } catch (\Exception $e) {
                                $result1 = 0;
                            }
                            $result2 = 0;
                            try {
                                $result2 = ($tnewk / $total_new_lead) * 100;
                            } catch (\Exception $e) {
                                $result2 = 0;
                            }
                        @endphp
                        <td>{{ $total_new_lead }}</td>
                        <td>{{ $totalk }}</td>
                        <td>{{ $tnewk }}</td>
                        <td>{{ number_format($result, 2) }} %</td>
                        <td>{{ number_format($result1, 2) }} %</td>
                        <td>{{ number_format($result2, 2) }} %</td>

                    </tr>
                @else
                    <tr>
                        <td rowspan="12">No Data Found</td>
                    </tr>

                @endif

            </tbody>


        </table>
    </div>
</div>
