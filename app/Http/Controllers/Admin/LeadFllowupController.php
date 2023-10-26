<?php

namespace App\Http\Controllers\Admin;

use App\FlatSetup;
use App\Http\Controllers\Controller;
use App\Lead;
use App\LeadFollowUp;
use App\LeadLifeCycle;
use App\LeadStageHistory;
use App\LookupData;
use Carbon\carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class LeadFllowupController extends Controller
{
    public function index()
    {
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $is_team_leader = Session::get('user.is_team_leader');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');
        $ses_user_id = Session::get('user.ses_user_pk_no');

        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
            $get_all_team_members = $get_all_team_member . "," . $ses_user_id;
        } else {
            $get_all_team_members = $ses_user_id;
        }

        $lead_data = [];
        if ($get_all_team_members != "") {
            $user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . "))"; //or b.created_by in(" . $get_all_team_members . ")

            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
                FROM t_lead2lifecycle_vw b
                LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
                SELECT
                MAX(lead_followup_pk_no)
                FROM
                t_leadfollowup c
                WHERE
                a.lead_pk_no = c.lead_pk_no
                )
                LEFT JOIN s_user c ON b.created_by=c.user_pk_no
            WHERE (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) and lead_current_stage not in(6,7) $user_cond"); //AND (a.lead_followup_datetime=CURDATE() OR a.lead_followup_datetime IS NULL) AND (Next_FollowUp_date=CURDATE() OR Next_FollowUp_date IS NULL)
        }

        return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $current_stage = ($request->hdn_cur_stage != "") ? $request->hdn_cur_stage : 0;
        $new_stage = ($request->cmb_change_stage > 0) ? $request->cmb_change_stage : $current_stage;

        if(($new_stage ==6 && !isset($request->close_reason))){
            return response()->json(['message' => 'Lead Followup Failde please select Reason For Closed.','title' => 'Error', "positionClass" => "toast-top-right"]);
        }
        $create_date = date('Y-m-d');
        $ses_user_id = Session::get('user.ses_user_pk_no');

        $txt_followup_date = date("Y-m-d", strtotime($request->txt_followup_date));
        $txt_followup_date_time = date("Y-m-d H:i:s", strtotime($request->txt_followup_date . " " . $request->txt_followup_date_time));

        $cmb_project_name = $request->cmb_project_name;
        $cmb_category = $request->cmb_category;
        $cmb_area = $request->cmb_area;
        $cmb_size = $request->cmb_size;

        $lead = Lead::where('lead_pk_no', $request->hdn_lead_pk_no)->first();

        if ($cmb_category > 0 && $cmb_project_name > 0 && $cmb_area > 0 && $cmb_size > 0) {
            $lead->project_category_pk_no = $cmb_category;
            $lead->project_area_pk_no = $cmb_area;
            $lead->Project_pk_no = $cmb_project_name;
            $lead->project_size_pk_no = $cmb_size;

            $lead->save();
        }
        $k_qualification = [
            'k_qualification'=> isset($request->k_qualification)? $request->k_qualification : 0,
        ];
        $k_status = [
            'k_status'=> isset($request->k_status)? $request->k_status : 0,
        ];


        $p_status = [
            'p_status'=> isset($request->p_status)? $request->p_status : 0,
        ];

        $k_status_jsn=addslashes($k_status_jsn= json_encode($k_status));
        $p_status_jsn=addslashes($p_status_jsn= json_encode($p_status));
        $lead_life_cycle = LeadLifeCycle::find($request->hdn_life_cycle_id);
        if (!empty($lead_life_cycle)) {
            //$lead_life_cycle->lead_type = $request->lead_type;
            $lead_life_cycle->k_qualification = json_encode($k_qualification);
            $lead_life_cycle->save();
        }
        $follow_reason =  isset($request->close_reason) ? $request->close_reason : '';


        $lead_followup = DB::statement(
            DB::raw("CALL proc_leadfollowup_ins ('1','$create_date',$request->hdn_lead_pk_no,$request->cmbFollowupType,'$request->followup_note',$current_stage,1,'$txt_followup_date','$txt_followup_date_time','$request->next_followup_note',$new_stage,1,$ses_user_id,'$create_date','$follow_reason','$p_status_jsn','$k_status_jsn')")
        );
        $k_qualification = [
            'k_qualification'=> isset($request->k_qualification)? $request->k_qualification : 0,
        ];




        if (LeadFollowUp::where('lead_followup_pk_no', '=', $request->hdn_lead_followup_pk_no)->exists()) {
            $upd_followup = LeadFollowUp::find($request->hdn_lead_followup_pk_no);
            $upd_followup->next_followup_flag = 0;
            $upd_followup->save();
        }

        if ($new_stage > 0) {
            DB::statement(
                DB::raw("CALL proc_leadlifecycle_upd_stage ($request->hdn_lead_pk_no,'$create_date',$ses_user_id,$new_stage,$ses_user_id)")
            );
        }

         return response()->json(['message' => 'Lead Followup created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status", 1)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = [];
        foreach ($lookup_data as $value) {
            $key = $value->lookup_pk_no;
            if ($value->lookup_type == 2) {
                $digital_mkt[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 3) {
                $hotline[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 4) {
                $project_cat[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 5) {
                $project_area[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 6) {
                $project_name[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 7) {
                $project_size[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 10) {
                $ocupations[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 11) {
                $press_adds[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 12) {
                $billboards[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 13) {
                $project_boards[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 14) {
                $flyers[$key] = $value->lookup_name;
            }

            if ($value->lookup_type == 15) {
                $fnfs[$key] = $value->lookup_name;
            }
        }

        $page = "";
        $lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 17) {
                $followup_type[$key] = $value->lookup_name;
            }
        }

        $lead_type = config('static_arrays.lead_type');

        $lead_data = DB::select("SELECT a.*,b.*
            FROM t_lead2lifecycle_vw b
            LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];
        //Categroy Project
        $ses_lead_type = Session::get('user.ses_lead_type');
        $ses_super_admin = Session::get('user.is_super_admin');
        $project = $lead_data->Project_pk_no;
        $area_id = $lead_data->project_area_pk_no;
        $cat_id = $lead_data->project_category_pk_no;
        if ($ses_super_admin == 1) {
            $project_size = DB::table("s_lookdata")
                ->where('lookup_type', 7)
                ->whereIn('lookup_pk_no', function ($query) use ($project, $ses_lead_type) {
                    $query->select("size_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("project_lookup_pk_no", $project);
                })->get();
        } else {
            $project_size = DB::table("s_lookdata")
                ->where('lookup_type', 7)
                ->whereIn('lookup_pk_no', function ($query) use ($project, $ses_lead_type) {
                    $query->select("size_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("project_lookup_pk_no", $project)->where("lead_type", $ses_lead_type);
                })->get();
        }

        $size_arr = [];
        foreach ($project_size as $size) {
            $size_arr[$size->lookup_pk_no] = $size->lookup_name;
        }
        if ($ses_super_admin == 1) {
            $project_category = DB::table("s_lookdata")
                ->where('lookup_type', 4)
                ->whereIn('lookup_pk_no', function ($query) use ($area_id, $ses_lead_type) {
                    $query->select("category_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("area_lookup_pk_no", $area_id);
                })->get();
        } else {
            $project_category = DB::table("s_lookdata")
                ->where('lookup_type', 4)
                ->whereIn('lookup_pk_no', function ($query) use ($area_id, $ses_lead_type) {
                    $query->select("category_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("area_lookup_pk_no", $area_id)
                        ->where("lead_type", $ses_lead_type);
                })->get();
        }

        $cat_arr = [];
        foreach ($project_category as $category) {
            $cat_arr[$category->lookup_pk_no] = $category->lookup_name;
        }

        if ($ses_super_admin == 1) {
            $project_name = DB::table("s_lookdata")
                ->where('lookup_type', 6)
                ->whereIn('lookup_pk_no', function ($query) use ($cat_id, $ses_lead_type) {
                    $query->select("project_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("category_lookup_pk_no", $cat_id);
                })->get();
        } else {
            $project_name = DB::table("s_lookdata")
                ->where('lookup_type', 6)
                ->whereIn('lookup_pk_no', function ($query) use ($cat_id, $ses_lead_type) {
                    $query->select("project_lookup_pk_no")
                        ->from("s_projectwiseflatlist")
                        ->where("category_lookup_pk_no", $cat_id)
                        ->where("lead_type", $ses_lead_type);
                })->get();
        }

        $project_arr = [];
        foreach ($project_name as $project) {
            $project_arr[$project->lookup_pk_no] = $project->lookup_name;
        }

        return view('admin.sales_team_management.lead_followup.lead_follow_up_form',
            compact('project_area', 'project_arr', 'cat_arr', 'size_arr', 'lead_data', 'lead_stage_arr', 'followup_type', 'page', "lead_type")
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_follow_up_from_dashboard($id, $type)
    {
        $page = "leadlist";
        $lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 17) {
                $followup_type[$key] = $value->lookup_name;
            }
        }

        $lead_data = DB::select("SELECT a.*,b.*
            FROM t_lead2lifecycle_vw b
            LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

        return view('admin.sales_team_management.lead_followup.lead_follow_up_form', compact('lead_data', 'lead_stage_arr', 'followup_type', 'page', 'type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function stage_update($id)
    {
        $lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 17) {
                $followup_type[$key] = $value->lookup_name;
            }
        }
        $lead_data = DB::select("SELECT a.*,b.*
            FROM t_lead2lifecycle_vw b
            LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

        return view('admin.sales_team_management.lead_followup.lead_stage_update', compact('lead_data', 'lead_stage_arr', 'followup_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_stage_update(Request $request)
    {
        $this->validate($request, [
            'new_stage' => 'required',
        ]);

        $create_date = date('Y-m-d');
        $ses_user_id = Session::get('user.ses_user_pk_no');
        DB::statement(
            DB::raw("CALL proc_leadlifecycle_upd_stage ($request->lead_pk_no,'$create_date',$ses_user_id,$request->new_stage,$ses_user_id)")
        );

        $lstage = new LeadStageHistory();
        $lstage->lead_pk_no = $request->lead_pk_no;
        $lstage->project_category_pk_no = $request->lead_category_id;
        $lstage->project_area_pk_no = $request->lead_area_id;
        $lstage->Project_pk_no = $request->lead_project_id;
        $lstage->project_size_pk_no = $request->lead_size_id;
        $lstage->lead_stage_before_update = $request->lead_cur_stage_id;
        $lstage->lead_stage_after_update = $request->new_stage;
        $lstage->sales_agent_pk_no = $request->sales_agent_id;
        $lstage->save();

        return response()->json(['message' => 'Lead Stage updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_sold($id)
    {
        $lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        foreach ($lookup_data as $key => $value) {
            if ($value->lookup_type == 17) {
                $followup_type[$key] = $value->lookup_name;
            }
        }

        $lead_data = DB::select("SELECT a.*,b.*
            FROM t_lead2lifecycle_vw b
            LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

        if (!empty($lead_data)) {
            $flat_list = FlatSetup::where('project_lookup_pk_no', $lead_data->Project_pk_no)
                ->where('category_lookup_pk_no', $lead_data->project_category_pk_no)
                ->where('size_lookup_pk_no', $lead_data->project_size_pk_no)
                ->where('flat_status', 0)
                ->where('lead_type', $lead_data->lead_type)
                ->get(['flatlist_pk_no', 'flat_name']);
        }
        $lead_type = config('static_arrays.lead_type');
        return view('admin.sales_team_management.lead_followup.lead_sold', compact('lead_type', 'lead_data', 'lead_stage_arr', 'followup_type', 'flat_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_lead_sold(Request $request)
    {
        /*$this->validate($request,[
        'new_stage' => 'required'
        ]);*/
        // die();
        $group_id = Session::get('user.ses_role_lookup_pk_no');
        $ses_lead_type = Session::get('user.ses_lead_type');
        $ses_super_admin = Session::get('user.is_super_admin');

        $ldata = LeadLifeCycle::findOrFail($request->leadlifecycle_id);

        $ldata->lead_current_stage = 7;
        $ldata->lead_sold_flag = 1;
        $ldata->flatlist_pk_no = $request->flat;
        $ldata->lead_sold_flatcost = $request->flat_cost;
        $ldata->lead_sold_utilitycost = $request->utility;
        $ldata->lead_sold_parkingcost = $request->parking;
        $ldata->lead_sold_date_manual = date("Y-m-d", strtotime($request->date_of_sold));
        $ldata->lead_sold_sales_agent_pk_no = $request->sales_agent_id;
        $ldata->lead_sold_team_lead_pk_no = 1;
        $ldata->lead_sold_team_manager_pk_no = 1;
        $ldata->deal_done = isset($request->deal_done) ? $request->deal_done : 0;
        $ldata->given_value = isset($request->given_value) ? $request->given_value : 0;
        if ($ldata->save()) {
            if (isset($request->flat)) {
                $fdata = FlatSetup::findOrFail($request->flat);
                $fdata->flat_status = 1;
                $fdata->save();
            }

            if ($ses_lead_type == 7 || $ses_lead_type == 9) {

                $newFlat = new FlatSetup();
                $newFlat->category_lookup_pk_no = $fdata->category_lookup_pk_no;
                if ($ses_lead_type == 7) {
                    $newFlat->lead_type = 11;
                }
                if ($ses_lead_type == 9) {
                    $newFlat->lead_type = 2;
                }

                $newFlat->area_lookup_pk_no = $fdata->area_lookup_pk_no;
                $newFlat->project_lookup_pk_no = $fdata->project_lookup_pk_no;
                $newFlat->size_lookup_pk_no = $fdata->size_lookup_pk_no;
                $newFlat->flat_name = $fdata->flat_name;
                $newFlat->flat_description = $fdata->flat_description;
                $newFlat->flat_cost = $fdata->flat_cost;
                $newFlat->selling_period = $fdata->selling_period;
                $target_closing_month = Carbon::now()->addMonths($fdata->selling_period);
                $newFlat->target_starting_month = date("Y-m-d");
                $newFlat->target_closing_month = date("Y-m-d", strtotime($target_closing_month));

                $newFlat->bed_count = $fdata->bed_count;
                $newFlat->rent_value = $fdata->rent_value;
                $newFlat->c_service_charge = $fdata->c_service_charge;

                $newFlat->flat_status = 0;
                $newFlat->created_by = 1;
                $newFlat->created_at = date("Y-m-d");
                $newFlat->save();
            }

            return response()->json(['message' => 'Sold Complete successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Sold Complete Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_followup_leads(Request $request)
    {
        $is_team_leader = Session::get('user.is_team_leader');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');
        $ses_user_id = Session::get('user.ses_user_pk_no');

        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
            $get_all_team_members = $get_all_team_member . "," . $ses_user_id;
        } else {
            $get_all_team_members = $ses_user_id;
        }

        $user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . "))"; //or b.created_by in(" . $get_all_team_members . ")

        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        if ($request->tab_type == 1) {
            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
                FROM t_lead2lifecycle_vw b
                LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
                SELECT
                MAX(lead_followup_pk_no)
                FROM
                t_leadfollowup c
                WHERE
                a.lead_pk_no = c.lead_pk_no
                )
                LEFT JOIN s_user c ON b.created_by=c.user_pk_no
                WHERE (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL) $user_cond
             and b.lead_current_stage not in(6,7)"); //AND (a.lead_followup_datetime=CURDATE() OR a.lead_followup_datetime IS NULL) AND (Next_FollowUp_date=CURDATE() OR Next_FollowUp_date IS NULL)
            return view('admin.sales_team_management.lead_followup.lead_today_follow_up', compact('lead_data', 'lead_stage_arr'));
        }
        if ($request->tab_type == 2) {
            /*$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.next_followup_Note,c.user_fullname agent_name,b.* FROM t_lead2lifecycle_vw b  LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.lead_followup_datetime < CURDATE() AND a.next_followup_flag=1 LEFT JOIN s_user c ON b.created_by=c.user_pk_no where (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) $user_cond AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)");*/

            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
                FROM t_lead2lifecycle_vw b
                LEFT JOIN t_leadfollowup a ON (a.lead_pk_no=b.lead_pk_no )
                LEFT JOIN s_user c ON b.created_by=c.user_pk_no
                WHERE a.lead_followup_pk_no = (SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no) $user_cond
                AND (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL)
                and b.lead_current_stage not in(6,7)"); //AND a.lead_followup_datetime<CURDATE()
            return view('admin.sales_team_management.lead_followup.lead_missed_follow_up', compact('lead_data', 'lead_stage_arr'));
        }
        if ($request->tab_type == 3) {
            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.next_followup_Note,c.user_fullname agent_name,b.* FROM t_lead2lifecycle_vw b  LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 LEFT JOIN s_user c ON b.created_by=c.user_pk_no  where (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) $user_cond AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)"); //AND a.Next_FollowUp_date > CURDATE()
            return view('admin.sales_team_management.lead_followup.lead_next_follow_up', compact('lead_data', 'lead_stage_arr'));
        }
    }
}
