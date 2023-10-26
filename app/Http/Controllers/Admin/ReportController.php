<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LookupData;
use App\ReportSetup;
use App\TeamAssign;
use App\YearSetup;
use GuzzleHttp\Promise\EachPromise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_groups = LookupData::where('lookup_type', 1)->orderBy('lookup_name')->get();
        $user_group = [];
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }

        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->groupBy('lookup_name')->get();

        $project_cat = $project_area = $project_name = $project_size = $hotline = $billboards = $project_boards = $flyers = $fnfs = array();
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 2) {
                $digital_mkt[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 3) {
                $hotline[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 4) {
                $project_cat[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 5) {
                $project_area[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 6) {
                $project_name[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 7) {
                $project_size[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 10) {
                $ocupations[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 11) {
                $press_adds[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 12) {
                $billboards[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 13) {
                $project_boards[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 14) {
                $flyers[$value->lookup_pk_no] = $value->lookup_name;
            }

            if ($value->lookup_type == 15) {
                $fnfs[$value->lookup_pk_no] = $value->lookup_name;
            }
        }
        $lead_type = config('static_arrays.lead_type');
        return view('admin.report_module.search_engine', compact('project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_stage_arr', 'user_group', 'lead_type'));
    }

    public function serch_result_query($request)
    {
        set_time_limit(0);

        ini_set('memory_limit','-1');

        ini_set('max_execution_time',0);

        $sql_cond = (trim($request->txt_customer_name) != "") ? " where customer_firstname like '%" . trim($request->txt_customer_name) . "%'" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= (trim($request->txt_mobile_no) != "") ? " $clause (phone1 like '%" . trim($request->txt_mobile_no) . "%' or phone2 like '%" . trim($request->txt_mobile_no) . "%')" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= (trim($request->txt_email) != "") ? " $clause email_id like '%" . trim($request->txt_email) . "%'" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbOccupation != "") ? " $clause occupation_pk_no=$request->cmbOccupation" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbOrganization != "") ? " $clause organization_pk_no=$request->cmbOrganization" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbCategory != "") ? " $clause project_category_pk_no=$request->cmbCategory" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbArea != "") ? " $clause project_area_pk_no=$request->cmbArea" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbProjectName != "") ? " $clause Project_pk_no=$request->cmbProjectName" : "";
        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbSize != "") ? " $clause project_size_pk_no=$request->cmbSize" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmbUserGroup > 0) ? " $clause source_auto_usergroup_pk_no=$request->cmbUserGroup" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $sql_cond .= ($request->cmb_stage > 0) ? " $clause lead_current_stage=$request->cmb_stage" : "";

        //SBU filtering
        $clause = ($sql_cond != "") ? " and" : " where";
        $lead_type_array =$request->lead_type;
        $sql_cond .= ($request->lead_type != "") ? " $clause lead_type in (" . implode(',', $lead_type_array) . ")" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $entry_date = (trim($request->txt_entry_date) != "") ? date("Y-m-d", strtotime($request->txt_entry_date)) : "";
        $entry_date_to = (trim($request->txt_entry_date_to) != "") ? date("Y-m-d", strtotime($request->txt_entry_date_to)) : "";
        $sql_cond .= ($entry_date != "") ? " $clause a.created_at >='$entry_date' and a.created_at <='$entry_date_to'" : "";


        //current date
        $clause = ($sql_cond != "") ? " and" : " where";
        $current_stage_date = (trim($request->txt_current_date) != "") ? date("Y-m-d", strtotime($request->txt_current_date)) : "";
        $current_stage_date_to = (trim($request->txt_current_date_to) != "") ? date("Y-m-d", strtotime($request->txt_current_date_to)) : "";
        $sql_cond .= ($current_stage_date != "") ? " $clause ( (a.lead_current_stage='1' and (a.created_at >='$current_stage_date' and a.created_at <='$current_stage_date_to')) or (a.lead_current_stage='3' and (a.lead_k1_datetime >='$current_stage_date' and a.lead_k1_datetime <='$current_stage_date_to')) or (a.lead_current_stage='4' and (a.lead_priority_datetime >='$current_stage_date' and a.lead_priority_datetime <='$current_stage_date_to')) or (a.lead_current_stage='5' and (a.lead_hold_datetime >='$current_stage_date' and a.lead_hold_datetime <='$current_stage_date_to')) or (a.lead_current_stage='6' and (a.lead_closed_datetime >='$current_stage_date' and a.lead_closed_datetime <='$current_stage_date_to')) or (a.lead_current_stage='7' and (a.lead_sold_datetime >='$current_stage_date' and a.lead_sold_datetime <='$current_stage_date_to')))" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_dob_from = (trim($request->txt_cus_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_from)) : "";
        $txt_cus_dob_to = (trim($request->txt_cus_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_to)) : "";
        $sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Customer_dateofbirth between '$txt_cus_dob_from' and '$txt_cus_dob_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_mar_date_from = (trim($request->txt_mar_date_from) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_from)) : "";
        $txt_mar_date_to = (trim($request->txt_mar_date_to) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_to)) : "";
        $sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Marriage_anniversary between '$txt_mar_date_from' and '$txt_mar_date_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_wife_dob_from = (trim($request->txt_cus_wife_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_from)) : "";
        $txt_cus_wife_dob_to = (trim($request->txt_cus_wife_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_to)) : "";
        $sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause customer_wife_dataofbirth between '$txt_cus_wife_dob_from' and '$txt_cus_wife_dob_to'" : "";

        $clause = ($sql_cond != "") ? " and" : " where";
        $txt_cus_child_dob_from = (trim($request->txt_cus_child_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_from)) : "";
        $txt_cus_child_dob_to = (trim($request->txt_cus_child_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_to)) : "";
        $sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause (children_dateofbirth1 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth2 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth3 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to')" : "";

        return DB::select("SELECT a.*,ss.user_pk_no,ss.user_fullname As createdbyuser,c.next_followup_Note,max(c.Next_FollowUp_date) lead_followup_datetime,c.close_reason as close_reason
            FROM t_lead2lifecycle_vw a
            LEFT JOIN (
            SELECT b.lead_pk_no,b.Next_FollowUp_date,b.next_followup_Note,b.close_reason,MAX(lead_followup_pk_no) AS maxid
            FROM t_leadfollowup b WHERE (b.next_followup_Note != '' OR b.close_reason != '')

            GROUP BY b.lead_pk_no,b.next_followup_Note order by b.lead_followup_pk_no desc) AS c ON a.lead_pk_no = c.lead_pk_no
            LEFT JOIN(select s.user_pk_no, s.user_fullname from s_user s) as ss
            ON a.created_by = ss.user_pk_no
            $sql_cond
            group by a.lead_pk_no");
    }


    public function search_result(Request $request)
    {
        // dd($request->all());
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lead_data = $this->serch_result_query($request);
        return view('admin.report_module.search_result', compact('lead_data', 'lead_stage_arr'));
    }

    public function export_report(Request $request)
    {
        $lead_data = $this->serch_result_query($request);
        // dd($lead_data);
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );
        $columns = array(
            "Lead ID",
            "Entry Date",
            "Customer First Name",
            "Customer Last Name",
            "Country Code1",
            "Phone Number 1",
            "Country Code2",
            "Phone Number 2",
            "Email",
            "Occupation",
            "Organization",
            "Category",
            "Area",
            "Project Name",
            "Size",
            "Source Name",
            "Digital Marketing",
            "Emp ID",
            "Name",
            "Position",
            "Contact Number",
            "SAC Name",
            "SAC Note",
            "Hotline",
            "Customer DOB",
            "Marriage Anniversary",
            "Wife Name",
            "Wife DOB",
            "Children Name1",
            "Children DOB1",
            "Children Name2",
            "Current Stage",
            "Current Stage Date",
            "Prev. Stages",
            "Stage Change Dates",
            "Sales Agent",
            "Next Followup Date",
            "Close Reason",
            "Sub Source",
            "K Qualification",
            "K Status",
            "P Status",
            "Created By",
            "SBU Name",
            "CHS Name",
            "HOT Name",
            "Sales Agent Employee ID",
            "Top Stage",
            "Sales Value",
            "Submitted by",
            "Last Comment",
            "Last Followup Date"
        );

        foreach ($lead_data as $ldata) {

            $lead_followup = DB::table('t_leadfollowup')->where('lead_pk_no', $ldata->lead_pk_no)->orderBy('lead_followup_pk_no', 'desc')->first();
        }


        $k_qualification_value = '';
        if (!empty($ldata->k_qualification)) {
            $k_qualification = json_decode($ldata->k_qualification);
            foreach ($k_qualification as $key => $kqualiValue) {

                $k_qualification_value .= $kqualiValue == " " ? " " : " $kqualiValue";
            }
        }


        $kstatus_value = '';
        if (!empty($lead_followup->k_status)) {
            $k_status = json_decode($lead_followup->k_status);
            foreach ($k_status as $key => $kstatusval) {

                $kstatus_value .= $kstatusval == " " ? " " : " $kstatusval";
            }
        }

        $pstatus_value = '';
        if (!empty($lead_followup->p_status)) {
            $p_status = json_decode($lead_followup->p_status);
            foreach ($p_status as $key => $pstatusval) {
                $pstatus_value .= $pstatusval == " " ? " " : " $pstatusval";
            }
        }






        $callback = function () use ($lead_data, $columns, $kstatus_value, $pstatus_value, $k_qualification_value) {
            $history = DB::select("SELECT lead_pk_no,GROUP_CONCAT(lead_stage_before_update ORDER BY created_at ASC) lead_stage_before_update, GROUP_CONCAT(created_at ORDER BY created_at ASC) stage_update_date
				FROM t_leadstagehistory
				GROUP BY lead_pk_no");
            $his_arr = [];

            if (!empty($history)) {
                foreach ($history as $his) {
                    $his_arr[$his->lead_pk_no]['lead_stage_before_update'] = $his->lead_stage_before_update;
                    $his_arr[$his->lead_pk_no]['stage_update_date'] = $his->stage_update_date;
                }
            }




            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($lead_data as $ldata) {
                $lead_stage_arr = config('static_arrays.lead_stage_arr');
                $lead_type = config('static_arrays.lead_type');
                $stages = $stage_update_date = "";
                if (isset($his_arr[$ldata->lead_pk_no]['lead_stage_before_update'])) {
                    $lead_stage_after_update = explode(",", $his_arr[$ldata->lead_pk_no]['lead_stage_before_update']);
                    foreach ($lead_stage_after_update as $stg) {
                        if (isset($lead_stage_arr[$stg])) {
                            $stages .= $lead_stage_arr[$stg] . ",";
                        }
                    }
                }
                $lead_followup = DB::table('t_leadfollowup')->where('lead_pk_no', $ldata->lead_pk_no)->orderBy('lead_followup_pk_no', 'desc')->first();
                //for top stage finding.
                $top_stage=1;
                $lead_followups = DB::table('t_leadfollowup')->where('lead_pk_no', $ldata->lead_pk_no)->orderBy('lead_followup_pk_no', 'desc')->get();
                foreach($lead_followups as $f_data){
                    if ($f_data->lead_stage_after_followup==3&&$top_stage!=4){
                        $top_stage=3;
                    }
                    if($f_data->lead_stage_after_followup==4){
                        $top_stage=4;
                    }
                }

                $top_stage_name= isset($lead_stage_arr[$top_stage])?$lead_stage_arr[$top_stage]:$lead_stage_arr[1];

                $stage_date = isset($lead_followup->lead_followup_datetime) ? date("d/m/Y", strtotime($lead_followup->lead_followup_datetime)) : '';
                if (isset($his_arr[$ldata->lead_pk_no]['stage_update_date'])) {
                    $update_date = $his_arr[$ldata->lead_pk_no]['stage_update_date'];
                    $stage_update_date = date("d/m/Y", strtotime($update_date));
                }
                $stages = rtrim($stages, ", ");
                $stage = isset($lead_stage_arr[$ldata->lead_current_stage]) ? $lead_stage_arr[$ldata->lead_current_stage] : '';
                $current_stage_dt = "";
                if ($ldata->lead_current_stage == 1) {
                    $current_stage_dt = date("d/m/Y", strtotime($ldata->created_at));
                }
                if ($ldata->lead_current_stage == 3) {
                    $current_stage_dt = date("d/m/Y", strtotime($ldata->lead_k1_datetime));
                }
                if ($ldata->lead_current_stage == 4) {
                    $current_stage_dt = date("d/m/Y", strtotime($ldata->lead_priority_datetime));
                }
                if ($ldata->lead_current_stage == 5) {
                    $current_stage_dt = date("d/m/Y", strtotime($ldata->lead_hold_datetime));
                }
                if ($ldata->lead_current_stage == 6) {
                    $current_stage_dt = date("d/m/Y", strtotime($ldata->lead_closed_datetime));
                }
                if ($ldata->lead_current_stage == 7) {
                    $current_stage_dt = "";
                    if (isset($ldata->lead_sold_date_manual)) {
                        $current_stage_dt = date("d/m/Y", strtotime($ldata->lead_sold_date_manual));
                    }
                }

                //checking if database returns null for prev followup stage
                $lead_stage_before_followup = "";
                if (isset($lead_followup->lead_stage_before_followup)) {
                    $lead_stage_before_followup = @$lead_stage_arr[$lead_followup->lead_stage_before_followup];
                }


                $Customer_dateofbirth = "";
                if (!isset($ldata->Customer_dateofbirth) || ($ldata->Customer_dateofbirth == "0000-01-01")) {
                    $Customer_dateofbirth = "";
                } else {
                    $Customer_dateofbirth = date("d/m/Y", strtotime($ldata->Customer_dateofbirth));
                }



                $Marriage_anniversary = "";
                if (!isset($ldata->Marriage_anniversary) || ($ldata->Marriage_anniversary == "0000-01-01")) {
                    $Marriage_anniversary = "";
                } else {
                    $Marriage_anniversary = date("d/m/Y", strtotime($ldata->Marriage_anniversary));
                }


                $customer_wife_dataofbirth = "";
                if (!isset($ldata->customer_wife_dataofbirth) || ($ldata->customer_wife_dataofbirth == "0000-01-01")) {
                    $customer_wife_dataofbirth = "";
                } else {
                    $customer_wife_dataofbirth = date("d/m/Y", strtotime($ldata->customer_wife_dataofbirth));
                }

                $children_dateofbirth1 = "";
                if (!isset($ldata->children_dateofbirth1) || ($ldata->children_dateofbirth1 == "0000-01-01")) {
                    $children_dateofbirth1 = "";
                } else {
                    $children_dateofbirth1 = date("d/m/Y", strtotime($ldata->children_dateofbirth1));
                }

                $lead_followup_datetime = "";
                if (!isset($ldata->lead_followup_datetime) || ($ldata->lead_followup_datetime == "0000-01-01")) {
                    $lead_followup_datetime = "";
                } else {
                    $lead_followup_datetime = date("d/m/Y", strtotime($ldata->lead_followup_datetime));
                }
                $sbu_name= isset($lead_type[$ldata->lead_type])?$lead_type[$ldata->lead_type]:"";

                $last_followup_date=@$lead_followup->lead_followup_datetime!="" ? date("d/m/Y", strtotime($lead_followup->lead_followup_datetime)) : '';

                fputcsv($file, array(
                    $ldata->lead_id,
                    date("d/m/Y", strtotime($ldata->created_at)),
                    $ldata->customer_firstname,
                    $ldata->customer_lastname,
                    $ldata->phone1_code,
                    $ldata->phone1,
                    $ldata->phone2_code,
                    $ldata->phone2,
                    $ldata->email_id,
                    $ldata->occup_name,
                    $ldata->org_name,
                    $ldata->project_category_name,
                    $ldata->project_area,
                    $ldata->project_name,
                    $ldata->project_size,
                    $ldata->source_auto_usergroup,
                    $ldata->source_digital_marketing,
                    $ldata->source_ir_emp_id,
                    $ldata->source_ir_name,
                    $ldata->source_ir_position,
                    $ldata->source_ir_contact_no,
                    $ldata->source_sac_name,
                    $ldata->source_sac_note,
                    $ldata->source_hotline,
                    $Customer_dateofbirth,
                    $Marriage_anniversary,
                    $ldata->customer_wife_name,
                    $customer_wife_dataofbirth,
                    $ldata->children_name1,
                    $children_dateofbirth1,
                    $ldata->children_name2,
                    $stage,
                    $current_stage_dt,
                    $lead_stage_before_followup,
                    $stage_update_date,
                    $ldata->lead_sales_agent_name,
                    $lead_followup_datetime,
                    $ldata->close_reason,
                    $ldata->source_auto_sub,
                    $k_qualification_value,
                    $kstatus_value,
                    $pstatus_value,
                    $ldata->user_full_name,
                    $sbu_name,
                    $ldata->cluster__name,
                    $ldata->team_lead_name,
                    $ldata->lead_sales_agent_employee_id,
                    $top_stage_name,
                    $ldata->lead_sold_flatcost,
                    $ldata->source_auto_gift,
                    @$lead_followup->followup_Note,
                    $last_followup_date
                ));
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    public function conversion_report()
    {
        $lead_type = config('static_arrays.lead_type');
        $target_year = YearSetup::orderby("id", "desc")->get();
        $cluster_head = DB::table("t_teambuild")
            ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
            ->select("s_user.user_pk_no", "s_user.user_fullname")
            ->where('t_teambuild.lead_type', '!=', 1)
            ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
            ->get();
        return view("admin.report_module.conversion_report.conversion_report", compact('target_year', 'cluster_head', 'lead_type'));
    }

    public function conversion_report_result(Request $request)
    {
        $report_type = $request->report_type;
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        //Finacial Year Date
        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
        $search_date =  $request->target_year . '-' . $request->target_month . '-31';

        $report_type = $request->report_type;
        $report_setup = DB::table('report_setup')
            ->whereIn('category_id', function ($query) use ($report_type) {
                $query->select('category_lookup_pk_no')
                    ->from('t_teambuild')
                    ->where('lead_type', $report_type);
            })
            ->where('prev_position', '=', 'sa')
            ->where('target_year_id', $fin_year_id)
            ->whereDate('created_at', '<=',  date('Y-m-d', strtotime($search_date)))
            ->get();


        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        $resize_arr['target_month'] = 0;
        $resize_arr['target_ytd'] = 0;
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $lead_contanier = 0;
                $lead_contanier_k1 = 0;
                $lead_contanier_curret_month = 0;
                $lead_contanier_k1_current_month = 0;
                $total_priroty_current_month = 0;
                $total_priroty_current_month_1 = 0;
                $total_k1_current_month = 0;
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                    $target_resign = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('member_id', $data->member_id)
                        ->first();
                    $resize_arr['target_month'] += isset($target_resign->target_month) ? $target_resign->target_month : 0;
                    $resize_arr['target_ytd'] += isset($target_resign->target_ytd) ? $target_resign->target_month : 0;
                    $member_id = $data->member_id;

                    $lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no
                        from t_lead2lifecycle_vw l
                        where l.lead_sales_agent_pk_no = '  $member_id'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        group by l.lead_pk_no");


                    $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                        where lead_sales_agent_pk_no = '  $member_id' and
                        YEAR(lead_sold_date_manual) = $target_year and
                        MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7");

                    $priority_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where l.lead_sales_agent_pk_no = '  $member_id'
                        and MONTH(p.lead_followup_datetime) = '$month_name'
                        and YEAR(p.lead_followup_datetime) = '$target_year'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        and p.lead_stage_after_followup=4
                        group by l.lead_pk_no");

                    $k1_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where  l.lead_sales_agent_pk_no = '$member_id'
                        and MONTH(p.lead_followup_datetime) = '$month_name'
                        and YEAR(p.lead_followup_datetime) = '$target_year'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                        group by l.lead_pk_no");




                    //upto current month total k1
                    $upto_currrentmonth_total_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where  l.lead_sales_agent_pk_no = '$member_id'
                        and l.created_at <='$finc_close_date'
                        and p.lead_followup_datetime <='$finc_close_date'
                        and p.lead_stage_after_followup=3
                        group by l.lead_pk_no");

                    //and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)






                    $total_sold = 0;
                    $total_sold = count($lead_sold_info);
                    $total_k1 = 0;
                    $yeartodate_total_k1 = 0;
                    $total_priroty_current_month_1 = count($priority_lead_info);

                    if (!empty($k1_lead_info)) {

                        foreach ($k1_lead_info as $lead) {

                            if (($lead->lead_current_stage == 3 || $lead->lead_current_stage == 4)) {
                                $lead_contanier_k1_current_month = $lead->lead_pk_no;
                                $lead_contanier_curret_month = $lead->lead_pk_no;
                                $total_k1_current_month++;
                            }
                        }
                    }


                    $resize_arr['new_lead'] = isset($resize_arr['new_lead']) ?  $resize_arr['new_lead'] +  count($lead_info) :  count($lead_info);
                    $resize_arr['new_k1'] = isset($resize_arr['new_k1']) ? $resize_arr['new_k1'] + $total_k1_current_month + $total_sold :  $total_k1_current_month + $total_sold; //$total_k1 + $total_sold;
                    $resize_arr['new_total_k1'] = isset($resize_arr['new_total_k1']) ?   $resize_arr['new_total_k1'] +  count($upto_currrentmonth_total_k1) :  count($upto_currrentmonth_total_k1); //$total_k1 + $total_sold;
                    $resize_arr['con'] = $total_sold;

                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                            where
                            lead_sales_agent_pk_no = '  $member_id' and
                            created_at >=  '$finc_start_date' and created_at <='$finc_close_date' ");
                    $total_k1 = 0;
                    $total_priroty = 0;
                    $total_sold = count(DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                            where
                            lead_sales_agent_pk_no = '  $member_id' and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage='7'"));
                    if (!empty($lead_total_info)) {
                        foreach ($lead_total_info as $lead) {
                            if ($lead->lead_current_stage == 4) {
                                $total_priroty++;
                            }
                            if ($lead->lead_current_stage == 3) {
                                $total_k1++;
                            }
                        }
                    }
                    $lead_sold_current_month_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                        where lead_sales_agent_pk_no = '  $member_id' and
                        lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");


                    //Current month total_new_k1
                    $yeartodate_lead_total_info_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                            from t_lead2lifecycle_vw l
                                            inner join t_leadfollowup p
                                            on l.lead_pk_no = p.lead_pk_no
                                            where l.lead_sales_agent_pk_no = '  $member_id'
                                            and p.lead_followup_datetime >= '$finc_start_date'
                                            and p.lead_followup_datetime <='$finc_close_date'
                                            and l.created_at >= '$finc_start_date'
                                            and l.created_at <='$finc_close_date'
                                            and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                            group by l.lead_pk_no");



                    $yeartodate_total_k1 = count($yeartodate_lead_total_info_k1);




                    //Year to date priority
                    $yearto_date_priority_lead_total_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                            from t_lead2lifecycle_vw l
                                            inner join t_leadfollowup p
                                            on l.lead_pk_no = p.lead_pk_no
                                            where l.lead_sales_agent_pk_no = '  $member_id'
                                            and p.lead_followup_datetime >= '$finc_start_date'
                                            and p.lead_followup_datetime <='$finc_close_date'
                                            and (p.lead_stage_after_followup=4)
                                            group by l.lead_pk_no");

                    $total_priroty = 0;
                    if (!empty($yearto_date_priority_lead_total_info)) {
                        foreach ($yearto_date_priority_lead_total_info as $lead) {
                            if ($lead->lead_current_stage == 4) {
                                $total_priroty++;
                            }
                        }
                    }



                    $lead_sold_for_yeartodate_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                    where lead_sales_agent_pk_no = '$member_id' and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");
                    $total_sold_for_yeartodate = count($lead_sold_for_yeartodate_total_info);


                    $resize_arr['total_k1'] =  isset($resize_arr['total_k1']) ? $resize_arr['total_k1'] +  $yeartodate_total_k1 + $total_sold_for_yeartodate : $yeartodate_total_k1 + $total_sold_for_yeartodate;
                    $resize_arr['new_priority'] = isset($resize_arr['new_priority']) ?   $resize_arr['new_priority'] + $total_priroty_current_month_1 : $total_priroty_current_month_1;
                    $resize_arr['priority'] = isset($resize_arr['priority']) ?    $resize_arr['priority'] + $total_priroty :  $total_priroty;



                    $resize_arr['total_new_lead'] = count($lead_total_info);

                    $resize_arr['t_con'] = $total_sold;
                }
            }
        }

        // dd( $starting_year, $starting_month );

        // getMonth($month_arr){
        //     return $month_arr;
        // };
        // return array_map('getMonth',[1,2,3,4]);
        // if ($request->cluster_head == '' || $request->cluster_head == '0') {
        //     $cluster_head1 = DB::table("t_teambuild")
        //         ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
        //         ->select("s_user.user_pk_no", "s_user.user_fullname")
        //         ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
        //         ->where('t_teambuild.lead_type', $request->report_type)
        //         ->get();
        // } else {
        //     $cluster_head1 = DB::table("t_teambuild")
        //         ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
        //         ->select("s_user.user_pk_no", "s_user.user_fullname")
        //         ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
        //         ->where("s_user.user_pk_no", $request->cluster_head)
        //         ->where('t_teambuild.lead_type', $request->report_type)
        //         ->get();
        // }
        //dd( $cluster_head1);
        $team_member = [];
        $tl_arr = [];
        $report_data = [];
        $user_id_check = [];
        $ach_arr = [];
        $team_build = [];

        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
        //Ch

        $get_all_tl_member = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.team_lookup_pk_no,  t_teambuild.category_lookup_pk_no FROM t_teambuild join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no WHERE team_lead_flag=1 and lead_type=$request->report_type");

        $lead_contanier = 0;
        $lead_contanier_k1 = 0;
        $lead_contanier_curret_month = 0;
        $lead_contanier_k1_current_month = 0;
        $total_priroty_current_month = 0;
        $total_priroty_current_month_1 = 0;
        $total_k1_current_month = 0;
        // echo "SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.team_lookup_pk_no FROM t_teambuild join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no WHERE (hod_user_pk_no='$cluster->user_pk_no' and team_lead_flag=1)";
        //team leader
        $get_all_team_member = [];
        //dd(  $get_all_tl_member);
        if (!empty($get_all_tl_member)) {
            foreach ($get_all_tl_member as $teamLeader) {
                $get_all_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname, t_teambuild.category_lookup_pk_no
                        FROM t_teambuild join s_user
                        on s_user.user_pk_no =  t_teambuild.user_pk_no
                        WHERE (team_lead_user_pk_no='$teamLeader->user_pk_no'
                        and team_lead_flag=0 and hod_flag =0 and hot_flag =0 and agent_type = 2
                        and team_lookup_pk_no = '$teamLeader->team_lookup_pk_no')
                        ");
                if (!empty($get_all_members)) {
                    foreach ($get_all_members as $member) {
                        array_push($team_build, [
                            'user_pk_no' => $member->user_pk_no,
                            'category_lookup_pk_no' => $teamLeader->category_lookup_pk_no,
                            'user_fullname' => $member->user_fullname,
                            'team_lookup_pk_no' => $teamLeader->team_lookup_pk_no,
                            'tl_pk_no' => $teamLeader->user_pk_no,
                            'tl_fullname' => $teamLeader->user_fullname
                        ]);
                    }
                }
                $resign_promotion_member = DB::table('report_setup')
                    ->join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('report_setup.category_id', $request->project_name)
                    ->where('report_setup.prev_position', '=', 'sa')
                    ->where('report_setup.team_id', $teamLeader->team_lookup_pk_no)
                    ->where('report_setup.target_year_id', $fin_year_id)
                    ->whereDate('report_setup.created_at', '>=',  date('Y-m-d', strtotime($search_date)))
                    ->get();
                if (!empty($resign_promotion_member)) {
                    // dd($resign_promotion_member);
                    foreach ($resign_promotion_member as $r_member) {

                        if ((!in_array($r_member->member_id, $user_id_check))) {
                            array_push($user_id_check, $r_member->member_id);
                            array_push($team_build, [
                                'user_pk_no' => $r_member->member_id,
                                'category_lookup_pk_no' => $r_member->category_id,
                                'user_fullname' => $r_member->user_fullname,
                                'team_lookup_pk_no' => $r_member->team_id,
                                'tl_pk_no' => $teamLeader->user_pk_no,
                                'tl_fullname' => $teamLeader->user_fullname
                            ]);
                        }
                    }
                }
            }
        }

        if (!empty($team_build)) {

            foreach ($team_build as $cluster) {

                $lead_contanier = 0;
                $lead_contanier_k1 = 0;
                $lead_contanier_curret_month = 0;
                $lead_contanier_k1_current_month = 0;
                $total_priroty_current_month = 0;
                $total_priroty_current_month_1 = 0;
                $total_k1_current_month = 0;
                $get_all_team_member = [];

                $team_member[$cluster['tl_pk_no']][$cluster['user_pk_no']] = $cluster['user_fullname'];
                $member_id =  $cluster['user_pk_no'];

                //Lead info with followup

                $lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no
                                    from t_lead2lifecycle_vw l
                                    where l.lead_sales_agent_pk_no = '  $member_id'
                                    and MONTH(l.created_at) = '$month_name'
                                    and YEAR(l.created_at) = '$target_year'
                                    group by l.lead_pk_no");
                // and l.lead_type = '$request->report_type'

                $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '  $member_id' and
                                    YEAR(lead_sold_date_manual) = $target_year and
                                    MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                                    and lead_type = '$request->report_type'
                                    ");

                $priority_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where l.lead_sales_agent_pk_no = '  $member_id'
                                        and MONTH(p.lead_followup_datetime) = '$month_name'
                                        and YEAR(p.lead_followup_datetime) = '$target_year'
                                        and MONTH(l.created_at) = '$month_name'
                                        and YEAR(l.created_at) = '$target_year'
                                        and lead_type = '$request->report_type'
                                        and p.lead_stage_after_followup=4
                                        group by l.lead_pk_no");

                $k1_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where  l.lead_sales_agent_pk_no = '$member_id'
                                        and MONTH(p.lead_followup_datetime) = '$month_name'
                                        and YEAR(p.lead_followup_datetime) = '$target_year'
                                        and MONTH(l.created_at) = '$month_name'
                                        and YEAR(l.created_at) = '$target_year'
                                        and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                        group by l.lead_pk_no");




                //upto current month total k1
                $upto_currrentmonth_total_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where  l.lead_sales_agent_pk_no = '$member_id'
                                        and l.created_at <='$finc_close_date'
                                        and p.lead_followup_datetime <='$finc_close_date'
                                        and p.lead_stage_after_followup=3
                                        group by l.lead_pk_no");

                //and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)






                $total_sold = 0;
                $total_sold = count($lead_sold_info);
                $total_k1 = 0;
                $yeartodate_total_k1 = 0;
                $total_priroty_current_month_1 = count($priority_lead_info);

                if (!empty($k1_lead_info)) {

                    foreach ($k1_lead_info as $lead) {

                        if (($lead->lead_current_stage == 3 || $lead->lead_current_stage == 4)) {
                            $lead_contanier_k1_current_month = $lead->lead_pk_no;
                            $lead_contanier_curret_month = $lead->lead_pk_no;
                            $total_k1_current_month++;
                        }
                    }
                }


                $report_data['new_lead'][$member_id] = count($lead_info);
                $report_data['new_k1'][$member_id] = $total_k1_current_month + $total_sold; //$total_k1 + $total_sold;
                $report_data['new_total_k1'][$member_id] = count($upto_currrentmonth_total_k1); //$total_k1 + $total_sold;
                $report_data['con'][$member_id] = $total_sold;

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                        where
                                        lead_sales_agent_pk_no = '  $member_id' and
                                        created_at >=  '$finc_start_date' and created_at <='$finc_close_date' "); //and lead_type='$request->report_type'
                $total_k1 = 0;
                $total_priroty = 0;
                $total_sold = count(DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where
                                    lead_sales_agent_pk_no = '  $member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage='7'"));
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        if ($lead->lead_current_stage == 4) {
                            $total_priroty++;
                        }
                        if ($lead->lead_current_stage == 3) {
                            $total_k1++;
                        }
                    }
                }
                $lead_sold_current_month_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '  $member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                                    and lead_current_stage =7
                                    and lead_type = '$request->report_type'
                                    ");


                //Current month total_new_k1
                $yeartodate_lead_total_info_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                                                from t_lead2lifecycle_vw l
                                                                inner join t_leadfollowup p
                                                                on l.lead_pk_no = p.lead_pk_no
                                                                where l.lead_sales_agent_pk_no = '  $member_id'
                                                                and p.lead_followup_datetime >= '$finc_start_date'
                                                                and p.lead_followup_datetime <='$finc_close_date'
                                                                and l.created_at >= '$finc_start_date'
                                                                and l.created_at <='$finc_close_date'
                                                                and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                                                group by l.lead_pk_no");



                $yeartodate_total_k1 = count($yeartodate_lead_total_info_k1);




                //Year to date priority
                $yearto_date_priority_lead_total_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                                                from t_lead2lifecycle_vw l
                                                                inner join t_leadfollowup p
                                                                on l.lead_pk_no = p.lead_pk_no
                                                                where l.lead_sales_agent_pk_no = '  $member_id'
                                                                and p.lead_followup_datetime >= '$finc_start_date'
                                                                and p.lead_followup_datetime <='$finc_close_date'
                                                                and (p.lead_stage_after_followup=4)
                                                                group by l.lead_pk_no");

                $total_priroty = 0;
                if (!empty($yearto_date_priority_lead_total_info)) {
                    foreach ($yearto_date_priority_lead_total_info as $lead) {
                        if ($lead->lead_current_stage == 4) {
                            $total_priroty++;
                        }
                    }
                }



                $lead_sold_for_yeartodate_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '$member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                                    and lead_current_stage =7
                                    and lead_type = '$request->report_type'");

                $total_sold_for_yeartodate = count($lead_sold_for_yeartodate_total_info);

                $report_data['new_lead'][$member_id] = count($lead_info);
                $report_data['total_k1'][$member_id] = $yeartodate_total_k1 + $total_sold_for_yeartodate;
                $report_data['new_priority'][$member_id] = $total_priroty_current_month_1;
                $report_data['priority'][$member_id] = $total_priroty;



                $report_data['total_new_lead'][$member_id] = count($lead_total_info);

                $report_data['t_con'][$member_id] = $total_sold;
                $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                     where lead_sales_agent_pk_no = '  $member_id' and lead_current_stage = '3'");

                $team_member[$cluster['tl_pk_no']][$cluster['user_pk_no']] = $cluster['user_fullname'];
            }
        }
        $categoryname = LookupData::find($request->project_name);

        return view("admin.report_module.conversion_report.conversion_report_result", compact("categoryname", "resize_arr", "team_build", "doj_arr", "dor_arr", "resign_promote_arr", "target_year1", "month", "tl_arr", "team_member", 'report_data'));
    }

    public function sales_report()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();
        $cluster_head = DB::table("t_teambuild")
            ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
            ->select("s_user.user_pk_no", "s_user.user_fullname")
            ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
            ->get();
        return view("admin.report_module.sales_report.sales_report", compact('target_year', 'cluster_head'));
    }

    public function obm_conversion_report()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();
        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata join s_projectwiseflatlist on s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type =1"); //LookupData::where('lookup_type', 4)->get()

        return view("admin.report_module.obm_conversion_report.obm_conversion_report", compact('project_cateory', 'target_year'));
    }

    public function obm_conversion_report_result(Request $request)
    {

        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        //Finacial Year Date
        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

        //creating date of search
        $search_date =  $request->target_year . '-' . $request->target_month . '-31';
        //Resgin and promotion list
        $report_setup = DB::table('report_setup')
            ->where('category_id', $request->project_name)
            ->where('prev_position', '=', 'sa')
            ->where('target_year_id', $fin_year_id)
            //->whereDate('created_at', '<=',  date('Y-m-d', strtotime($search_date)))
            ->get();

        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        $resize_arr['target_month'] = 0;
        $resize_arr['target_ytd'] = 0;
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $lead_contanier = 0;
                $lead_contanier_k1 = 0;
                $lead_contanier_curret_month = 0;
                $lead_contanier_k1_current_month = 0;
                $total_priroty_current_month = 0;
                $total_priroty_current_month_1 = 0;
                $total_k1_current_month = 0;
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                    $target_resign = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('member_id', $data->member_id)
                        ->first();
                    $resize_arr['target_month'] += isset($target_resign->target_month) ? $target_resign->target_month : 0;
                    $resize_arr['target_ytd'] += isset($target_resign->target_ytd) ? $target_resign->target_month : 0;
                    $member_id = $data->member_id;

                    $lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no
                        from t_lead2lifecycle_vw l
                        where l.lead_sales_agent_pk_no = '  $member_id'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        group by l.lead_pk_no");


                    $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                        where lead_sales_agent_pk_no = '  $member_id' and
                        YEAR(lead_sold_date_manual) = $target_year and
                        MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7");

                    $priority_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where l.lead_sales_agent_pk_no = '  $member_id'
                        and MONTH(p.lead_followup_datetime) = '$month_name'
                        and YEAR(p.lead_followup_datetime) = '$target_year'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        and p.lead_stage_after_followup=4
                        group by l.lead_pk_no");

                    $k1_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where  l.lead_sales_agent_pk_no = '$member_id'
                        and MONTH(p.lead_followup_datetime) = '$month_name'
                        and YEAR(p.lead_followup_datetime) = '$target_year'
                        and MONTH(l.created_at) = '$month_name'
                        and YEAR(l.created_at) = '$target_year'
                        and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                        group by l.lead_pk_no");




                    //upto current month total k1
                    $upto_currrentmonth_total_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                        from t_lead2lifecycle_vw l
                        inner join t_leadfollowup p
                        on l.lead_pk_no = p.lead_pk_no
                        where  l.lead_sales_agent_pk_no = '$member_id'
                        and l.created_at <='$finc_close_date'
                        and p.lead_followup_datetime <='$finc_close_date'
                        and p.lead_stage_after_followup=3
                        group by l.lead_pk_no");

                    //and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)






                    $total_sold = 0;
                    $total_sold = count($lead_sold_info);
                    $total_k1 = 0;
                    $yeartodate_total_k1 = 0;
                    $total_priroty_current_month_1 = count($priority_lead_info);

                    if (!empty($k1_lead_info)) {

                        foreach ($k1_lead_info as $lead) {

                            if (($lead->lead_current_stage == 3 || $lead->lead_current_stage == 4)) {
                                $lead_contanier_k1_current_month = $lead->lead_pk_no;
                                $lead_contanier_curret_month = $lead->lead_pk_no;
                                $total_k1_current_month++;
                            }
                        }
                    }


                    $resize_arr['new_lead'] = isset($resize_arr['new_lead']) ?  $resize_arr['new_lead'] +  count($lead_info) :  count($lead_info);
                    $resize_arr['new_k1'] = isset($resize_arr['new_k1']) ? $resize_arr['new_k1'] + $total_k1_current_month + $total_sold :  $total_k1_current_month + $total_sold; //$total_k1 + $total_sold;
                    $resize_arr['new_total_k1'] = isset($resize_arr['new_total_k1']) ?   $resize_arr['new_total_k1'] +  count($upto_currrentmonth_total_k1) :  count($upto_currrentmonth_total_k1); //$total_k1 + $total_sold;
                    $resize_arr['con'] = $total_sold;

                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                            where
                            lead_sales_agent_pk_no = '  $member_id' and
                            created_at >=  '$finc_start_date' and created_at <='$finc_close_date' ");
                    $total_k1 = 0;
                    $total_priroty = 0;
                    $total_sold = count(DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                            where
                            lead_sales_agent_pk_no = '  $member_id' and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage='7'"));
                    if (!empty($lead_total_info)) {
                        foreach ($lead_total_info as $lead) {
                            if ($lead->lead_current_stage == 4) {
                                $total_priroty++;
                            }
                            if ($lead->lead_current_stage == 3) {
                                $total_k1++;
                            }
                        }
                    }
                    $lead_sold_current_month_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                        where lead_sales_agent_pk_no = '  $member_id' and
                        lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");


                    //Current month total_new_k1
                    $yeartodate_lead_total_info_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                            from t_lead2lifecycle_vw l
                                            inner join t_leadfollowup p
                                            on l.lead_pk_no = p.lead_pk_no
                                            where l.lead_sales_agent_pk_no = '  $member_id'
                                            and p.lead_followup_datetime >= '$finc_start_date'
                                            and p.lead_followup_datetime <='$finc_close_date'
                                            and l.created_at >= '$finc_start_date'
                                            and l.created_at <='$finc_close_date'
                                            and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                            group by l.lead_pk_no");



                    $yeartodate_total_k1 = count($yeartodate_lead_total_info_k1);




                    //Year to date priority
                    $yearto_date_priority_lead_total_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                            from t_lead2lifecycle_vw l
                                            inner join t_leadfollowup p
                                            on l.lead_pk_no = p.lead_pk_no
                                            where l.lead_sales_agent_pk_no = '  $member_id'
                                            and p.lead_followup_datetime >= '$finc_start_date'
                                            and p.lead_followup_datetime <='$finc_close_date'
                                            and (p.lead_stage_after_followup=4)
                                            group by l.lead_pk_no");

                    $total_priroty = 0;
                    if (!empty($yearto_date_priority_lead_total_info)) {
                        foreach ($yearto_date_priority_lead_total_info as $lead) {
                            if ($lead->lead_current_stage == 4) {
                                $total_priroty++;
                            }
                        }
                    }



                    $lead_sold_for_yeartodate_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                    where lead_sales_agent_pk_no = '$member_id' and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");
                    $total_sold_for_yeartodate = count($lead_sold_for_yeartodate_total_info);


                    $resize_arr['total_k1'] =  isset($resize_arr['total_k1']) ? $resize_arr['total_k1'] +  $yeartodate_total_k1 + $total_sold_for_yeartodate : $yeartodate_total_k1 + $total_sold_for_yeartodate;
                    $resize_arr['new_priority'] = isset($resize_arr['new_priority']) ?   $resize_arr['new_priority'] + $total_priroty_current_month_1 : $total_priroty_current_month_1;
                    $resize_arr['priority'] = isset($resize_arr['priority']) ?    $resize_arr['priority'] + $total_priroty :  $total_priroty;



                    $resize_arr['total_new_lead'] = count($lead_total_info);

                    $resize_arr['t_con'] = $total_sold;
                }
            }
        }


        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.category_lookup_pk_no,
        t_teambuild.team_lookup_pk_no FROM t_teambuild
        join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
        where team_lead_flag=1 and t_teambuild.category_lookup_pk_no= $request->project_name and lead_type = 1");

        //dd($team_members);

        $user_id_check = [];
        $ach_arr = [];
        $team_build = [];
        $tl_wise_mem_arr = [];
        $search_date =  $request->team_target_year . '-' . $request->target_month . '-31';
        //Members Array
        if ($request->project_name == '' || $request->project_name == '0') {
            $report_data = [];
            $category_name = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata left join s_projectwiseflatlist on s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type =1"); //LookupData::where('lookup_type', 4)->get()
            foreach ($category_name as $category) {

                $team_member = [];
                $tl_arr = [];

                //Ch

                $get_all_tl_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) as teamMembers FROM t_teambuild WHERE category_lookup_pk_no='$category->lookup_pk_no' and team_lead_flag =0 and hot_flag= 0 and hod_flag =0 ")[0]->teamMembers;

                $resign_promotion_member = DB::table('report_setup')
                    ->join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('report_setup.category_id', $category->lookup_pk_no)
                    ->where('report_setup.prev_position', '=', 'sa')
                    ->where('report_setup.target_year_id', $fin_year_id)
                    ->whereDate('report_setup.created_at', '<=',  date('Y-m-d', strtotime($search_date)))
                    ->get();





                if (!empty($resign_promotion_member)) {
                    // dd($resign_promotion_member);
                    foreach ($resign_promotion_member as $r_member) {
                        $get_all_tl_member .= ',' . $r_member->member_id;
                    }
                }
                //    dd( $get_all_tl_member);









                //echo $get_all_tl_member . '<br>';

                //echo "SELECT s_user.user_pk_no,s_user.user_fullname FROM t_teambuild join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no WHERE (hod_user_pk_no='$cluster->user_pk_no' and team_lead_flag=1)";
                //team leader
                $get_all_team_member = isset($get_all_tl_member) ? $get_all_tl_member : '0';

                $lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, lead_current_stage
                                    from t_lead2lifecycle_vw l
                                    where l.lead_sales_agent_pk_no in ($get_all_team_member)
                                    and MONTH(l.created_at) = '$month_name'
                                    and YEAR(l.created_at) = '$target_year'
                                    group by l.lead_pk_no");



                $total_k1 = 0;
                $total_sold = 0;

                $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                where lead_type = '1' and lead_current_stage= 7 and
                lead_sales_agent_pk_no in ($get_all_team_member) and
                YEAR(lead_sold_date_manual) = $target_year and
                MONTH(lead_sold_date_manual) = '$month_name'");

                $priority_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                from t_lead2lifecycle_vw l
                inner join t_leadfollowup p
                on l.lead_pk_no = p.lead_pk_no
                where l.lead_sales_agent_pk_no in ($get_all_team_member)
                and MONTH(p.lead_followup_datetime) = '$month_name'
                and YEAR(p.lead_followup_datetime) = '$target_year'
                and MONTH(l.created_at) = '$month_name'
                and YEAR(l.created_at) = '$target_year'
                and p.lead_stage_after_followup=4
                group by l.lead_pk_no");

                $k1_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                from t_lead2lifecycle_vw l
                inner join t_leadfollowup p
                on l.lead_pk_no = p.lead_pk_no
                where  l.lead_sales_agent_pk_no in ($get_all_team_member)
                and MONTH(p.lead_followup_datetime) = '$month_name'
                and YEAR(p.lead_followup_datetime) = '$target_year'
                and MONTH(l.created_at) = '$month_name'
                and YEAR(l.created_at) = '$target_year'
                and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                group by l.lead_pk_no");

                $upto_currrentmonth_total_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                from t_lead2lifecycle_vw l
                inner join t_leadfollowup p
                on l.lead_pk_no = p.lead_pk_no
                where  l.lead_sales_agent_pk_no in ($get_all_team_member)
                and l.created_at <='$finc_close_date'
                and p.lead_followup_datetime <='$finc_close_date'
                and p.lead_stage_after_followup=3
                group by l.lead_pk_no");



                $total_sold = count($lead_sold_info);

                $report_data['new_lead'][$category->lookup_pk_no] = count($lead_info);
                $report_data['new_k1'][$category->lookup_pk_no] = count($k1_lead_info) + $total_sold;
                $report_data['new_total_k1'][$category->lookup_pk_no] = count($upto_currrentmonth_total_k1); //$total_k1 + $total_sold;

                $report_data['con'][$category->lookup_pk_no] = $total_sold;
                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                where
                                lead_sales_agent_pk_no in ($get_all_team_member) and
                                created_at >=  '$finc_start_date' and created_at <='$finc_close_date' ");

                $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                where
                                lead_sales_agent_pk_no in ($get_all_team_member) and
                                lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' ");
                //Current month total_new_k1
                $yeartodate_lead_total_info_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                 from t_lead2lifecycle_vw l
                 inner join t_leadfollowup p
                 on l.lead_pk_no = p.lead_pk_no
                 where l.lead_sales_agent_pk_no in ($get_all_team_member)
                 and p.lead_followup_datetime >= '$finc_start_date'
                 and p.lead_followup_datetime <='$finc_close_date'
                 and l.created_at >= '$finc_start_date'
                 and l.created_at <='$finc_close_date'
                 and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                 group by l.lead_pk_no");



                $yeartodate_total_k1 = count($yeartodate_lead_total_info_k1);




                //Year to date priority
                $yearto_date_priority_lead_total_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                 from t_lead2lifecycle_vw l
                 inner join t_leadfollowup p
                 on l.lead_pk_no = p.lead_pk_no
                 where l.lead_sales_agent_pk_no in ($get_all_team_member)
                 and p.lead_followup_datetime >= '$finc_start_date'
                 and p.lead_followup_datetime <='$finc_close_date'
                 and (p.lead_stage_after_followup=4)
                 group by l.lead_pk_no");
                $total_priroty = 0;
                if (!empty($yearto_date_priority_lead_total_info)) {
                    foreach ($yearto_date_priority_lead_total_info as $lead) {
                        if ($lead->lead_current_stage == 4) {
                            $total_priroty++;
                        }
                    }
                }

                $total_priroty_current_month_1 = count($priority_lead_info);

                $report_data[$category->lookup_pk_no]['total_new_lead'] = count($lead_total_info);
                $report_data[$category->lookup_pk_no]['total_k1'] = $yeartodate_total_k1 + count($lead_sold_info);
                $report_data[$category->lookup_pk_no]['priority'] = $total_priroty;
                $report_data[$category->lookup_pk_no]['new_priority'] = $total_priroty_current_month_1;
                $report_data[$category->lookup_pk_no]['t_con'] = count($lead_sold_info);
            }

            return view('admin.report_module.obm_conversion_report.obm_conversion_report_result_v2', compact('month_arr', 'month', 'report_data', 'category_name'));
        } else {
            //$team_build = [];
            if (!empty($team_members)) {
                foreach ($team_members as $hot) {
                    $get_all_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.category_lookup_pk_no,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    WHERE (team_lead_user_pk_no='$hot->user_pk_no' and hod_flag =0 and
                    hot_flag=0 and team_lead_flag =0)");

                    if (!empty($get_all_members)) {
                        foreach ($get_all_members as $member) {
                            array_push($user_id_check, $member->user_pk_no);
                            array_push($team_build, [
                                'user_pk_no' => $member->user_pk_no,
                                'category_lookup_pk_no' => $hot->category_lookup_pk_no,
                                'user_fullname' => $member->user_fullname,
                                'team_lookup_pk_no' => $hot->team_lookup_pk_no,
                                'tl_pk_no' => $hot->user_pk_no,
                                'tl_fullname' => $hot->user_fullname
                            ]);
                        }
                    }
                    $resign_promotion_member = DB::table('report_setup')
                        ->join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                        ->where('report_setup.category_id', $request->project_name)
                        ->where('report_setup.prev_position', '=', 'sa')
                        ->where('report_setup.team_id', $hot->team_lookup_pk_no)
                        ->where('report_setup.target_year_id', $fin_year_id)
                        ->whereDate('report_setup.created_at', '>=',  date('Y-m-d', strtotime($search_date)))
                        ->get();
                    //dd($resign_promotion_member,$team_build);
                    //dd($resign_promotion_member);
                    //  echo   $hot->user_pk_no.'<br>'; //. $search_date.'<br>'. $hod->team_lookup_pk_no
                    // dd(!empty($resign_promotion_member));
                    if (!empty($resign_promotion_member)) {
                        // dd($resign_promotion_member);
                        foreach ($resign_promotion_member as $r_member) {
                            if ((!in_array($r_member->member_id, $user_id_check))) {
                                //echo $r_member->member_id.'<br>';
                                array_push($user_id_check, $r_member->member_id);
                                array_push($team_build, [
                                    'user_pk_no' => $r_member->member_id,
                                    'category_lookup_pk_no' => $r_member->category_id,
                                    'user_fullname' => $r_member->user_fullname,
                                    'team_lookup_pk_no' => $r_member->team_id,
                                    'tl_pk_no' => $hot->user_pk_no,
                                    'tl_fullname' => $hot->user_fullname
                                ]);
                            }
                        }
                    }
                }
            }
        }
        //dd($user_id_check,$team_build);



        //dd(array_unique($team_build));

        $cluster_head1 = DB::table("t_teambuild")
            ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
            ->select("s_user.user_pk_no", "s_user.user_fullname")
            ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
            ->where('category_lookup_pk_no', $request->project_name)
            ->get();

        $team_member = [];
        $tl_arr = [];
        $report_data = [];
        //dd($team_build);
        //Ch
        if (!empty($team_build)) {

            foreach ($team_build as $cluster) {

                $lead_contanier = 0;
                $lead_contanier_k1 = 0;
                $lead_contanier_curret_month = 0;
                $lead_contanier_k1_current_month = 0;
                $total_priroty_current_month = 0;
                $total_priroty_current_month_1 = 0;
                $total_k1_current_month = 0;
                $get_all_team_member = [];

                $team_member[$cluster['tl_pk_no']][$cluster['user_pk_no']] = $cluster['user_fullname'];
                $member_id =  $cluster['user_pk_no'];

                //Lead info with followup

                $lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no
                                    from t_lead2lifecycle_vw l
                                    where l.lead_sales_agent_pk_no = '  $member_id'
                                    and MONTH(l.created_at) = '$month_name'
                                    and YEAR(l.created_at) = '$target_year'
                                    group by l.lead_pk_no");


                $lead_sold_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '  $member_id' and
                                    YEAR(lead_sold_date_manual) = $target_year and
                                    MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7");

                $priority_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where l.lead_sales_agent_pk_no = '  $member_id'
                                        and MONTH(p.lead_followup_datetime) = '$month_name'
                                        and YEAR(p.lead_followup_datetime) = '$target_year'
                                        and MONTH(l.created_at) = '$month_name'
                                        and YEAR(l.created_at) = '$target_year'
                                        and p.lead_stage_after_followup=4
                                        group by l.lead_pk_no");

                $k1_lead_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where  l.lead_sales_agent_pk_no = '$member_id'
                                        and MONTH(p.lead_followup_datetime) = '$month_name'
                                        and YEAR(p.lead_followup_datetime) = '$target_year'
                                        and MONTH(l.created_at) = '$month_name'
                                        and YEAR(l.created_at) = '$target_year'
                                        and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                        group by l.lead_pk_no");




                //upto current month total k1
                $upto_currrentmonth_total_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                        from t_lead2lifecycle_vw l
                                        inner join t_leadfollowup p
                                        on l.lead_pk_no = p.lead_pk_no
                                        where  l.lead_sales_agent_pk_no = '$member_id'
                                        and l.created_at <='$finc_close_date'
                                        and p.lead_followup_datetime <='$finc_close_date'
                                        and p.lead_stage_after_followup=3
                                        group by l.lead_pk_no");

                //and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)






                $total_sold = 0;
                $total_sold = count($lead_sold_info);
                $total_k1 = 0;
                $yeartodate_total_k1 = 0;
                $total_priroty_current_month_1 = count($priority_lead_info);

                if (!empty($k1_lead_info)) {

                    foreach ($k1_lead_info as $lead) {

                        if (($lead->lead_current_stage == 3 || $lead->lead_current_stage == 4)) {
                            $lead_contanier_k1_current_month = $lead->lead_pk_no;
                            $lead_contanier_curret_month = $lead->lead_pk_no;
                            $total_k1_current_month++;
                        }
                    }
                }


                $report_data['new_lead'][$member_id] = count($lead_info);
                $report_data['new_k1'][$member_id] = $total_k1_current_month + $total_sold; //$total_k1 + $total_sold;
                $report_data['new_total_k1'][$member_id] = count($upto_currrentmonth_total_k1); //$total_k1 + $total_sold;
                $report_data['con'][$member_id] = $total_sold;

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                        where
                                        lead_sales_agent_pk_no = '  $member_id' and
                                        created_at >=  '$finc_start_date' and created_at <='$finc_close_date' ");
                $total_k1 = 0;
                $total_priroty = 0;
                $total_sold = count(DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where
                                    lead_sales_agent_pk_no = '  $member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage='7'"));
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        if ($lead->lead_current_stage == 4) {
                            $total_priroty++;
                        }
                        if ($lead->lead_current_stage == 3) {
                            $total_k1++;
                        }
                    }
                }
                $lead_sold_current_month_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '  $member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");


                //Current month total_new_k1
                $yeartodate_lead_total_info_k1 = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                                                from t_lead2lifecycle_vw l
                                                                inner join t_leadfollowup p
                                                                on l.lead_pk_no = p.lead_pk_no
                                                                where l.lead_sales_agent_pk_no = '  $member_id'
                                                                and p.lead_followup_datetime >= '$finc_start_date'
                                                                and p.lead_followup_datetime <='$finc_close_date'
                                                                and l.created_at >= '$finc_start_date'
                                                                and l.created_at <='$finc_close_date'
                                                                and (p.lead_stage_after_followup=4 or p.lead_stage_after_followup=3)
                                                                group by l.lead_pk_no");



                $yeartodate_total_k1 = count($yeartodate_lead_total_info_k1);




                //Year to date priority
                $yearto_date_priority_lead_total_info = DB::select("select l.lead_pk_no,l.lead_sales_agent_pk_no, p.lead_stage_after_followup as lead_current_stage,p.lead_followup_datetime
                                                                from t_lead2lifecycle_vw l
                                                                inner join t_leadfollowup p
                                                                on l.lead_pk_no = p.lead_pk_no
                                                                where l.lead_sales_agent_pk_no = '  $member_id'
                                                                and p.lead_followup_datetime >= '$finc_start_date'
                                                                and p.lead_followup_datetime <='$finc_close_date'
                                                                and (p.lead_stage_after_followup=4)
                                                                group by l.lead_pk_no");

                $total_priroty = 0;
                if (!empty($yearto_date_priority_lead_total_info)) {
                    foreach ($yearto_date_priority_lead_total_info as $lead) {
                        if ($lead->lead_current_stage == 4) {
                            $total_priroty++;
                        }
                    }
                }



                $lead_sold_for_yeartodate_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                    where lead_sales_agent_pk_no = '$member_id' and
                                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date' and lead_current_stage =7");

                $total_sold_for_yeartodate = count($lead_sold_for_yeartodate_total_info);

                $report_data['new_lead'][$member_id] = count($lead_info);
                $report_data['total_k1'][$member_id] = $yeartodate_total_k1 + $total_sold_for_yeartodate;
                $report_data['new_priority'][$member_id] = $total_priroty_current_month_1;
                $report_data['priority'][$member_id] = $total_priroty;



                $report_data['total_new_lead'][$member_id] = count($lead_total_info);

                $report_data['t_con'][$member_id] = $total_sold;
                $lead_total_info = DB::select("select lead_sales_agent_pk_no, lead_current_stage from t_lead2lifecycle_vw
                                     where lead_sales_agent_pk_no = '  $member_id' and lead_current_stage = '3'");

                $team_member[$cluster['tl_pk_no']][$cluster['user_pk_no']] = $cluster['user_fullname'];
            }
        }
        $categoryname = LookupData::find($request->project_name);
        //dd($categoryname,$tl_arr);
        // dd($report_data);
        //dd($cluster_head1);
        return view("admin.report_module.obm_conversion_report.obm_conversion_report_result", compact('resize_arr', "categoryname", "doj_arr", "dor_arr", "resign_promote_arr", "target_year1", "month", "cluster_head1", "tl_arr", "team_member", 'report_data', 'team_build'));
    }
    public function sales_report_a()
    {
        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata  join s_projectwiseflatlist on s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type =1"); //LookupData::where('lookup_type', 4)->get()
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.report_module.sales_report.sales_report_a.sales_report_a", compact('target_year', 'project_cateory'));
    }
    public function sales_report_a_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $t_month_name = str_pad($month_name, 2, '0', STR_PAD_LEFT);
        //return $t_month_name;
        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $search_date =  $request->target_year . '-' . $request->target_month . '-31';
        $report_setup = DB::table('report_setup')->where('category_id', $request->project_cate)->where('prev_position', '=', 'sa')->where('target_year_id', $fin_year_id)->get();
        // ->whereDate('created_at', '<=',  date('Y-m-d', strtotime($search_date))) dd(  $report_setup , $search_date,$fin_year_id);
        $doj_arr = [];
        $dor_arr = [];
        $resize_arr = [];
        $resign_promote_arr = [];
        $resize_arr['target_month'] = 0;
        $resize_arr['target_ytd'] = 0;
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $target_resign = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('member_id', $data->member_id)
                        ->first();
                    $resize_arr['target_month'] += isset($target_resign->target_month) ? $target_resign->target_month : 0;
                    $resize_arr['target_ytd'] += isset($target_resign->target_ytd) ? $target_resign->target_month : 0;
                    $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                    where  lead_sales_agent_pk_no in ($data->member_id) and
                    YEAR(lead_sold_date_manual) = $request->target_year and
                    MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                    and project_category_pk_no = '$request->project_cate' ");

                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_sale = 0;
                    $cancel_qty = 0;

                    if (!empty($lead_of_this_month)) {
                        foreach ($lead_of_this_month as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                $cancel_qty++;
                                $cancel_sale += $lead->lead_sold_flatcost;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                    $resize_arr['total_sale'] = isset($resize_arr['total_sale']) ? $resize_arr['total_sale'] + $total_sale : $total_sale;
                    $resize_arr['sale_value'] = isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] + $sale_value : $sale_value;
                    $resize_arr['cancel_qty'] = isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['cancel_sale'] = isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] + $cancel_sale : $cancel_sale;
                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                    where lead_sales_agent_pk_no in ($data->member_id) and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                    and lead_current_stage = 7"); //and project_category_pk_no = '$request->project_cate'
                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_qty = 0;

                    $cancel_sale = 0;
                    if (!empty($lead_total_info)) {
                        foreach ($lead_total_info as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                $cancel_qty++;
                                $cancel_sale += $lead->lead_sold_flatcost;

                                $project_wise_sale_cancel[@$lead->Project_pk_no] = isset($project_wise_sale_cancel[@$lead->Project_pk_no]) ? $project_wise_sale_cancel[@$lead->Project_pk_no] + 1 : 1;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;

                                $project_wise_sale_value[@$lead->Project_pk_no] = isset($project_wise_sale_value[@$lead->Project_pk_no]) ? $project_wise_sale_value[@$lead->Project_pk_no] + @$lead->lead_sold_flatcost : @$lead->lead_sold_flatcost;
                                $project_wise_sale_count[@$lead->Project_pk_no] = isset($project_wise_sale_count[@$lead->Project_pk_no]) ? $project_wise_sale_count[@$lead->Project_pk_no] + 1 : 1;
                            }
                        }
                    }
                    $resize_arr['t_total_sale'] = isset($resize_arr['t_total_sale']) ? $resize_arr['t_total_sale'] + $total_sale : $total_sale;
                    $resize_arr['t_sale_value'] = isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] + $sale_value : $sale_value;
                    $resize_arr['t_cancel_qty'] = isset($resize_arr['t_cancel_qty']) ? $resize_arr['t_cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['t_cancel_sale'] = isset($resize_arr['t_cancel_sale']) ? $resize_arr['t_cancel_sale'] + $cancel_sale : $cancel_sale;
                }
            }
        }

        //  dd( $resize_arr);


        $target_setup_data = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('category_id', $request->project_cate)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();

        $target_mm = [];
        $target_ytd = [];
        $target_desgination = [];
        if (!empty($target_setup_data)) {
            foreach ($target_setup_data as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
                $target_desgination[$data->member_id] = $data->designation;
            }
        }

        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.category_lookup_pk_no,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    where category_lookup_pk_no ='$request->project_cate'
                    and team_lead_flag =1");


        $ach_arr = [];
        $tl_wise_mem_arr = [];
        $project_wise_sale_value = [];
        $project_wise_sale_count = [];
        $project_wise_sale_cancel = [];
        $team_build = [];
        $user_id_check = [];
        $search_date =  $request->target_year . '-' . $request->target_month . '-31';

        if (!empty($team_members)) {
            foreach ($team_members as $hod) {
                $get_all_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
                t_teambuild.team_lookup_pk_no FROM t_teambuild
                join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                WHERE (team_lead_user_pk_no='$hod->user_pk_no' and hod_flag =0 and hot_flag=0  and team_lead_flag =0)"); // and team_lead_flag =0
                //dd( $get_all_members);
                if (!empty($get_all_members)) {
                    foreach ($get_all_members as $member) {
                        array_push($team_build, [
                            'user_pk_no' => $member->user_pk_no,
                            'category_lookup_pk_no' => $hod->category_lookup_pk_no,
                            'user_fullname' => $member->user_fullname,
                            'team_lookup_pk_no' => $hod->team_lookup_pk_no,
                            'tl_pk_no' => $hod->user_pk_no,
                            'tl_fullname' => $hod->user_fullname
                        ]);
                    }
                }


                //echo $hod->team_lookup_pk_no."<br/>";
                // $resign_promotion_member = ReportSetup::join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                //     ->where('e_status', '!=', '0')->where('prev_position', 'sa')
                //     ->whereYear('report_setup.created_at', '=',  date("Y", strtotime($search_date)))
                //     ->whereMonth('report_setup.created_at', '>',  intval(date("m", strtotime($search_date))))
                //     ->get();

                $resign_promotion_member = DB::table('report_setup')
                    ->join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('report_setup.category_id', $request->project_cate)
                    ->where('report_setup.prev_position', '=', 'sa')
                    ->where('report_setup.team_id', $hod->team_lookup_pk_no)
                    ->where('report_setup.target_year_id', $fin_year_id)
                    ->whereDate('report_setup.created_at', '>=',  date('Y-m-d', strtotime($search_date)))
                    ->get();

                //dd($resign_promotion_member);
                //  echo   $hod->user_pk_no.'<br>'; //. $search_date.'<br>'. $hod->team_lookup_pk_no

                if (!empty($resign_promotion_member)) {
                    foreach ($resign_promotion_member as $r_member) {

                        if ((!in_array($r_member->member_id, $user_id_check))) {
                            array_push($user_id_check, $r_member->member_id);
                            array_push($team_build, [
                                'user_pk_no' => $r_member->member_id,
                                'category_lookup_pk_no' => $r_member->category_id,
                                'user_fullname' => $r_member->user_fullname,
                                'team_lookup_pk_no' => $r_member->team_id,
                                'tl_pk_no' => $hod->user_pk_no,
                                'tl_fullname' => $hod->user_fullname
                            ]);
                        }
                    }
                }
            }
        }


        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';


        if (!empty($team_build)) {
            foreach ($team_build as $member) {
                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                            where  lead_sales_agent_pk_no in (" . $member['user_pk_no'] . ") and
                            YEAR(lead_sold_date_manual) = $request->target_year and
                            MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                            and project_category_pk_no = '$request->project_cate' ");


                $total_sale = 0;
                $sale_value = 0;

                $cancel_sale = 0;
                $cancel_qty = 0;

                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr[$member['user_pk_no']]['no_of_sale'] = $total_sale;
                $ach_arr[$member['user_pk_no']]['sold_value'] = $sale_value;
                $ach_arr[$member['user_pk_no']]['cancel_qty'] = $cancel_qty;
                $ach_arr[$member['user_pk_no']]['cancel_sale'] = $cancel_sale;
                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_sales_agent_pk_no in (" . $member['user_pk_no'] . ") and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                            and lead_current_stage = 7");


                $total_sale = 0;
                $sale_value = 0;

                $cancel_qty = 0;

                $cancel_sale = 0;
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;

                            $project_wise_sale_cancel[@$lead->Project_pk_no] = isset($project_wise_sale_cancel[@$lead->Project_pk_no]) ? $project_wise_sale_cancel[@$lead->Project_pk_no]++ : 1;
                        } else {
                            $sale_value += $lead->lead_sold_flatcost;

                            $project_wise_sale_value[@$lead->Project_pk_no] = isset($project_wise_sale_value[@$lead->Project_pk_no]) ? $project_wise_sale_value[@$lead->Project_pk_no] + @$lead->lead_sold_flatcost : @$lead->lead_sold_flatcost;
                            $project_wise_sale_count[@$lead->Project_pk_no] = isset($project_wise_sale_count[@$lead->Project_pk_no]) ? $project_wise_sale_count[@$lead->Project_pk_no] + 1 : 1;
                        }
                    }
                }
                // echo $member['user_pk_no'];
                $ach_arr[$member['user_pk_no']]['ytb_no_of_sale'] = $total_sale;
                $ach_arr[$member['user_pk_no']]['ytb_sold_value'] = $sale_value;
                $ach_arr[$member['user_pk_no']]['ytb_cancel_qty'] = $cancel_qty;
                $ach_arr[$member['user_pk_no']]['ytb_cancel_sale'] = $cancel_sale;
                $tl_wise_mem_arr[$member['tl_pk_no'] . '_' . $member['tl_fullname']][$member['user_pk_no']] = $member['user_pk_no'] . '_' . $member['user_fullname'];
            }
        }

        //         $project_name = DB::select("select s_lookdata.lookup_pk_no,s_lookdata.lookup_name , s_projectwiseflatlist.*,
        //             count(s_projectwiseflatlist.flatlist_pk_no) as number_of_inventory,sum(s_projectwiseflatlist.flat_cost) as inventory_value
        //             from s_lookdata
        //             join s_projectwiseflatlist on s_projectwiseflatlist.project_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type =1
        //             and s_projectwiseflatlist.project_lookup_pk_no in (select Project_pk_no from t_lead2lifecycle_vw where MONTH(lead_sold_date_manual) <= $month_name and project_category_pk_no = '$request->project_cate')
        //             and s_projectwiseflatlist.flat_status=0
        //             group by  s_lookdata.lookup_pk_no"); //LookupData::where('lookup_type', 4)->get() and s_projectwiseflatlist.flat_status=0
        // dd($project_name);
        $month_name = $request->target_month;
        $target_year = $request->target_year;
        if ($month_name < 10) {
            $month_name = '0' . $month_name;
        }
        $target_date = $target_year . "-" . $month_name . "-" . "01";


        $category_cond = isset($request->project_cate) ? 'and `sp`.`category_lookup_pk_no` =' . $request->project_cate : '';
        $month = date("F", mktime(0, 0, 0, $month_name, 10));

        $project_name = DB::select("SELECT `sp`.`flatlist_pk_no`, `c`.`lookup_pk_no` AS `cate_id`, `c`.`lookup_name` AS `cate`, `p`.`lookup_pk_no` AS `project_id`,sum(`sp`.`flat_cost`) as inventory_value,`sp`.`target_closing_month`,
        `p`.`lookup_name` AS `project_name`, COUNT(`p`.`lookup_pk_no`) AS total  FROM `s_projectwiseflatlist` AS `sp`
        INNER JOIN `s_lookdata` AS `p` ON `sp`.`project_lookup_pk_no` = `p`.`lookup_pk_no`
        INNER JOIN `s_lookdata` AS `c` ON `sp`.`category_lookup_pk_no` = `c`.`lookup_pk_no`
        where sp.lead_type ='1'   $category_cond
        GROUP BY `p`.`lookup_pk_no`");
        //dd(   $target_date);
        $total_sell_arr = [];
        $sum = 0;
        // dd($project_name);
        if (!empty($project_name)) {
            foreach ($project_name as $data) {
                $sale = 0;
                $share_unit = 0;
                $priority = 0;
                $cancel_unit = 0;
                $k = 0;

                $total_inventory = count(DB::table('s_projectwiseflatlist')->where('project_lookup_pk_no', $data->project_id)->where('lead_type', 1)->where('category_lookup_pk_no', $request->project_cate)->get()); //->where('flat_status',0) ->where('created_at', '<', $target_date)

                $project_pk_no = $data->project_id;
                $lead_info  = DB::select("SELECT *
                FROM t_lead2lifecycle_vw t
                WHERE t.Project_pk_no=   $project_pk_no  and lead_current_stage =7 and lead_type =1 and project_category_pk_no = '$request->project_cate'
                and MONTH(lead_sold_date_manual) = '$request->target_month' and YEAR(lead_sold_date_manual) = '$request->target_year' ");


                // echo   $project_pk_no .'<br>';

                // echo "select * from t_lead2lifecycle_vw where Project_pk_no =  '$data->project_id'
                // and YEAR(lead_sold_date_manual) = $target_year and lead_sold_date_manual < '$target_date' and
                // lead_type ='$lead_type'<br>";

                if (!empty($lead_info)) {
                    foreach ($lead_info as $ld) {
                        if ($ld->lead_current_stage == 7) {
                            $sale++;
                        }
                        if ($ld->is_cancel == 1) {
                            $cancel_unit++;
                        }

                        $share_unit++;
                    }
                }
                $target_date = $target_year . "-" . $month_name . "-" . "31";
                $lead_info  = DB::select("SELECT *
                FROM t_lead2lifecycle_vw t
                WHERE t.Project_pk_no=   $project_pk_no  and lead_type =1  and lead_current_stage =7 and is_cancel is null and lead_sold_date_manual < '$target_date'");



                $total_sell_arr[$data->project_id]['k'] = $k;
                $total_sell_arr[$data->project_id]['project_name'] = $data->project_name;
                $total_sell_arr[$data->project_id]['inventory_value'] = $data->inventory_value;
                $total_sell_arr[$data->project_id]['target_closing_month'] = $data->target_closing_month;

                $total_sell_arr[$data->project_id]['sale'] = $sale;
                $total_sell_arr[$data->project_id]['priority'] = $priority;
                $total_sell_arr[$data->project_id]['cancel_unit'] = $cancel_unit;
                $total_sell_arr[$data->project_id]['t_sale'] =  $sale; // count($lead_info)
                $total_sell_arr[$data->project_id]['share_unit'] = @$total_inventory -  count($lead_info); // -  count($lead_info)
            }
        }
        //dd(  $total_sell_arr,        $project_name);
        // dd($total_sell_arr);
        //dd($target_ytd, $ach_arr, $project_wise_sale_cancel, $project_name);
        // dd($total_sell_arr);
        // dd($total_sell_arr);
        return view(
            "admin.report_module.sales_report.sales_report_a.sales_report_a_result",
            compact('team_build', 'doj_arr', 'dor_arr', 'resize_arr', 'target_desgination', 'project_wise_sale_value', 'project_wise_sale_count', 'project_wise_sale_cancel', 'project_name', 'month', 'tl_wise_mem_arr', 'ach_arr', 'target_mm', 'target_ytd', 'total_sell_arr')
        );
    }
    public function sfs()
    {
        // $team = DB::table('s_lookdata')
        //     ->join('t_teambuild', 't_teambuild.team_lookup_pk_no', 's_lookdata.lookup_pk_no')
        //     ->select('s_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
        //     ->whereIn('t_teambuild.lead_type', [2, 4, 5, 10])
        //     ->groupBy('s_lookdata.lookup_name')
        //     ->get();

        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata
          join s_projectwiseflatlist on
          s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no
          where lead_type in (2, 4, 5, 10,11,7,9)"); //LookupData::where('lookup_type', 4)->get()

        $target_year = YearSetup::orderby("id", "desc")->get();

        $lead_type_arr = config('static_arrays.months_arr');

        return view("admin.report_module.sfs.sfs", compact('project_cateory', 'target_year', 'lead_type_arr'));
    }
    public function sfs_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }


        $search_date =  $request->target_year . '-' . $request->target_month . '-31';
        $search_date = date('Y-m-d', strtotime($search_date));
        $report_setup = DB::table('report_setup')->where('category_id', $request->project_category)->where('prev_position', '=', 'sa')->where('target_year_id', $fin_year_id)->get();
        // dd( $report_setup );
        $target_mm = [];
        $target_ytd = [];
        $resize_arr = [];
        $resign_promote_arr = [];
        $resize_arr['target_month'] = 0;
        $resize_arr['target_ytd'] = 0;

        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {



                if ($data->e_status != "0") {

                    $target_resign = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('member_id', $data->member_id)
                        ->first();
                    $resize_arr['target_month'] += isset($target_resign->target_month) ? $target_resign->target_month : 0;
                    $resize_arr['target_ytd'] += isset($target_resign->target_ytd) ? $target_resign->target_month : 0;
                    $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                    where  lead_sales_agent_pk_no in ($data->member_id) and
                    YEAR(lead_sold_date_manual) = $request->target_year and
                    MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                    and lead_type = '$request->project_category'");
                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_sale = 0;
                    $cancel_qty = 0;

                    if (!empty($lead_of_this_month)) {
                        foreach ($lead_of_this_month as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                $cancel_qty++;
                                $cancel_sale += $lead->lead_sold_flatcost;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                    $resize_arr['total_sale'] = isset($resize_arr['total_sale']) ? $resize_arr['total_sale'] + $total_sale : $total_sale;
                    $resize_arr['sale_value'] = isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] + $sale_value : $sale_value;
                    $resize_arr['cancel_qty'] = isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['cancel_sale'] = isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] + $cancel_sale : $cancel_sale;
                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                    where lead_sales_agent_pk_no in ($data->member_id) and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                    and lead_current_stage = 7 and lead_type = '$request->project_category'"); //and project_category_pk_no = '$request->project_cate'
                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_qty = 0;

                    $cancel_sale = 0;
                    if (!empty($lead_total_info)) {
                        foreach ($lead_total_info as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                $cancel_qty++;
                                $cancel_sale += $lead->lead_sold_flatcost;

                                $project_wise_sale_cancel[@$lead->Project_pk_no] = isset($project_wise_sale_cancel[@$lead->Project_pk_no]) ? $project_wise_sale_cancel[@$lead->Project_pk_no] + 1 : 1;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;

                                $project_wise_sale_value[@$lead->Project_pk_no] = isset($project_wise_sale_value[@$lead->Project_pk_no]) ? $project_wise_sale_value[@$lead->Project_pk_no] + @$lead->lead_sold_flatcost : @$lead->lead_sold_flatcost;
                                $project_wise_sale_count[@$lead->Project_pk_no] = isset($project_wise_sale_count[@$lead->Project_pk_no]) ? $project_wise_sale_count[@$lead->Project_pk_no] + 1 : 1;
                            }
                        }
                    }
                    $resize_arr['t_total_sale'] = isset($resize_arr['t_total_sale']) ? $resize_arr['t_total_sale'] + $total_sale : $total_sale;
                    $resize_arr['t_sale_value'] = isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] + $sale_value : $sale_value;
                    $resize_arr['t_cancel_qty'] = isset($resize_arr['t_cancel_qty']) ? $resize_arr['t_cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['t_cancel_sale'] = isset($resize_arr['t_cancel_sale']) ? $resize_arr['t_cancel_sale'] + $cancel_sale : $cancel_sale;
                }
            }
        }





        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $report_setup = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();
        $target_mm = [];
        $target_ytd = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }

        //$target_mm = [];
        // $team_target = DB::table('t_teamtarget')
        //     ->where('yy_mm', $target_year . '-' . $month_name)
        //     ->get();
        // if (!empty($team_target)) {
        //     foreach ($team_target as $data) {
        //         $target_mm[$data->user_pk_no] = $data->target_amount;
        //     }
        // }
        // $check = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT);
        // $check2 = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT);
        // $team_target = DB::select("SELECT user_pk_no,SUM(target_amount) as target_total FROM `t_teamtarget` WHERE `yy_mm` >= '$check' AND `yy_mm` <= '$check2' GROUP BY user_pk_no");
        // if (!empty($team_target)) {
        //     foreach ($team_target as $data) {
        //         $target_ytd[$data->user_pk_no] = $data->target_total;
        //     }
        // }

        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    where lead_type ='$request->project_category' and team_lead_flag=1 group by s_user.user_pk_no");

        $ach_arr = [];
        $tl_wise_mem_arr = [];
        if (!empty($team_members)) {
            foreach ($team_members as $hot) {
                $get_all_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    WHERE (team_lead_user_pk_no='$hot->user_pk_no' and hod_flag =0 and
                    hot_flag=0 and team_lead_flag =0 and
                    lead_type ='$request->project_category')");



                if (!empty($get_all_members)) {
                    foreach ($get_all_members as $member) {
                        $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                            where  lead_sales_agent_pk_no in ($member->user_pk_no) and
                            YEAR(lead_sold_date_manual) = $request->target_year and lead_type='$request->project_category' and
                            MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7");


                        $total_sale = 0;
                        $sale_value = 0;

                        $cancel_sale = 0;
                        $cancel_qty = 0;

                        if (!empty($lead_of_this_month)) {
                            foreach ($lead_of_this_month as $lead) {
                                $total_sale++;
                                if ($lead->is_cancel == 1) {
                                    $cancel_qty++;
                                    $cancel_sale += $lead->lead_sold_flatcost;
                                } else {
                                    $sale_value += $lead->lead_sold_flatcost;
                                }
                            }
                        }
                        $ach_arr[$member->user_pk_no]['no_of_sale'] = $total_sale;
                        $ach_arr[$member->user_pk_no]['sold_value'] = $sale_value;
                        $ach_arr[$member->user_pk_no]['cancel_qty'] = $cancel_qty;
                        $ach_arr[$member->user_pk_no]['cancel_sale'] = $cancel_sale;

                        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                        $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_sales_agent_pk_no in ($member->user_pk_no) and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                            and lead_current_stage = 7");
                        $total_sale = 0;
                        $sale_value = 0;

                        $cancel_qty = 0;

                        $cancel_sale = 0;
                        if (!empty($lead_total_info)) {
                            foreach ($lead_total_info as $lead) {
                                $total_sale++;
                                if ($lead->is_cancel == 1) {
                                    $cancel_qty++;
                                    $cancel_sale += $lead->lead_sold_flatcost;
                                } else {
                                    $sale_value += $lead->lead_sold_flatcost;
                                }
                            }
                        }
                        $ach_arr[$member->user_pk_no]['ytb_no_of_sale'] = $total_sale;
                        $ach_arr[$member->user_pk_no]['ytb_sold_value'] = $sale_value;
                        $ach_arr[$member->user_pk_no]['ytb_cancel_qty'] = $cancel_qty;
                        $ach_arr[$member->user_pk_no]['ytb_cancel_sale'] = $cancel_sale;
                        $tl_wise_mem_arr[$hot->user_pk_no . '_' . $hot->user_fullname][] = $member->user_pk_no . '_' . $member->user_fullname;
                    }
                }
            }
        }

        // dd($tl_wise_mem_arr,$ach_arr);
        return view("admin.report_module.sfs.sfs_result", compact('month', 'tl_wise_mem_arr', 'ach_arr', 'resize_arr', 'target_mm', 'target_ytd', 'doj_arr', 'dor_arr'));
    }
    public function bd_land()
    {
        // $team = DB::table('s_lookdata')
        //     ->join('t_teambuild', 't_teambuild.team_lookup_pk_no', 's_lookdata.lookup_pk_no')
        //     ->select('s_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
        //     ->whereIn('t_teambuild.lead_type', [3])
        //     ->groupBy('s_lookdata.lookup_name')
        //     ->get();

        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata
            join s_projectwiseflatlist on
            s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no
            where lead_type in (3)");
        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.bd_land.bd_land", compact('project_cateory', 'target_year'));
    }
    public function bd_land_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }

        $search_date =  $request->target_year . '-' . $request->target_month . '-31';
        $search_date = date('Y-m-d', strtotime($search_date));
        $report_setup = DB::table('report_setup')->where('category_id', $request->project_category)->where('prev_position', '=', 'sa')->where('target_year_id', $fin_year_id)->get();
        $target_mm = [];
        $target_ytd = [];
        $resize_arr = [];
        $resign_promote_arr = [];
        $resize_arr['target_month'] = 0;
        $resize_arr['target_ytd'] = 0;

        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {

                // $target_mm[$data->member_id] = $data->target_month;    //commented by anik vai
                //$target_ytd[$data->member_id] = $data->target_ytd;      //commented by anik vai

                if ($data->e_status != "0") {

                    $target_resign = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('member_id', $data->member_id)
                        ->first();
                    $resize_arr['target_month'] += isset($target_resign->target_month) ? $target_resign->target_month : 0;
                    $resize_arr['target_ytd'] += isset($target_resign->target_ytd) ? $target_resign->target_month : 0;
                    $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                    where  lead_sales_agent_pk_no in ($data->member_id) and
                    YEAR(lead_sold_date_manual) = $request->target_year and
                    MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                    and project_category_pk_no = '$request->project_category' and lead_type=3 ");

                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_sale = 0;
                    $cancel_qty = 0;

                    if (!empty($lead_of_this_month)) {
                        foreach ($lead_of_this_month as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                $cancel_qty++;
                                $cancel_sale += $lead->given_value;
                            } else {
                                $sale_value += $lead->given_value;
                            }
                        }
                    }
                    $resize_arr['total_sale'] = isset($resize_arr['total_sale']) ? $resize_arr['total_sale'] + $total_sale : $total_sale;
                    $resize_arr['sale_value'] = isset($resize_arr['sale_value']) ? $resize_arr['sale_value'] + $sale_value : $sale_value;
                    $resize_arr['cancel_qty'] = isset($resize_arr['cancel_qty']) ? $resize_arr['cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['cancel_sale'] = isset($resize_arr['cancel_sale']) ? $resize_arr['cancel_sale'] + $cancel_sale : $cancel_sale;
                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                    where lead_sales_agent_pk_no in ($data->member_id) and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                    and lead_current_stage = 7 and lead_type=3"); //and project_category_pk_no = '$request->project_cate'
                    $total_sale = 0;
                    $sale_value = 0;

                    $cancel_qty = 0;

                    $cancel_sale = 0;
                    if (!empty($lead_total_info)) {
                        foreach ($lead_total_info as $lead) {
                            $total_sale++;
                            if ($lead->is_cancel == 1) {
                                if ($lead->lead_type == 3) {
                                    $cancel_qty++;
                                    $cancel_sale += $lead->given_value;
                                } else {
                                    $cancel_qty++;
                                    $cancel_sale += $lead->lead_sold_flatcost;
                                }


                                $project_wise_sale_cancel[@$lead->Project_pk_no] = isset($project_wise_sale_cancel[@$lead->Project_pk_no]) ? $project_wise_sale_cancel[@$lead->Project_pk_no] + 1 : 1;
                            } else {
                                //$sale_value += $lead->given_value;
                                if ($lead->lead_type == 3) {
                                    $sale_value += $lead->given_value;
                                } else {
                                    $sale_value += $lead->lead_sold_flatcost;
                                }
                                $project_wise_sale_value[@$lead->Project_pk_no] = isset($project_wise_sale_value[@$lead->Project_pk_no]) ? $project_wise_sale_value[@$lead->Project_pk_no] + @$lead->lead_sold_flatcost : @$lead->lead_sold_flatcost;
                                $project_wise_sale_count[@$lead->Project_pk_no] = isset($project_wise_sale_count[@$lead->Project_pk_no]) ? $project_wise_sale_count[@$lead->Project_pk_no] + 1 : 1;
                            }
                        }
                    }
                    $resize_arr['t_total_sale'] = isset($resize_arr['t_total_sale']) ? $resize_arr['t_total_sale'] + $total_sale : $total_sale;
                    $resize_arr['t_sale_value'] = isset($resize_arr['t_sale_value']) ? $resize_arr['t_sale_value'] + $sale_value : $sale_value;
                    $resize_arr['t_cancel_qty'] = isset($resize_arr['t_cancel_qty']) ? $resize_arr['t_cancel_qty'] + $cancel_qty : $cancel_qty;
                    $resize_arr['t_cancel_sale'] = isset($resize_arr['t_cancel_sale']) ? $resize_arr['t_cancel_sale'] + $cancel_sale : $cancel_sale;
                }
            }
        }

        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.category_lookup_pk_no,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    where team_lead_flag=1 and lead_type = 3"); //category_lookup_pk_no ='$request->project_category' and
        // dd( $team_members);



        $user_id_check = [];
        $ach_arr = [];
        $team_build = [];
        $tl_wise_mem_arr = [];
        $search_date =  $request->target_year . '-' . $request->target_month . '-31';


        if (!empty($team_members)) {
            foreach ($team_members as $hot) {

                $get_all_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.category_lookup_pk_no,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    WHERE (team_lead_user_pk_no='$hot->user_pk_no' and hod_flag =0 and
                    hot_flag=0 and team_lead_flag =0)");
                // dd(    $get_all_members,$hot->user_pk_no);
                if (!empty($get_all_members)) {
                    foreach ($get_all_members as $member) {
                        array_push($team_build, [
                            'user_pk_no' => $member->user_pk_no,
                            'category_lookup_pk_no' => $hot->category_lookup_pk_no,
                            'user_fullname' => $member->user_fullname,
                            'team_lookup_pk_no' => $hot->team_lookup_pk_no,
                            'tl_pk_no' => $hot->user_pk_no,
                            'tl_fullname' => $hot->user_fullname
                        ]);
                    }
                }
                $resign_promotion_member = DB::table('report_setup')
                    ->join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('report_setup.category_id', $request->project_category)
                    ->where('report_setup.prev_position', '=', 'sa')
                    ->where('report_setup.team_id', $hot->team_lookup_pk_no)
                    ->where('report_setup.target_year_id', $fin_year_id)
                    ->whereDate('report_setup.created_at', '>=',  date('Y-m-d', strtotime($search_date)))
                    ->get();

                //dd($resign_promotion_member);
                //  echo   $hot->user_pk_no.'<br>'; //. $search_date.'<br>'. $hod->team_lookup_pk_no
                // dd(!empty($resign_promotion_member));
                if (!empty($resign_promotion_member)) {
                    // dd($resign_promotion_member);
                    foreach ($resign_promotion_member as $r_member) {

                        if ((!in_array($r_member->member_id, $user_id_check))) {
                            array_push($user_id_check, $r_member->member_id);
                            array_push($team_build, [
                                'user_pk_no' => $r_member->member_id,
                                'category_lookup_pk_no' => $r_member->category_id,
                                'user_fullname' => $r_member->user_fullname,
                                'team_lookup_pk_no' => $r_member->team_id,
                                'tl_pk_no' => $hot->user_pk_no,
                                'tl_fullname' => $hot->user_fullname
                            ]);
                        }
                    }
                }
            }
        }
        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

        //dd($team_build);
        if (!empty($team_build)) {
            foreach ($team_build as $member) {
                $user_pk_no = $member['user_pk_no'];

                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                            where  lead_sales_agent_pk_no in (" . $member['user_pk_no'] . ") and
                            YEAR(lead_sold_date_manual) = $request->target_year and
                            MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7
                            and lead_type = 3"); //

                // $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                //             where  lead_sales_agent_pk_no in ($user_pk_no) and
                //             YEAR(lead_sold_date_manual) = $request->target_year and
                //             MONTH(lead_sold_date_manual) = '$month_name' and lead_type = 3");
                // dd($lead_of_this_month);
                $total_sale = 0;
                $sale_value = 0;

                $cancel_sale = 0;
                $cancel_qty = 0;
                // dd($lead_of_this_month);
                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->given_value;
                        } else {
                            $sale_value += $lead->given_value;
                        }
                    }
                }
                $ach_arr[$member['user_pk_no']]['no_of_sale'] = $total_sale;
                $ach_arr[$member['user_pk_no']]['sold_value'] = $sale_value;
                $ach_arr[$member['user_pk_no']]['cancel_qty'] = $cancel_qty;
                $ach_arr[$member['user_pk_no']]['cancel_sale'] = $cancel_sale;


                $user_pk_no = $member['user_pk_no'];
                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_sales_agent_pk_no in ($user_pk_no)
                            and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'"); //and lead_type = 3

                // dd($lead_total_info);
                $total_sale = 0;
                $sale_value = 0;

                $cancel_qty = 0;

                $cancel_sale = 0;
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            if ($lead->lead_type == 3) {
                                $cancel_qty++;
                                $cancel_sale += $lead->given_value;
                            } else {
                                $cancel_qty++;
                                $cancel_sale += $lead->lead_sold_flatcost;
                            }
                        } else {
                            if ($lead->lead_type == 3) {
                                $sale_value += $lead->given_value;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                }
                $ach_arr[$member['user_pk_no']]['ytb_no_of_sale'] = $total_sale;
                $ach_arr[$member['user_pk_no']]['ytb_sold_value'] = $sale_value;
                $ach_arr[$member['user_pk_no']]['ytb_cancel_qty'] = $cancel_qty;
                $ach_arr[$member['user_pk_no']]['ytb_cancel_sale'] = $cancel_sale;
                $tl_wise_mem_arr[$member['tl_pk_no'] . '_' .  $member['tl_fullname']][] = $member['user_pk_no'] . '_' . $member['user_fullname'];
            }
        }
        // dd($tl_wise_mem_arr, $team_build);


        return view("admin.report_module.sfs.sfs_result", compact('resize_arr', 'doj_arr', 'dor_arr', 'month', 'tl_wise_mem_arr', 'ach_arr', 'target_mm', 'target_ytd'));
    }

    public function hollow_block_sales()
    {
        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata
            join s_projectwiseflatlist on
            s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no
            where lead_type in (8)");

        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.hollow_block_sales.hollow_block_sales", compact('project_cateory', 'target_year'));
    }
    public function hollow_block_sales_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $lookup_arr = [4, 5];
        $project_cat = $project_area = [];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')
            ->where('target_year_id', $fin_year_id)
            ->get();
        $ach_arr = [];
        $doj_arr = [];
        $dor_arr = [];
        $total_resign = 0;
        $total_y_resign = 0;
        $target_resize = 0;
        $target_y_resize = 0;
        $target_mm = [];
        $target_ytd = [];

        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                            where  lead_sales_agent_pk_no in ($data->member_id) and
                            YEAR(lead_sold_date_manual) = $request->target_year and
                            MONTH(lead_sold_date_manual) = '$month_name' and lead_type = 8
                            and lead_current_stage = 7");
                    $total_resign += count($lead_of_this_month);
                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_sales_agent_pk_no in ($data->member_id) and
                            (YEAR(lead_sold_date_manual) >= $starting_year or
                            MONTH(lead_sold_date_manual) >= '$starting_month' or
                            YEAR(lead_sold_date_manual) <= $target_year or
                            MONTH(lead_sold_date_manual) <= '$month_name')
                            and lead_type = 8 and lead_current_stage = 7");
                    $total_y_resign += count($lead_total_info);

                    $report_setup = DB::table('target_sales_a_setup')
                        ->where('finc_yy', $fin_year_id)
                        ->where('team_id', $request->team_name)
                        ->where('target_yy', $request->target_year)
                        ->where('target_mm', $request->target_month)
                        ->first();
                    $target_resize += isset($report_setup->target_month) ? $report_setup->target_month : 0;
                    $target_y_resize += isset($report_setup->target_ytd) ? $report_setup->target_ytd : 0;
                }
            }
        }
        $ach_arr['resign_total']['resign_total'] = $total_resign;

        $ach_arr['resign_y_total']['resign_y_total'] = $total_y_resign;

        $report_setup = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();

        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }
        $target_mm['resize'] = $target_resize;
        $target_ytd['resize_t'] = $target_y_resize;
        $tl_wise_mem_arr = [];

        $get_all_members = DB::select("SELECT * FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    WHERE (hod_flag =0 and
                    hot_flag=0 and team_lead_flag =0 and
                    lead_type ='8')");

        if (!empty($get_all_members)) {
            foreach ($get_all_members as $member) {
                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                            where  lead_sales_agent_pk_no in ($member->user_pk_no) and
                            YEAR(lead_sold_date_manual) = $request->target_year and
                            MONTH(lead_sold_date_manual) = '$month_name'
                            and lead_current_stage = 7");

                $ach_arr[$member->user_pk_no]['no_of_sale'] = count($lead_of_this_month);

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_sales_agent_pk_no in ($member->user_pk_no) and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                             and lead_current_stage = 7");

                $ach_arr[$member->user_pk_no]['ytb_ach'] = count($lead_total_info);
            }
        }
        //dd($target_mm);
        // dd($tl_wise_mem_arr,$ach_arr);
        return view("admin.report_module.hollow_block_sales.hollow_block_sales_result", compact('month', 'project_cat', 'project_area', 'get_all_members', 'ach_arr', 'target_mm', 'target_ytd', 'doj_arr', 'dor_arr'));
    }
    public function hot_performance()
    {
        $lead_type = config('static_arrays.lead_type');
        $team = DB::table('s_lookdata')
            ->join('t_teambuild', 't_teambuild.team_lookup_pk_no', 's_lookdata.lookup_pk_no')
            ->select('s_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
            ->whereIn('t_teambuild.lead_type', [1])
            ->groupBy('s_lookdata.lookup_name')
            ->get();
        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.hot_performance.hot_performance", compact('lead_type', 'team', 'target_year'));
    }

    public function hot_performance_result(Request $request)
    {
        //memory occupy
        set_time_limit(0);

        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', 0);
        $lead_type_cond = isset($request->lead_type) ? " and lead_type = '$request->lead_type'" : '';
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $lookup_arr = [4, 5];
        $project_cat = $project_area = [];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }

        $ach_arr = [];
        $doj_arr = [];
        $dor_arr = [];
        $total_resign = 0;
        $total_y_resign = 0;
        $target_resize = 0;
        $target_y_resize = 0;
        $target_mm = [];
        $target_ytd = [];
        $report_setup = DB::table('report_setup')
            ->where('target_year_id', $fin_year_id)
            ->get();
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $resign_promote_arr = [];

        // $report_setup = DB::table('target_sales_a_setup')
        //     ->where('finc_yy', $fin_year_id)
        //     ->where('team_id', $request->team_name)
        //     ->where('target_yy', $request->target_year)
        //     ->where('target_mm', $request->target_month)
        //     ->get();

        // $target_mm = [];
        // // $team_target = DB::table('t_teamtarget')
        // //     ->where('yy_mm', $target_year . '-' . $month_name)
        // //     ->get();
        // if (!empty($report_setup)) {
        //     foreach ($report_setup as $data) {
        //         $target_mm[$data->member_id] = $data->target_month;
        //         $target_ytd[$data->member_id] = $data->target_ytd;
        //     }
        // }
        // $check = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT);
        // $check2 = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT);
        // $team_target = DB::select("SELECT user_pk_no,SUM(target_amount) as target_total FROM `t_teamtarget` WHERE `yy_mm` >= '$check' AND `yy_mm` <= '$check2' GROUP BY user_pk_no");
        // // if (!empty($team_target)) {
        // //     foreach ($team_target as $data) {
        // //         $target_ytd[$data->user_pk_no] = $data->target_total;
        // //     }
        // // }

        $total_sale = 0;
        $sale_value = 0;

        $cancel_sale = 0;
        $cancel_qty = 0;

        $r_total_sale = 0;
        $r_sale_value = 0;

        $r_cancel_qty = 0;

        $r_cancel_sale = 0;

        $search_date =  $request->target_year . '-' . $request->target_month . '-01';

        $resign_arr_target = DB::table('report_setup')
            ->where('target_year_id', $fin_year_id)
            ->where('e_status', '!=', 0)
            ->where('lead_type', $request->lead_type)
            ->where('prev_position', 'tl')
            ->whereYear('created_at', '=',  date("Y", strtotime($search_date)))
            ->whereMonth('created_at', '>',  date("m", strtotime($search_date)))
            ->get();

        if (!empty($resign_arr_target)) {
            foreach ($resign_arr_target as $row) {
                $resign_mem_target = DB::table('target_sales_a_setup')
                    ->where('finc_yy', $fin_year_id)
                    ->where('member_id', $row->member_id)
                    ->where('target_yy', $request->target_year)
                    ->where('target_mm', $request->target_month)
                    ->first();
                $target_resize += !empty($resign_mem_target->target_month) ? $resign_mem_target->target_month : 0;
                $target_y_resize += !empty($resign_mem_target->target_ytd) ? $resign_mem_target->target_ytd : 0;

                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                        where  lead_sales_agent_pk_no in ( $row->member_id) and
                        YEAR(lead_sold_date_manual) = $request->target_year
                        and lead_type = '$request->lead_type' and
                        MONTH(lead_sold_date_manual) = '$month_name'
                        and lead_current_stage = 7");



                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr['r_no_of_sale'] = isset($ach_arr['r_no_of_sale']) ?   $ach_arr['r_no_of_sale'] + $total_sale :  $total_sale;
                $ach_arr['r_sold_value'] = isset($ach_arr['r_sold_value']) ? $ach_arr['r_sold_value'] + $sale_value :  $sale_value;
                $ach_arr['r_cancel_qty'] = isset($ach_arr['r_cancel_qty']) ?  $ach_arr['r_cancel_qty'] + $cancel_qty : $cancel_qty;
                $ach_arr['r_cancel_sale'] = isset($ach_arr['r_cancel_sale']) ? $ach_arr['r_cancel_sale'] + $cancel_sale : $cancel_sale;
                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                        where lead_sales_agent_pk_no in ( $row->member_id) and
                        lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                        and lead_current_stage = 7");

                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $r_total_sale++;
                        if ($lead->is_cancel == 1) {
                            $r_cancel_qty++;
                            $r_cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $r_sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr['r_ytb_no_of_sale'] =  isset($ach_arr['r_ytb_no_of_sale']) ? $ach_arr['r_ytb_no_of_sale'] + $r_total_sale : $r_total_sale;
                $ach_arr['r_ytb_sold_value'] = isset($ach_arr['r_ytb_sold_value'])   ? $ach_arr['r_ytb_sold_value'] + $r_sale_value : $r_sale_value;
                $ach_arr['r_ytb_cancel_qty'] = isset($ach_arr['r_ytb_cancel_qty']) ?  $ach_arr['r_ytb_cancel_qty'] + $r_cancel_qty :  $r_cancel_qty;
                $ach_arr['r_ytb_cancel_sale'] = isset($ach_arr['r_ytb_cancel_sale']) ? $ach_arr['r_ytb_cancel_sale'] + $r_cancel_sale : $r_cancel_sale;
            }
        }

        $target_mm['resize'] = $target_resize;
        $target_ytd['resize_t'] = $target_y_resize;
        $tl_wise_mem_arr = [];

        $get_hot_members = DB::select("SELECT * FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    WHERE (team_lead_flag =1 and agent_type = 2   $lead_type_cond )
                    group by t_teambuild.category_lookup_pk_no,t_teambuild.user_pk_no");


        $team_build = [];
        $user_id_check = [];
        if (!empty($get_hot_members)) {
            foreach ($get_hot_members as $hod) {
                array_push($team_build, [
                    'user_pk_no' => $hod->user_pk_no,
                    'category_lookup_pk_no' => $hod->category_lookup_pk_no,
                    'user_fullname' => $hod->user_fullname,
                    'team_lookup_pk_no' => $hod->team_lookup_pk_no,
                    'hot_user_pk_no' => $hod->hot_user_pk_no
                ]);

                //echo $hod->team_lookup_pk_no."<br/>";
                $resign_promotion_member = ReportSetup::join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('e_status', '!=', '0')
                    ->where('lead_type', $request->lead_type)->where('prev_position', 'tl')
                    ->whereYear('report_setup.created_at', '=',  date("Y", strtotime($search_date)))
                    ->whereMonth('report_setup.created_at', '>',  date("m", strtotime($search_date)))
                    ->get();
                // echo   $hod->hot_user_pk_no.'<br>'; // $resign_promotion_member.'<br>'. $search_date.'<br>'.
                //dd($resign_promotion_member);
                if (!empty($resign_promotion_member)) {
                    foreach ($resign_promotion_member as $r_member) {

                        if ((!in_array($r_member->member_id, $user_id_check))) {
                            array_push($user_id_check, $r_member->member_id);
                            array_push($team_build, [
                                'user_pk_no' => $r_member->member_id,
                                'category_lookup_pk_no' => $r_member->category_id,
                                'user_fullname' => $r_member->user_fullname,
                                'team_lookup_pk_no' => $r_member->team_id,
                                'hot_user_pk_no' => 'report_setup'
                            ]);
                        }
                    }
                }
            }
        }
        //dd(  $team_build);



        if (!empty($team_build)) {
            foreach ($team_build as $hot) {

                $team_member = DB::select("select group_concat(user_pk_no) as members
                                    from t_teambuild
                                    where team_lead_user_pk_no ='" . $hot['user_pk_no'] . "'
                                    and hod_flag = 0 and hot_flag =0 and  team_lead_flag = 0
                                    ")[0]->members;
                $team_member = isset($team_member) ? $team_member : 0;

                $target_setup_of_member = DB::select("select sum(target_month)  as total_target_month,sum(target_ytd) as total_target_ytd from target_sales_a_setup where finc_yy='$fin_year_id'
                and target_yy='$request->target_year' and target_mm ='$request->target_month' and member_id in ( $team_member)");
                $target_mm[$hot['user_pk_no']] = isset($target_setup_of_member[0]->total_target_month) ? $target_setup_of_member[0]->total_target_month : 0;
                $target_ytd[$hot['user_pk_no']] = isset($target_setup_of_member[0]->total_target_ytd) ? $target_setup_of_member[0]->total_target_ytd : 0;

                try {
                    if ($request->lead_type == 1 || $request->lead_type == 3 || $request->lead_type == 8) {
                        $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                        where  lead_tl_pk_no = '" . $hot['user_pk_no'] . "' and
                        YEAR(lead_sold_date_manual) = $request->target_year
                        and lead_type = '$request->lead_type' and
                        MONTH(lead_sold_date_manual) = '$month_name'
                        and lead_current_stage = 7 and lead_sales_agent_pk_no != lead_tl_pk_no
                        and project_category_pk_no='" . $hot['category_lookup_pk_no'] . "' ");
                    } else {
                        $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                        where  lead_tl_pk_no = '" . $hot['user_pk_no'] . "' and
                        YEAR(lead_sold_date_manual) = $request->target_year
                        and lead_type = '$request->lead_type' and
                        MONTH(lead_sold_date_manual) = '$month_name'
                        and lead_current_stage = 7 and lead_sales_agent_pk_no != lead_tl_pk_no "); //  and project_category_pk_no='" . $hot['category_lookup_pk_no'] . "'
                    }


                    //lead_type = 1 and


                } catch (\Exception $e) {

                    echo "Memeory Access Cross";
                    die;
                }
                //dd(  $lead_of_this_month );
                $total_sale = 0;
                $sale_value = 0;

                $cancel_sale = 0;
                $cancel_qty = 0;

                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            if ($lead->lead_type == 3) {
                                $sale_value += $lead->given_value;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                }
                $ach_arr[$hot['user_pk_no']][$hot['category_lookup_pk_no']]['no_of_sale'] = $total_sale;
                $ach_arr[$hot['user_pk_no']][$hot['category_lookup_pk_no']]['sold_value'] = $sale_value;
                $ach_arr[$hot['user_pk_no']][$hot['category_lookup_pk_no']]['cancel_qty'] = $cancel_qty;
                $ach_arr[$hot['user_pk_no']][$hot['category_lookup_pk_no']]['cancel_sale'] = $cancel_sale;

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';
                //echo  $team_member.',';
                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                            where lead_tl_pk_no ='" . $hot['user_pk_no'] . "'
                            and
                            lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                            and lead_current_stage = 7 and lead_sales_agent_pk_no != lead_tl_pk_no");





                $total_sale = 0;
                $sale_value = 0;

                $cancel_qty = 0;

                $cancel_sale = 0;
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;

                            if ($lead->lead_type == 3) {
                                $cancel_sale += $lead->given_value;
                            } else {
                                $cancel_sale += $lead->lead_sold_flatcost;
                            }
                        } else {
                            if ($lead->lead_type == 3) {
                                $sale_value += $lead->given_value;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                }
                $ach_arr[$hot['user_pk_no']]['ytb_no_of_sale'] = $total_sale;
                $ach_arr[$hot['user_pk_no']]['ytb_sold_value'] = $sale_value;
                $ach_arr[$hot['user_pk_no']]['ytb_cancel_qty'] = $cancel_qty;
                $ach_arr[$hot['user_pk_no']]['ytb_cancel_sale'] = $cancel_sale;
            }
        }

        $ach_arr['resign_total']['resign_total'] = $total_resign;

        $ach_arr['resign_y_total']['resign_y_total'] = $total_y_resign;

        // dd($tl_wise_mem_arr,$ach_arr);
        return view("admin.report_module.hot_performance.hot_performance_result", compact('team_build', 'month', 'project_cat', 'project_area', 'get_hot_members', 'ach_arr', 'target_mm', 'target_ytd', 'doj_arr', 'dor_arr'));
    }
    public function k_classification()
    {
        $lead_type = config("static_arrays.lead_type");

        $target_year = YearSetup::orderby("id", "desc")->get();
        $project_cateory = LookupData::where('lookup_type', 4)->get();

        return view("admin.report_module.k_classification.k_classification", compact("target_year", "project_cateory", "lead_type"));
    }
    public function k_classification_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->t_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        if ($request->project_name == "" || $request->project_name == "0") {
            $project_cate = LookupData::where('lookup_type', 4)->get();
        } else {
            $project_cate = LookupData::where('lookup_type', 4)->where('lookup_pk_no', $request->project_name)->get();
        }
        $name_of_project = $project_cate[0]->lookup_name;
        $result_arr = [];
        $tl_arr = [];
        $final_total = [];
        $row_count = 0;
        if (!empty($project_cate)) {
            foreach ($project_cate as $row) {
                $team_hot_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
                    t_teambuild.team_lookup_pk_no FROM t_teambuild
                    join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                    where category_lookup_pk_no ='$row->lookup_pk_no' and hot_flag=1 group by s_user.user_pk_no ");

                if (!empty($team_hot_members)) {
                    foreach ($team_hot_members as $hot) {
                        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
                            t_teambuild.team_lookup_pk_no FROM t_teambuild
                            join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
                            where category_lookup_pk_no ='$row->lookup_pk_no'
                            and hot_user_pk_no='$hot->user_pk_no' and hod_flag=0 and hot_flag =0 and team_lead_flag = 0");

                        if (!empty($team_members)) {
                            foreach ($team_members as $member) {
                                $row_count++;
                                $acd = 0;
                                $cre = 0;
                                $dg = 0;
                                $dsi = 0;
                                $fair = 0;
                                $hotine = 0;
                                $ir = 0;
                                $other = 0;

                                $ex = 0;
                                $lead_data = DB::select("select * from t_lead2lifecycle_vw where
                                lead_sales_agent_pk_no ='$member->user_pk_no'
                                and YEAR(created_at) = $target_year and
                                MONTH(created_at) = '$month_name'   "); //and lead_type= '$request->lead_type' // and project_category_pk_no='$row->lookup_pk_no'

                                if (!empty($lead_data)) {
                                    foreach ($lead_data as $ld) {

                                        if ($ld->source_auto_usergroup_pk_no == 73) {
                                            $cre++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                                            $dg++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                                            $acd++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                                            $ex++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                                            $hotine++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                                            $ir++;
                                        } else {
                                            $other++;
                                        }
                                    }
                                }

                                $result_arr[$member->user_pk_no]['total'] = count($lead_data);
                                $result_arr[$member->user_pk_no]['cre'] = $cre;
                                $result_arr[$member->user_pk_no]['dg'] = $dg;
                                $result_arr[$member->user_pk_no]['acd'] = $acd;
                                $result_arr[$member->user_pk_no]['ex'] = $ex;
                                $result_arr[$member->user_pk_no]['hotine'] = $hotine;
                                $result_arr[$member->user_pk_no]['ir'] = $ir;
                                $result_arr[$member->user_pk_no]['other'] = $other;

                                $acd = 0;
                                $cre = 0;
                                $dg = 0;
                                $dsi = 0;
                                $fair = 0;
                                $hotine = 0;
                                $ir = 0;
                                $other = 0;

                                $ex = 0;
                                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                                $finc_close_date = $target_year1 . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                                $lead_data = DB::select("select * from t_lead2lifecycle_vw where
                                lead_sales_agent_pk_no ='$member->user_pk_no'
                                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                ");
                                // and lead_type= '$request->lead_type'
                                //and project_category_pk_no='$row->lookup_pk_no'
                                // echo "select * from t_lead2lifecycle_vw where
                                // lead_sales_agent_pk_no ='$member->user_pk_no' and project_category_pk_no='$row->lookup_pk_no'
                                // and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'";die;
                                // (YEAR(created_at) >= $starting_year or
                                // MONTH(created_at) >= '$starting_month' or
                                // YEAR(created_at) <= $target_year or
                                // MONTH(created_at) <= '$month_name')

                                if (!empty($lead_data)) {
                                    foreach ($lead_data as $ld) {

                                        if ($ld->source_auto_usergroup_pk_no == 73) {
                                            $cre++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                                            $dg++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                                            $acd++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                                            $ex++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                                            $hotine++;
                                        } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                                            $ir++;
                                        } else {
                                            $other++;
                                        }
                                    }
                                }
                                $result_arr[$member->user_pk_no]['t_total'] = count($lead_data);
                                $result_arr[$member->user_pk_no]['t_cre'] = $cre;
                                $result_arr[$member->user_pk_no]['t_dg'] = $dg;
                                $result_arr[$member->user_pk_no]['t_acd'] = $acd;
                                $result_arr[$member->user_pk_no]['t_ex'] = $ex;
                                $result_arr[$member->user_pk_no]['t_hotine'] = $hotine;
                                $result_arr[$member->user_pk_no]['t_ir'] = $ir;
                                $result_arr[$member->user_pk_no]['t_other'] = $other;

                                $tl_arr[$hot->user_pk_no][$member->user_pk_no] = $member->user_pk_no . '_' . $member->user_fullname . '_' . $hot->user_fullname . '_' . $hot->user_pk_no;
                            }
                        }
                    }
                }
            }
        }
        // dd($tl_arr);

        return view("admin.report_module.k_classification.k_classification_result", compact('month', 'row_count', 'name_of_project', 'tl_arr', 'result_arr'));
    }

    public function project_wise_report()
    {
        $lead_type = config("static_arrays.lead_type");
        $project_name = LookupData::where('lookup_type', 6)->get();
        return view("admin.report_module.project_wise_report.project_wise_report", compact('lead_type'));
    }
    public function project_wise_report_result(Request $request)
    {
        $month_name = $request->target_month;
        $target_year = $request->target_year;
        if ($month_name < 10) {
            $month_name = '0' . $month_name;
        }
        $target_date = $target_year . "-" . $month_name . "-" . "01";

        $lead_type = $request->lead_type;
        $category_cond = isset($request->project_category) ? 'and `sp`.`category_lookup_pk_no` =' . $request->project_category : '';
        $month = date("F", mktime(0, 0, 0, $month_name, 10));
        $project_category = $request->project_category;
        $project_name = DB::select("SELECT `sp`.`flatlist_pk_no`, `c`.`lookup_pk_no` AS `cate_id`, `c`.`lookup_name` AS `cate`, `p`.`lookup_pk_no` AS `project_id`,
        `p`.`lookup_name` AS `project_name`, COUNT(`p`.`lookup_pk_no`) AS total  FROM `s_projectwiseflatlist` AS `sp`
        INNER JOIN `s_lookdata` AS `p` ON `sp`.`project_lookup_pk_no` = `p`.`lookup_pk_no`
        INNER JOIN `s_lookdata` AS `c` ON `sp`.`category_lookup_pk_no` = `c`.`lookup_pk_no`
        where sp.lead_type ='$lead_type'   $category_cond
        GROUP BY `p`.`lookup_pk_no`");
        //dd(   $target_date);
        $total_sell_arr = [];
        $sum = 0;
        //dd($project_name);
        //dd($request->all());
        if (!empty($project_name)) {
            foreach ($project_name as $data) {
                $sale = 0;
                $share_unit = 0;
                $priority = 0;
                $cancel_unit = 0;
                $k = 0;

                $total_inventory = count(DB::table('s_projectwiseflatlist')->where('project_lookup_pk_no', $data->project_id)->where('lead_type', $request->lead_type)->get()); //->where('flat_status',0) ->where('created_at', '<', $target_date)
                $project_pk_no = $data->project_id;
                $lead_info  = DB::select("SELECT *
                FROM t_lead2lifecycle_vw t
                WHERE t.Project_pk_no= $project_pk_no and t.project_category_pk_no =    $project_category   and lead_current_stage =7
                and MONTH(lead_sold_date_manual) = '$request->target_month' and YEAR(lead_sold_date_manual) = '$request->target_year' and lead_type = '$request->lead_type' ");

                // echo   $project_pk_no .'<br>';





                // echo "select * from t_lead2lifecycle_vw where Project_pk_no =  '$data->project_id'
                // and YEAR(lead_sold_date_manual) = $target_year and lead_sold_date_manual < '$target_date' and
                // lead_type ='$lead_type'<br>";

                if (!empty($lead_info)) {
                    foreach ($lead_info as $ld) {
                        if ($ld->lead_current_stage == 7) {
                            $sale++;
                        }
                        if ($ld->is_cancel == 1) {
                            $cancel_unit++;
                        }

                        $share_unit++;
                    }
                }
                $target_date = $target_year . "-" . $month_name . "-" . "31";
                $lead_info  = DB::select("SELECT *
                FROM t_lead2lifecycle_vw t
                WHERE t.Project_pk_no=   $project_pk_no and t.project_category_pk_no =    $project_category   and lead_current_stage =7 and is_cancel is null and lead_sold_date_manual < '$target_date' and lead_type= '$request->lead_type'");

                $total_sell_arr[$data->project_id]['k'] = $k;
                $total_sell_arr[$data->project_id]['sale'] = $sale;
                $total_sell_arr[$data->project_id]['priority'] = $priority;
                $total_sell_arr[$data->project_id]['cancel_unit'] = $cancel_unit;
                $total_sell_arr[$data->project_id]['t_sale'] = count($lead_info);
                $sum += count($lead_info);
                $total_sell_arr[$data->project_id]['share_unit'] = @$total_inventory; // -  count($lead_info)
            }
        }
        // dd($total_sell_arr);
        return view("admin.report_module.project_wise_report.project_wise_report_result", compact('target_year', 'month', 'total_sell_arr', 'project_name'));
    }
    public function call_center_report()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.report_module.call_center_report.call_center_report", compact('target_year'));
    }
    public function call_center_report_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }

        //dd( $project_cate );
        $result_arr = [];
        $tl_arr = [];
        $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
        t_teambuild.team_lookup_pk_no FROM t_teambuild
        join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
        where hod_flag=0 and hot_flag = 0 and agent_type = 1 and team_lead_flag =0");
        $final_total = [];
        if (!empty($team_members)) {
            foreach ($team_members as $member) {

                $luxury = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(128,129,130) )");

                $classic_a = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(122,123,124) )");

                $classic_b_c = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(125,126) )");

                $commercial = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(567,127) )");

                $ctg = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(441) )");

                $brokeage = DB::select("select * from t_lead2lifecycle_vw where lead_type in (2) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' ");
                $rent = DB::select("select * from t_lead2lifecycle_vw where lead_type in (11) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' ");
                $bd = DB::select("select * from t_lead2lifecycle_vw where lead_type in (3) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' ");
                $sfs = DB::select("select * from t_lead2lifecycle_vw where lead_type in (4,10) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' ");
                $buidling_product = DB::select("select * from t_lead2lifecycle_vw where lead_type in (5) and source_auto_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and
                MONTH(created_at) = '$month_name' ");

                $result_arr[$member->user_pk_no]['luxury'] = count($luxury);
                $result_arr[$member->user_pk_no]['classic_a'] = count($classic_a);
                $result_arr[$member->user_pk_no]['classic_b_c'] = count($classic_b_c);
                $result_arr[$member->user_pk_no]['commercial'] = count($commercial);
                $result_arr[$member->user_pk_no]['ctg'] = count($ctg);

                $result_arr[$member->user_pk_no]['brokeage'] = count($brokeage);
                $result_arr[$member->user_pk_no]['rent'] = count($rent);
                $result_arr[$member->user_pk_no]['bd'] = count($bd);
                $result_arr[$member->user_pk_no]['sfs'] = count($sfs);
                $result_arr[$member->user_pk_no]['buidling_product'] = count($buidling_product);

                $final_total['luxury'] = isset($final_total['luxury']) ? $final_total['luxury'] + count($luxury) : count($luxury);
                $final_total['classic_a'] = isset($final_total['classic_a']) ? $final_total['classic_a'] + count($classic_a) : count($classic_a);
                $final_total['classic_b_c'] = isset($final_total['classic_b_c']) ? $final_total['classic_b_c'] + count($classic_b_c) : count($classic_b_c);
                $final_total['commercial'] = isset($final_total['commercial']) ? $final_total['commercial'] + count($commercial) : count($commercial);
                $final_total['ctg'] = isset($final_total['ctg']) ? $final_total['ctg'] + count($ctg) : count($ctg);
                $final_total['brokeage'] = isset($final_total['brokeage']) ? $final_total['brokeage'] + count($brokeage) : count($brokeage);
                $final_total['rent'] = isset($final_total['rent']) ? $final_total['rent'] + count($rent) : count($rent);
                $final_total['bd'] = isset($final_total['bd']) ? $final_total['bd'] + count($bd) : count($bd);
                $final_total['sfs'] = isset($final_total['sfs']) ? $final_total['sfs'] + count($sfs) : count($sfs);
                $final_total['buidling_product'] = isset($final_total['buidling_product']) ? $final_total['buidling_product'] + count($buidling_product) : count($buidling_product);

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $luxury = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no'
                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                and
                                lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(128,129,130) )");

                $classic_a = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and
                                    created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                    and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(122,123,124) )");

                $classic_b_c = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no'
                                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(125,126) )");

                $commercial = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no'
                                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(567,127) )");

                $ctg = DB::select("select * from t_lead2lifecycle_vw where lead_type in (1) and source_auto_pk_no ='$member->user_pk_no' and
                                created_at >=  '$finc_start_date' and created_at <='$finc_close_date'
                                and lead_sales_agent_pk_no in (select GROUP_CONCAT(user_pk_no) from t_teambuild where team_lookup_pk_no in(441) )");

                //total;
                $brokeage = DB::select("select * from t_lead2lifecycle_vw where lead_type in (2) and source_auto_pk_no ='$member->user_pk_no' and
                                    created_at >=  '$finc_start_date' and created_at <='$finc_close_date'   ");
                $rent = DB::select("select * from t_lead2lifecycle_vw where lead_type in (11) and source_auto_pk_no ='$member->user_pk_no' and
                                created_at >=  '$finc_start_date' and created_at <='$finc_close_date'   ");
                $bd = DB::select("select * from t_lead2lifecycle_vw where lead_type in (3) and source_auto_pk_no ='$member->user_pk_no'
                                 and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'   ");
                $sfs = DB::select("select * from t_lead2lifecycle_vw where lead_type in (4,10) and source_auto_pk_no ='$member->user_pk_no'
                                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'   ");
                $buidling_product = DB::select("select * from t_lead2lifecycle_vw where lead_type in (5) and source_auto_pk_no ='$member->user_pk_no'
                                and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'   ");

                $result_arr[$member->user_pk_no]['t_luxury'] = count($luxury);
                $result_arr[$member->user_pk_no]['t_classic_a'] = count($classic_a);
                $result_arr[$member->user_pk_no]['t_classic_b_c'] = count($classic_b_c);
                $result_arr[$member->user_pk_no]['t_commercial'] = count($commercial);
                $result_arr[$member->user_pk_no]['t_ctg'] = count($ctg);

                $result_arr[$member->user_pk_no]['t_brokeage'] = count($brokeage);
                $result_arr[$member->user_pk_no]['t_rent'] = count($rent);
                $result_arr[$member->user_pk_no]['t_bd'] = count($bd);
                $result_arr[$member->user_pk_no]['t_sfs'] = count($sfs);
                $result_arr[$member->user_pk_no]['t_buidling_product'] = count($buidling_product);
                $final_total['t_luxury'] = isset($final_total['t_luxury']) ? $final_total['t_luxury'] + count($luxury) : count($luxury);
                $final_total['t_classic_a'] = isset($final_total['t_classic_a']) ? $final_total['t_classic_a'] + count($classic_a) : count($classic_a);
                $final_total['t_classic_b_c'] = isset($final_total['t_classic_b_c']) ? $final_total['t_classic_b_c'] + count($classic_b_c) : count($classic_b_c);
                $final_total['t_commercial'] = isset($final_total['t_commercial']) ? $final_total['t_commercial'] + count($commercial) : count($commercial);
                $final_total['t_ctg'] = isset($final_total['t_ctg']) ? $final_total['t_ctg'] + count($ctg) : count($ctg);
                $final_total['t_brokeage'] = isset($final_total['t_brokeage']) ? $final_total['t_brokeage'] + count($brokeage) : count($brokeage);
                $final_total['t_rent'] = isset($final_total['t_rent']) ? $final_total['t_rent'] + count($rent) : count($rent);
                $final_total['t_bd'] = isset($final_total['t_bd']) ? $final_total['t_bd'] + count($bd) : count($bd);
                $final_total['t_sfs'] = isset($final_total['t_sfs']) ? $final_total['t_sfs'] + count($sfs) : count($sfs);
                $final_total['t_buidling_product'] = isset($final_total['t_buidling_product']) ? $final_total['t_buidling_product'] + count($buidling_product) : count($buidling_product);
            }
        }

        return view("admin.report_module.call_center_report.call_center_report_result", compact("result_arr", "team_members", "final_total"));
    }
    public function brokerage_inventory_report()
    {

        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata  join s_projectwiseflatlist on s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type in (7, 9)"); //LookupData::where('lookup_type', 4)->get()

        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.sales_report.brokerage_inventory.brokerage_inventory_report", compact('target_year', 'project_cateory'));
    }
    public function brokerage_inventory_report_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $report_setup = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();
        $target_mm = [];
        $target_ytd = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }
        if ($request->team_name == '') {
            $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
            t_teambuild.* FROM t_teambuild
            join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
            where t_teambuild.lead_type in (7,9)");
        } else {
            $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
            t_teambuild.* FROM t_teambuild
            join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
            where team_lookup_pk_no ='$request->team_name'");
        }

        $res_arr = [];
        $tl_arr = [];
        $tl_name = [];
        if (!empty($team_members)) {
            foreach ($team_members as $member) {
                if ($member->hod_flag == 0 && $member->hot_flag == 0 && $member->team_lead_flag == 0) {

                    $lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_type in (7,9) and
                    lead_sales_agent_pk_no ='$member->user_pk_no' and YEAR(created_at) = $target_year and MONTH(created_at) = '$month_name'");

                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_ytd_data = DB::select("select * from t_lead2lifecycle_vw where lead_type in (7,9) and
                    lead_sales_agent_pk_no ='$member->user_pk_no'
                    and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'  ");
                    $res_arr[$member->user_pk_no]['month'] = count($lead_data);
                    $res_arr[$member->user_pk_no]['ytd'] = count($lead_ytd_data);
                    $tl_arr[$member->hot_user_pk_no][] = $member->user_pk_no . "_" . $member->user_fullname;
                }
                if ($member->hot_flag == 1) {
                    $tl_name[$member->hot_user_pk_no] = $member->user_fullname;
                }
            }
        }
        return view(
            "admin.report_module.sales_report.brokerage_inventory.brokerage_inventory_report_result",
            compact('month', 'res_arr', 'tl_arr', 'tl_name', 'target_mm', 'target_ytd', 'doj_arr', 'dor_arr')
        );
    }

    public function brokerage_sales_report()
    {

        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata  join s_projectwiseflatlist on s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type in (2, 11)"); //LookupData::where('lookup_type', 4)->get()

        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.sales_report.brokerage_sales.brokerage_sales", compact('target_year', 'project_cateory'));
    }
    public function brokerage_sales_report_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $report_setup = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();
        $target_mm = [];
        $target_ytd = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }
        if ($request->project_category == '') {
            $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
            t_teambuild.* FROM t_teambuild
            join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
            where t_teambuild.lead_type in (2,9)");
        } else {
            $team_members = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
            t_teambuild.* FROM t_teambuild
            join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
            where category_lookup_pk_no ='$request->project_category'");
        }
        //dd($team_members);
        $res_arr = [];
        $tl_arr = [];
        $tl_name = [];
        if (!empty($team_members)) {
            foreach ($team_members as $member) {
                if ($member->hod_flag == 0 && $member->hot_flag == 0 && $member->team_lead_flag == 0) {

                    $lead_data = DB::select("select sum(lead_sold_flatcost)  as total from t_lead2lifecycle_vw where lead_type in (2,9)  and lead_current_stage = 7 and
                    lead_sales_agent_pk_no ='$member->user_pk_no' and YEAR(lead_sold_date_manual) = $target_year and MONTH(lead_sold_date_manual) = '$month_name'");

                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_ytd_data = DB::select("select sum(lead_sold_flatcost) as total from t_lead2lifecycle_vw where lead_type in (2,9) and
                    lead_sales_agent_pk_no ='$member->user_pk_no' and lead_current_stage = 7 and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'  ");
                    $res_arr[$member->user_pk_no]['month'] = isset($lead_data[0]->total) ? $lead_data[0]->total : 0;
                    $res_arr[$member->user_pk_no]['ytd'] = isset($lead_ytd_data[0]->total) ? $lead_ytd_data[0]->total : 0;
                    $tl_arr[$member->hot_user_pk_no][] = $member->user_pk_no . "_" . $member->user_fullname;
                }
                if ($member->hot_flag == 1) {
                    $tl_name[$member->hot_user_pk_no] = $member->user_fullname;
                }
            }
        }
        return view(
            "admin.report_module.sales_report.brokerage_sales.brokerage_sales_result",
            compact('res_arr', 'tl_arr', 'tl_name', 'target_mm', 'target_ytd', 'month', 'doj_arr', 'dor_arr')
        );
    }
    public function sales_summary()
    {
        $lead_type = config('static_arrays.lead_type');
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.report_module.sales_report.sales_summary.sales_summary_report", compact("lead_type", "target_year"));
    }
    public function sales_summary_result(Request $request)
    {
        $lead_type = isset($request->lead_type) ? "and t_teambuild.lead_type ='$request->lead_type'" : '';
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $ach_arr = [];
        $doj_arr = [];
        $dor_arr = [];
        $total_resign = 0;
        $total_y_resign = 0;
        $target_resize = 0;
        $target_y_resize = 0;
        $total_sale = 0;
        $sale_value = 0;

        $cancel_sale = 0;
        $cancel_qty = 0;

        $r_total_sale = 0;
        $r_sale_value = 0;

        $r_cancel_qty = 0;

        $r_cancel_sale = 0;
        $search_date =  $request->target_year . '-' . $request->target_month . '-01';

        $resign_arr_target = DB::table('report_setup')
            ->where('target_year_id', $fin_year_id)
            ->where('e_status', '!=', 0)
            ->where('lead_type', $request->lead_type)
            ->where('prev_position', 'chs')
            ->whereYear('created_at', '=',  date("Y", strtotime($search_date)))
            ->whereMonth('created_at', '>',  date("m", strtotime($search_date)))
            ->get();

        if (!empty($resign_arr_target)) {
            foreach ($resign_arr_target as $row) {
                $resign_mem_target = DB::table('target_sales_a_setup')
                    ->where('finc_yy', $fin_year_id)
                    ->where('member_id', $row->member_id)
                    ->where('target_yy', $request->target_year)
                    ->where('target_mm', $request->target_month)
                    ->first();
                $target_resize += !empty($resign_mem_target->target_month) ? $resign_mem_target->target_month : 0;
                $target_y_resize += !empty($resign_mem_target->target_ytd) ? $resign_mem_target->target_ytd : 0;

                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                        where  lead_hot_pk_no =  '$row->member_id' and
                        YEAR(lead_sold_date_manual) = $request->target_year
                        and lead_type = '$request->lead_type' and
                        MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7");



                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr['r_no_of_sale'] = isset($ach_arr['r_no_of_sale']) ?   $ach_arr['r_no_of_sale'] + $total_sale :  $total_sale;
                $ach_arr['r_sold_value'] = isset($ach_arr['r_sold_value']) ? $ach_arr['r_sold_value'] + $sale_value :  $sale_value;
                $ach_arr['r_cancel_qty'] = isset($ach_arr['r_cancel_qty']) ?  $ach_arr['r_cancel_qty'] + $cancel_qty : $cancel_qty;
                $ach_arr['r_cancel_sale'] = isset($ach_arr['r_cancel_sale']) ? $ach_arr['r_cancel_sale'] + $cancel_sale : $cancel_sale;
                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                        where lead_hot_pk_no = '$row->member_id' and
                        lead_sold_date_manual >=  '$finc_start_date'
                        and lead_sold_date_manual <='$finc_close_date'
                        and lead_type = '$request->lead_type'
                        and lead_current_stage = 7");


                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $r_total_sale++;
                        if ($lead->is_cancel == 1) {
                            $r_cancel_qty++;
                            $r_cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $r_sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr['r_ytb_no_of_sale'] =  isset($ach_arr['r_ytb_no_of_sale']) ? $ach_arr['r_ytb_no_of_sale'] + $r_total_sale : $r_total_sale;
                $ach_arr['r_ytb_sold_value'] = isset($ach_arr['r_ytb_sold_value'])   ? $ach_arr['r_ytb_sold_value'] + $r_sale_value : $r_sale_value;
                $ach_arr['r_ytb_cancel_qty'] = isset($ach_arr['r_ytb_cancel_qty']) ?  $ach_arr['r_ytb_cancel_qty'] + $r_cancel_qty :  $r_cancel_qty;
                $ach_arr['r_ytb_cancel_sale'] = isset($ach_arr['r_ytb_cancel_sale']) ? $ach_arr['r_ytb_cancel_sale'] + $r_cancel_sale : $r_cancel_sale;
            }
        }



        $team_hod = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,
        t_teambuild.* FROM t_teambuild
        join s_user on s_user.user_pk_no =  t_teambuild.user_pk_no
        where t_teambuild.hot_flag =1 and t_teambuild.agent_type !=1   $lead_type  group by s_user.user_pk_no,t_teambuild.category_lookup_pk_no"); //,t_teambuild.team_lookup_pk_no
        //dd($team_hod);
        //$ach_arr = [];
        $target_arr = [];
        $inventory_target = [];
        $team_build = [];
        $user_id_check = [];

        //dd(  $search_date);
        if (!empty($team_hod)) {
            foreach ($team_hod as $hod) {
                array_push($team_build, [
                    'user_pk_no' => $hod->user_pk_no,
                    'category_lookup_pk_no' => $hod->category_lookup_pk_no,
                    'user_fullname' => $hod->user_fullname,
                    'team_lookup_pk_no' => $hod->team_lookup_pk_no
                ]);

                //echo $hod->team_lookup_pk_no."<br/>";
                $resign_promotion_member = ReportSetup::join('s_user', 's_user.user_pk_no', 'report_setup.member_id')
                    ->where('e_status', '!=', '0')
                    ->where('lead_type', $request->lead_type)->where('prev_position', 'chs')
                    ->whereYear('report_setup.created_at', '=',  date("Y", strtotime($search_date)))
                    ->whereMonth('report_setup.created_at', '>',  date("m", strtotime($search_date)))
                    ->get();
                //  echo   $hod->user_pk_no.'<br>'; //. $search_date.'<br>'. $hod->team_lookup_pk_no
                //dd($resign_promotion_member);
                if (!empty($resign_promotion_member)) {
                    foreach ($resign_promotion_member as $r_member) {

                        if ((!in_array($r_member->member_id, $user_id_check))) {
                            array_push($user_id_check, $r_member->member_id);
                            array_push($team_build, [
                                'user_pk_no' => $r_member->member_id,
                                'category_lookup_pk_no' => $r_member->category_id,
                                'user_fullname' => $r_member->user_fullname,
                                'team_lookup_pk_no' => $r_member->team_id
                            ]);
                        }
                    }
                }
            }
        }
        // dd(array_unique($team_build,SORT_REGULAR));
        //dd(collect($team_build[0])['user_pk_no']);

        if (!empty(collect($team_build))) {
            foreach (array_unique($team_build, SORT_REGULAR) as $hod) {

                $team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) as teamMembers FROM t_teambuild WHERE hot_user_pk_no='" . $hod['user_pk_no'] . "' and team_lead_flag=0 and team_lead_flag = 0 and hot_flag=0")[0]->teamMembers;
                $team_member = isset($team_member) ?   $team_member : '0';
                $target_mm_ytd = DB::select("select sum(target_month) as month_target,sum(target_ytd) as ytd_target
                    from target_sales_a_setup where finc_yy = '$fin_year_id' and member_id in ( $team_member) and target_mm = '$request->target_month'");

                $target_arr[$hod['user_pk_no']]['month_target'] = isset($target_mm_ytd[0]->month_target) ? $target_mm_ytd[0]->month_target : 0;
                $target_arr[$hod['user_pk_no']]['ytd_target'] = isset($target_mm_ytd[0]->ytd_target) ? $target_mm_ytd[0]->ytd_target : 0;
                $team_member = isset($team_member) ?   $team_member : '0';

                $lead_of_this_month = DB::select("select * from t_lead2lifecycle_vw
                                where  lead_hot_pk_no = '" . $hod['user_pk_no'] . "' and
                                lead_type = '$request->lead_type' and
                                YEAR(lead_sold_date_manual) = $request->target_year and lead_hot_pk_no != lead_sales_agent_pk_no and
                                MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7 and project_category_pk_no = '" . $hod['category_lookup_pk_no'] . "'");
                // echo "select * from t_lead2lifecycle_vw
                // where  lead_hot_pk_no = '$hod->hot_user_pk_no' and lead_hot_pk_no != lead_sales_agent_pk_no
                // YEAR(lead_sold_date_manual) = $request->target_year and
                // MONTH(lead_sold_date_manual) = '$month_name' and lead_current_stage = 7<br>";





                $inventory_value = DB::select("select sum(flat_cost) as total_flat_cost from s_projectwiseflatlist where category_lookup_pk_no = '" . $hod["category_lookup_pk_no"] . "'");
                $inventory_target[$hod["user_pk_no"]] = isset($inventory_value[0]->total_flat_cost) ? $inventory_value[0]->total_flat_cost : 0;
                $total_sale = 0;
                $sale_value = 0;

                $cancel_sale = 0;
                $cancel_qty = 0;
                if (!empty($lead_of_this_month)) {
                    foreach ($lead_of_this_month as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            $cancel_sale += $lead->lead_sold_flatcost;
                        } else {
                            $sale_value += $lead->lead_sold_flatcost;
                        }
                    }
                }
                $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['no_of_sale'] = $total_sale;
                $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['sold_value'] = $sale_value;
                $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_qty'] = $cancel_qty;
                $ach_arr[$hod["user_pk_no"]][$hod["category_lookup_pk_no"]]['cancel_sale'] = $cancel_sale;

                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw
                                where  lead_hot_pk_no = '" . $hod['user_pk_no'] . "'  and
                                lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                                and lead_current_stage = 7");
                // echo "select * from t_lead2lifecycle_vw
                // where  lead_hot_pk_no = '" . $hod['user_pk_no'] . "'  and
                // lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'
                // and lead_current_stage = 7 <br>";

                $total_sale = 0;
                $sale_value = 0;

                $cancel_qty = 0;

                $cancel_sale = 0;
                if (!empty($lead_total_info)) {
                    foreach ($lead_total_info as $lead) {
                        $total_sale++;
                        if ($lead->is_cancel == 1) {
                            $cancel_qty++;
                            if ($lead->lead_type == 3) {
                                $cancel_sale += $lead->given_value;
                            } else {
                                $cancel_sale += $lead->lead_sold_flatcost;
                            }
                        } else {
                            //$sale_value += $lead->lead_sold_flatcost;
                            if ($lead->lead_type == 3) {
                                $sale_value += $lead->given_value;
                            } else {
                                $sale_value += $lead->lead_sold_flatcost;
                            }
                        }
                    }
                }
                $ach_arr[$hod["user_pk_no"]]['ytb_no_of_sale'] = $total_sale;
                $ach_arr[$hod["user_pk_no"]]['ytb_sold_value'] = $sale_value;
                $ach_arr[$hod["user_pk_no"]]['ytb_cancel_qty'] = $cancel_qty;
                $ach_arr[$hod["user_pk_no"]]['ytb_cancel_sale'] = $cancel_sale;
            }
        }
        $project_cat = LookupData::where("lookup_type", 4)->get();
        $project_cat_arr = [];
        if (!empty($project_cat)) {
            foreach ($project_cat as $cat) {
                $project_cat_arr[$cat->lookup_pk_no] = $cat->lookup_name;
            }
        }
        return view("admin.report_module.sales_report.sales_summary.sales_summary_report_result", compact("target_resize", "target_y_resize", "inventory_target", "target_arr", "project_cat_arr", "month", 'team_build', 'ach_arr'));
    }

    public function get_type_wise_catgeory(Request $request)
    {
        $project_cateory = DB::select("select distinct lookup_pk_no,lookup_name from s_lookdata join s_projectwiseflatlist on
        s_projectwiseflatlist.category_lookup_pk_no = s_lookdata.lookup_pk_no where lead_type ='$request->type'");
        $project_categeory_dropdown = '<option value="">Select One</option>';
        if (!empty($project_cateory)) {
            foreach ($project_cateory as $row) {
                $project_categeory_dropdown .= '<option value="' . $row->lookup_pk_no . '">' . $row->lookup_name . '</option>';
            }
        }
        return $project_categeory_dropdown;
    }
    public function bd_sac_lead()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.bd_sac_lead.bd_sac_lead", compact('target_year'));
    }
    public function bd_sac_lead_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $report_setup = DB::table('target_bd_land')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();
        $target_mm = [];
        $target_ytd = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }
        $get_all_tl_member = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.team_lookup_pk_no
                     FROM t_teambuild join s_user on s_user.user_pk_no = t_teambuild.user_pk_no
                     WHERE team_lead_flag=0 and hod_flag=0 and hot_flag =0  and lead_type=3");
        $report_lead_arr = [];

        if (!empty($get_all_tl_member)) {
            foreach ($get_all_tl_member as $row) {
                $lead_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no'
                and YEAR(created_at) = $request->target_year and
                MONTH(created_at) = '$month_name'");
                $report_lead_arr['month'][$row->user_pk_no] = count($lead_info);
                $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                $lead_total_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no' and
                created_at >=  '$finc_start_date' and created_at <='$finc_close_date'");
                $report_lead_arr['ytd'][$row->user_pk_no] = count($lead_total_info);
            }
        }
        return view("admin.report_module.bd_sac_lead.bd_sac_lead_result", compact('dor_arr', 'doj_arr', 'target_mm', 'target_ytd', 'get_all_tl_member', 'report_lead_arr', 'month'));
    }
    public function acd_report()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();

        return view("admin.report_module.acd_report.acd_report", compact('target_year'));
    }
    public function acd_report_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        // dd($fin_year_info);
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }
        $report_setup = DB::table('report_setup')->where('target_year_id', $fin_year_id)->get();
        // dd($report_setup);
        $doj_arr = [];
        $dor_arr = [];
        $resign_promote_arr = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $doj_arr[$data->member_id] = $data->date_of_join;
                $dor_arr[$data->member_id] = $data->date_of_report;
                if ($data->e_status != "0") {
                    $resign_promote_arr[$data->team_id][] = $data->member_id;
                }
            }
        }
        $report_setup = DB::table('target_sales_a_setup')
            ->where('finc_yy', $fin_year_id)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->target_month)
            ->get();
        // dd($report_setup);

        $target_mm = [];
        $target_ytd = [];
        if (!empty($report_setup)) {
            foreach ($report_setup as $data) {
                $target_mm[$data->member_id] = $data->target_month;
                $target_ytd[$data->member_id] = $data->target_ytd;
            }
        }
        $get_all_tl_member = DB::select("SELECT s_user.user_pk_no,s_user.user_fullname,t_teambuild.team_lookup_pk_no
                     FROM t_teambuild join s_user on s_user.user_pk_no = t_teambuild.user_pk_no
                     WHERE team_lead_flag=0 and hod_flag=0 and hot_flag =0  and t_teambuild.team_lookup_pk_no=448");
        $report_lead_arr = [];

        if (!empty($get_all_tl_member)) {
            foreach ($get_all_tl_member as $row) {
                if ($request->report_type == 1) {
                    $lead_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no'
                    and YEAR(created_at) = $request->target_year and
                    MONTH(created_at) = '$month_name'");
                    // echo count($lead_info)."<br>";
                    $report_lead_arr['month'][$row->user_pk_no] = count($lead_info);
                    $lead_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no'
                    and YEAR(created_at) = $request->target_year and
                    MONTH(created_at) = '$month_name' and lead_current_stage in (3,4)");
                    // echo count($lead_info)."<br>";
                    $report_lead_arr['k1'][$row->user_pk_no] = count($lead_info);
                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no' and
                    created_at >=  '$finc_start_date' and created_at <='$finc_close_date'");
                    // echo count($lead_total_info)."<br>";

                    $report_lead_arr['ytd'][$row->user_pk_no] = count($lead_total_info);
                }
                if ($request->report_type == 2) {
                    $lead_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no'
                    and YEAR(lead_sold_date_manual) = $request->target_year and
                    MONTH(lead_sold_date_manual) = '$month_name'");
                    // echo count($lead_info)."<br>";
                    $report_lead_arr['month'][$row->user_pk_no] = count($lead_info);
                    $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
                    $finc_close_date = $target_year . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

                    $lead_total_info = DB::select("select * from t_lead2lifecycle_vw where source_auto_pk_no = '$row->user_pk_no' and
                    lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'");
                    $report_lead_arr['ytd'][$row->user_pk_no] = count($lead_total_info);
                    // echo count($lead_total_info)."<br>";
                }
            }
        }

        if ($request->report_type == 1) {
            return view("admin.report_module.acd_report.acd_report_result", compact('dor_arr', 'doj_arr', 'target_mm', 'target_ytd', 'get_all_tl_member', 'report_lead_arr', 'month'));
        } else {
            return view("admin.report_module.acd_report.acd_report_result_sales", compact('dor_arr', 'doj_arr', 'target_mm', 'target_ytd', 'get_all_tl_member', 'report_lead_arr', 'month'));
        }
    }
    public function source_wise_report()
    {
        $lead_type = config("static_arrays.lead_type");

        $target_year = YearSetup::orderby("id", "desc")->get();
        $project_cateory = LookupData::where('lookup_type', 4)->get();

        return view("admin.report_module.source_wise_k.source_wise_k", compact("target_year", "project_cateory", "lead_type"));
    }
    public function source_wise_report_result(Request $request)
    {
        $month_arr = config('static_arrays.months_arr');
        $month_name = $request->target_month;
        $target_year = $request->team_target_year;
        $target_year1 = $request->team_target_year;

        $month_num = $month_name;
        $month = date("F", mktime(0, 0, 0, $month_num, 10));

        $fin_year_info = DB::table('year_to_year_setup')->where('closing_year', $target_year)->first();
        $starting_year = $starting_month = 0;
        if (isset($fin_year_info->closing_year)) {
            if ($request->target_month <= $fin_year_info->closing_month) {
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else if ($request->target_month >= $fin_year_info->starting_month) {
                //$fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            } else {
                $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
                $fin_year_id = $fin_year_info->id;
                $starting_year = $fin_year_info->starting_year;
                $starting_month = intval($fin_year_info->starting_month);
            }
        } else {
            $fin_year_info = DB::table('year_to_year_setup')->where('starting_month', $target_year)->first();
            $fin_year_id = $fin_year_info->id;
            $starting_year = $fin_year_info->starting_year;
            $starting_month = intval($fin_year_info->starting_month);
        }

        $cre = 0;

        $acd = 0;
        $dg = 0;
        $dsi = 0;
        $fair = 0;
        $hotine = 0;
        $ir = 0;
        $other = 0;

        $ex = 0;
        $refresh = 0;
        $refresh_total = 0;
        $close = 0;
        $close_total = 0;
        $cre_total = 0;

        $acd_total = 0;
        $dg_total = 0;
        $dsi_total = 0;
        $fair_total = 0;
        $hotine_total = 0;
        $ir_total = 0;
        $other_total = 0;

        $ex_total = 0;
        $lead_data = DB::select("select * from t_lead2lifecycle_vw where YEAR(created_at) = $target_year and
            MONTH(created_at) = '$month_name' and lead_type = '$request->lead_type'");

        if (!empty($lead_data)) {
            foreach ($lead_data as $ld) {

                if ($ld->source_auto_usergroup_pk_no == 73) {
                    $cre++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $cre_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                    $dg++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $dg_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                    $acd++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $acd_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                    $ex++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $ex_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                    $hotine++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $hotine_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                    $ir++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $ir_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 77) {
                    $dsi++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $dsi_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 696) {
                    $close++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $close_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 701) {
                    $refresh++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $refresh_total++;
                    }
                } else {
                    $other++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $other_total++;
                    }
                }
            }
        }

        $result_arr['total'] = count($lead_data);
        $result_arr['cre'] = $cre;
        $result_arr['dg'] = $dg;
        $result_arr['acd'] = $acd;
        $result_arr['ex'] = $ex;
        $result_arr['hotine'] = $hotine;
        $result_arr['ir'] = $ir;
        $result_arr['dsi'] = $dsi;
        $result_arr['other'] = $other;
        $result_arr['refresh'] = $refresh;
        $result_arr['close'] = $close;

        $result_arr['cre_total'] = $cre_total;
        $result_arr['dg_total'] = $dg_total;
        $result_arr['acd_total'] = $acd_total;
        $result_arr['ex_total'] = $ex_total;
        $result_arr['hotine_total'] = $hotine_total;
        $result_arr['ir_total'] = $ir_total;
        $result_arr['dsi_total'] = $dsi_total;
        $result_arr['other_total'] = $other_total;
        $result_arr['refresh_total'] = $refresh_total;
        $result_arr['close_total'] = $close_total;
        //Sold Of  CRE
        $cre = 0;

        $acd = 0;
        $dg = 0;
        $dsi = 0;
        $fair = 0;
        $hotine = 0;
        $ir = 0;
        $other = 0;
        $refresh = 0;
        $refresh_total = 0;
        $close = 0;
        $close_total = 0;

        $ex = 0;
        $lead_data = DB::select("select * from t_lead2lifecycle_vw where YEAR(lead_sold_date_manual) = $target_year and
        MONTH(lead_sold_date_manual) = '$month_name' and lead_type = '$request->lead_type' and lead_current_stage = 7");

        if (!empty($lead_data)) {
            foreach ($lead_data as $ld) {

                if ($ld->source_auto_usergroup_pk_no == 73) {
                    $cre++;
                } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                    $dg++;
                } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                    $acd++;
                } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                    $ex++;
                } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                    $hotine++;
                } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                    $ir++;
                } elseif ($ld->source_auto_usergroup_pk_no == 77) {
                    $dsi++;
                } elseif ($ld->source_auto_usergroup_pk_no == 701) {
                    $refresh++;
                } elseif ($ld->source_auto_usergroup_pk_no == 696) {
                    $close++;
                } else {
                    $other++;
                }
            }
        }

        $result_arr['cre_sold'] = $cre;
        $result_arr['dg_sold'] = $dg;
        $result_arr['acd_sold'] = $acd;
        $result_arr['ex_sold'] = $ex;
        $result_arr['hotine_sold'] = $hotine;
        $result_arr['ir_sold'] = $ir;
        $result_arr['dsi_sold'] = $dsi;
        $result_arr['other_sold'] = $other;
        $result_arr['refresh_sold'] = $refresh;
        $result_arr['close_sold'] = $close;

        $acd = 0;
        $cre = 0;
        $dg = 0;
        $dsi = 0;
        $fair = 0;
        $hotine = 0;
        $ir = 0;
        $other = 0;

        $ex = 0;

        $cre_total = 0;

        $acd_total = 0;
        $dg_total = 0;
        $dsi_total = 0;
        $fair_total = 0;
        $hotine_total = 0;
        $ir_total = 0;
        $other_total = 0;

        $ex_total = 0;

        $refresh = 0;
        $refresh_total = 0;
        $close = 0;
        $close_total = 0;

        $finc_start_date = $starting_year . '-' . str_pad($starting_month, 2, '0', STR_PAD_LEFT) . '-01';
        $finc_close_date = $target_year1 . '-' . str_pad($month_name, 2, '0', STR_PAD_LEFT) . '-31';

        $lead_data = DB::select("select * from t_lead2lifecycle_vw where  lead_type = '$request->lead_type' and created_at >=  '$finc_start_date' and created_at <='$finc_close_date'");

        if (!empty($lead_data)) {
            foreach ($lead_data as $ld) {

                if ($ld->source_auto_usergroup_pk_no == 73) {
                    $cre++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $cre_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                    $dg++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $dg_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                    $acd++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $acd_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                    $ex++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $ex_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                    $hotine++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $hotine_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                    $ir++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $ir_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 77) {
                    $dsi++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $dsi_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 696) {
                    $close++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $close_total++;
                    }
                } elseif ($ld->source_auto_usergroup_pk_no == 701) {
                    $refresh++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $refresh_total++;
                    }
                } else {
                    $other++;
                    if ($ld->lead_current_stage == 3 || $ld->lead_current_stage == 4) {
                        $other_total++;
                    }
                }
            }
        }

        $result_arr['t_total'] = count($lead_data);
        $result_arr['t_cre'] = $cre;
        $result_arr['t_dg'] = $dg;
        $result_arr['t_acd'] = $acd;
        $result_arr['t_ex'] = $ex;
        $result_arr['t_hotine'] = $hotine;
        $result_arr['t_ir'] = $ir;
        $result_arr['t_other'] = $other;
        $result_arr['t_dsi'] = $dsi;
        $result_arr['t_close'] = $close;
        $result_arr['t_refresh'] = $refresh;
        $result_arr['t_total'] = count($lead_data);

        $result_arr['t_cre_total'] = $cre_total;
        $result_arr['t_dg_total'] = $dg_total;
        $result_arr['t_acd_total'] = $acd_total;
        $result_arr['t_ex_total'] = $ex_total;
        $result_arr['t_hotine_total'] = $hotine_total;
        $result_arr['t_ir_total'] = $ir_total;
        $result_arr['t_dsi_total'] = $dsi_total;
        $result_arr['t_other_total'] = $other_total;

        $result_arr['t_close_total'] = $close_total;
        $result_arr['t_refresh_total'] = $refresh_total;
        //sold
        $acd = 0;
        $cre = 0;
        $dg = 0;
        $dsi = 0;
        $fair = 0;
        $hotine = 0;
        $ir = 0;
        $other = 0;

        $ex = 0;
        $lead_data = DB::select("select * from t_lead2lifecycle_vw where  lead_type = '$request->lead_type' and lead_sold_date_manual >=  '$finc_start_date' and lead_sold_date_manual <='$finc_close_date'");

        if (!empty($lead_data)) {
            foreach ($lead_data as $ld) {

                if ($ld->source_auto_usergroup_pk_no == 73) {
                    $cre++;
                } elseif ($ld->source_auto_usergroup_pk_no == 74) {
                    $dg++;
                } elseif ($ld->source_auto_usergroup_pk_no == 447) {
                    $acd++;
                } elseif ($ld->source_auto_usergroup_pk_no == 203) {
                    $ex++;
                } elseif ($ld->source_auto_usergroup_pk_no == 76) {
                    $hotine++;
                } elseif ($ld->source_auto_usergroup_pk_no == 75) {
                    $ir++;
                } elseif ($ld->source_auto_usergroup_pk_no == 77) {
                    $dsi++;
                } elseif ($ld->source_auto_usergroup_pk_no == 701) {
                    $refresh++;
                } elseif ($ld->source_auto_usergroup_pk_no == 696) {
                    $close++;
                } else {
                    $other++;
                }
            }
        }
        $result_arr['t_cre_sold'] = $cre;
        $result_arr['t_dg_sold'] = $dg;
        $result_arr['t_acd_sold'] = $acd;
        $result_arr['t_ex_sold'] = $ex;
        $result_arr['t_hotine_sold'] = $hotine;
        $result_arr['t_ir_sold'] = $ir;
        $result_arr['t_dsi_sold'] = $dsi;
        $result_arr['t_refresh_sold'] = $refresh;
        $result_arr['t_close_sold'] = $close;
        $result_arr['t_other_sold'] = $other;

        return view("admin.report_module.source_wise_k.source_wise_k_result", compact('result_arr', 'month'));
    }
}
