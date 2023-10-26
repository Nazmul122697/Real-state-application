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
                    <th class="text-center" rowspan="2">Category</th>
                    <th class="text-center" rowspan="2">HoT</th>
                    <th class="text-center" rowspan="2">ID</th>
                    <th class="text-center" rowspan="2">Sales Person Name</th>
                    <th rowspan="2">{{ isset($month) ? $month : 'Current Month' }}</th>
                    <th class="text-center" colspan="9">K</th>
                    <th rowspan="2">Year to {{ isset($month) ? $month : 'Current Month' }}</th>
                    <th class="text-center" colspan="9">K</th>
                    </th>
                </tr>
                <tr>
                    <th>ACD</th>
                    <th>CRE</th>
                    <th>Digital Marketing</th>
                    <th>DSI</th>
                    <th>Existing Customer </th>
                    <th>Fair</th>
                    <th>Hotline (Web,FB,mail,U/A)</th>
                    <th>IR</th>
                    <th>Others</th>

                    <th>ACD</th>
                    <th>CRE</th>
                    <th>Digital Marketing</th>
                    <th>DSI</th>
                    <th>Existing Customer </th>
                    <th>Fair</th>
                    <th>Hotline (Web,FB,mail,U/A)</th>
                    <th>IR</th>
                    <th>Others</th>
                </tr>
            </thead>
            <tbody>
                @php
                    
                @endphp
                @if (!empty($tl_arr))
                    @php
                        $i = 0;
                        $total_mm = 0;
                        $total_mm_acd = 0;
                        $total_mm_cre = 0;
                        $total_mm_dg = 0;
                        $total_mm_ex = 0;
                        $total_mm_ht = 0;
                        $total_mm_ir = 0;
                        $total_mm_other = 0;
                        
                        $total_yy_total = 0;
                        
                        $total_yy_acd = 0;
                        $total_yy_cre = 0;
                        $total_yy_dg = 0;
                        $total_yy_ex = 0;
                        $total_yy_ht = 0;
                        $total_yy_ir = 0;
                        $total_yy_other = 0;
                        $prev = '';
                    @endphp
                    @foreach ($tl_arr as $key => $value)
                        @foreach ($value as $key => $data)
                            @php
                                
                                $details = explode('_', $data);
                            @endphp
                            <tr>
                                @if ($i == 0)
                                    <td rowspan="{{ $row_count }}">{{ $name_of_project }}</td>
                                @endif
                                @if ($prev != $details[2])
                                    <td rowspan="{{ count($tl_arr[$details[3]]) }}">{{ $details[2] }}</td>
                                    @php
                                        $prev = $details[2];
                                    @endphp
                                @endif
                                <td>{{ $details[0] }}</td>
                                <td>{{ $details[1] }}</td>
                                <td>{{ isset($result_arr[$details[0]]['total']) ? $result_arr[$details[0]]['total'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['acd']) ? $result_arr[$details[0]]['acd'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['cre']) ? $result_arr[$details[0]]['cre'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['dg']) ? $result_arr[$details[0]]['dg'] : 0 }}
                                </td>
                                <td>0
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['ex']) ? $result_arr[$details[0]]['ex'] : 0 }}
                                </td>
                                <td>0
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['hotine']) ? $result_arr[$details[0]]['hotine'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['ir']) ? $result_arr[$details[0]]['ir'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['other']) ? $result_arr[$details[0]]['other'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_total']) ? $result_arr[$details[0]]['t_total'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_acd']) ? $result_arr[$details[0]]['t_acd'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_cre']) ? $result_arr[$details[0]]['t_cre'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_dg']) ? $result_arr[$details[0]]['t_dg'] : 0 }}
                                </td>
                                <td>0
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_ex']) ? $result_arr[$details[0]]['t_ex'] : 0 }}
                                </td>
                                <td>0
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_hotine']) ? $result_arr[$details[0]]['t_hotine'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_ir']) ? $result_arr[$details[0]]['t_ir'] : 0 }}
                                </td>
                                <td>{{ isset($result_arr[$details[0]]['t_other']) ? $result_arr[$details[0]]['t_other'] : 0 }}
                                </td>
                            </tr>
                            @php
                                $i++;
                                $total_mm += isset($result_arr[$details[0]]['total']) ? $result_arr[$details[0]]['total'] : 0;
                                $total_mm_acd += isset($result_arr[$details[0]]['acd']) ? $result_arr[$details[0]]['acd'] : 0;
                                $total_mm_cre += isset($result_arr[$details[0]]['cre']) ? $result_arr[$details[0]]['cre'] : 0;
                                $total_mm_dg += isset($result_arr[$details[0]]['dg']) ? $result_arr[$details[0]]['dg'] : 0;
                                $total_mm_ex += isset($result_arr[$details[0]]['ex']) ? $result_arr[$details[0]]['ex'] : 0;
                                $total_mm_ht += isset($result_arr[$details[0]]['hotine']) ? $result_arr[$details[0]]['hotine'] : 0;
                                $total_mm_ir += isset($result_arr[$details[0]]['ir']) ? $result_arr[$details[0]]['ir'] : 0;
                                $total_mm_other += isset($result_arr[$details[0]]['other']) ? $result_arr[$details[0]]['other'] : 0;
                                
                                $total_yy_total += isset($result_arr[$details[0]]['t_total']) ? $result_arr[$details[0]]['t_total'] : 0;
                                
                                $total_yy_acd += isset($result_arr[$details[0]]['t_acd']) ? $result_arr[$details[0]]['t_acd'] : 0;
                                $total_yy_cre += isset($result_arr[$details[0]]['t_cre']) ? $result_arr[$details[0]]['t_cre'] : 0;
                                $total_yy_dg += isset($result_arr[$details[0]]['t_dg']) ? $result_arr[$details[0]]['t_dg'] : 0;
                                $total_yy_ex += isset($result_arr[$details[0]]['t_ex']) ? $result_arr[$details[0]]['t_ex'] : 0;
                                $total_yy_ht += isset($result_arr[$details[0]]['t_hotine']) ? $result_arr[$details[0]]['t_hotine'] : 0;
                                $total_yy_ir += isset($result_arr[$details[0]]['t_ir']) ? $result_arr[$details[0]]['t_ir'] : 0;
                                $total_yy_other += isset($result_arr[$details[0]]['t_other']) ? $result_arr[$details[0]]['t_other'] : 0;
                            @endphp
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center">
                            Grand Total
                        </td>
                        <td class="text-center">
                            {{ $total_mm }}

                        </td>
                        <td class="text-center">
                            {{ $total_mm_acd }}

                        </td>
                        <td class="text-center">
                            {{ $total_mm_cre }}

                        </td>
                        <td class="text-center">
                            {{ $total_mm_dg }}

                        </td>
                        <td>0</td>
                        <td class="text-center">
                            {{ $total_mm_ex }}

                        </td>
                        <td>0</td>
                        <td class="text-center">
                            {{ $total_mm_ht }}

                        </td>
                        <td class="text-center">
                            {{ $total_mm_ir }}

                        </td>
                        <td class="text-center">
                            {{ $total_mm_other }}

                        </td>
                        <td class="text-center">
                            {{ $total_yy_total }}

                        </td>
                        <td class="text-center">
                            {{ $total_yy_acd }}

                        </td>
                        <td class="text-center">
                            {{ $total_yy_cre }}

                        </td>

                        <td class="text-center">
                            {{ $total_yy_dg }}

                        </td>
                        <td>0</td>
                        <td class="text-center">
                            {{ $total_yy_ex }}

                        </td>
                        <td>0</td>
                        <td class="text-center">
                            {{ $total_yy_ht }}

                        </td>
                        <td class="text-center">
                            {{ $total_yy_ir }}

                        </td>

                        <td class="text-center">
                            {{ $total_yy_other }}

                        </td>

                    </tr>
                @else
                    <tr>
                        <td colspan="24" class="text-center">No Data found</td>
                    </tr>
                @endif


            </tbody>


        </table>
    </div>
</div>
<script type="text/javascript">
    function ExportCSV() {
        TableToExcel.convert(document.getElementById("tbl_search_result"), {
            name: "k_classification_result.xlsx",
            sheet: {
                name: "Sheet1"
            }
        });
    }
</script>
