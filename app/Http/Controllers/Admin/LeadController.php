<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Datatables\DataTableClass;
use App\FlatSetup;
use App\Http\Controllers\Controller;
use App\Lead;
use Carbon\Carbon;
use App\LeadFollowUp;
use App\LeadHistory;
use App\LeadLifeCycle;
use App\LeadLifeCycleView;
use App\LookupData;
use App\TeamAssign;
use App\TeamUser;
use App\TransferHistory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Response;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ses_super_admin = Session::get('user.is_super_admin');

        $user_id = Session::get('user.ses_user_pk_no');
        $projects = LookupData::where('lookup_type', 6)->get();
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

        //$user_category = DB::select("SELECT GROUP_CONCAT(category_lookup_pk_no) category_lookup_pk_no FROM t_teambuild WHERE user_pk_no=$user_id and row_status=1")[0]->category_lookup_pk_no;

        $project_name = DB::table("s_lookdata")
                ->where('lookup_type', 6)
                ->whereIn('lookup_pk_no', function ($query){
                    $query->select("project_lookup_pk_no")
                        ->from("s_projectwiseflatlist");
                })->orderBy("lookup_name")->get();


        $lead_type = config('static_arrays.lead_type');
        $countries = Country::where("iso3", '!=', '')->get();
        $sub_source=LookupData::where("lookup_type", 20)->where('lookup_row_status',1)->get();
        $users = User::where('status',1)->get();
        // dd($sub_source);

        return view('admin.lead_management.lead_entry', compact('projects', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'countries', 'lead_type','sub_source','users'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_email'        => 'nullable|email|max:255|regex:/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',
            'customer_phone1' => 'required|numeric',
            //'cmb_category'          => 'required',
            'cmb_area' => 'required',
            //'cmb_project_name'      => 'required',
            //'cmb_size'              => 'required'
        ]);
        if ($request->lead_type == '') {
            return response()->json(['type' => 'error', 'message' => 'Please Select a lead type', 'title' => 'Error', "positionClass" => "toast-top-right"]);
            die();
        }

        $verify_customer = DB::select("SELECT count(lead_id) total_lead FROM t_lead2lifecycle_vw WHERE (phone1 like ('%$request->customer_phone1') or phone2 like ('%$request->customer_phone1') ) and lead_type=$request->lead_type and lead_current_stage not in(5,6,7)")[0]->total_lead; // lead_current_stage not in(6,7) and

        if ($verify_customer > 0) {
            return response()->json(['type' => 'error', 'message' => 'Another Lead is already in progress with this Customer information.', 'title' => 'Error', "positionClass" => "toast-top-right"]);
            die();
        }

        $create_date = date('Y-m-d');
        $user_id = Session::get('user.ses_user_pk_no');
        $user_type = Session::get('user.user_type');
        $is_bypass = Session::get('user.is_bypass');
        $bypass_date = Session::get('user.bypass_date');
        $ses_auto_dist = Session::get('user.ses_auto_dist');
        $ses_dist_date = Session::get('user.ses_dist_date');

        $get_team_lead = DB::select("SELECT a.team_lead_user_pk_no,is_bypass,bypass_date,auto_distribute,distribute_date FROM t_teambuild a,s_user b WHERE a.user_pk_no=$user_id and a.team_lead_user_pk_no=b.user_pk_no");

        $digital_marketings = $sac_name = $sac_note = $ir_emp_name = $ir_emp_position = "";
        $ir_emp_id = 0;
        if ((isset($request->src_detail)) && $request->src_detail == 'SAC') {
            $sac_name = $request->txt_sac_name;
            $sac_note = $request->txt_sac_note;
        }

        if ((isset($request->src_detail)) && $request->src_detail == 'DM') {
            $digital_mkt = $request->chk_digital_mark;
            for ($i = 0; $i < count($digital_mkt); $i++) {
                $digital_marketings .= $digital_mkt[$i] . ",";
            }
            $digital_marketings = rtrim($digital_marketings, ", ");
        }
        $ir_contract_no = 0;
        if ((isset($request->hdn_source_role)) && $request->hdn_source_role == 75) {
            $ir_emp_id = $request->txt_emp_id;
            $ir_emp_name = $request->txt_emp_name;
            $ir_emp_position = $request->txt_emp_position;
            $ir_contract_no = $request->txt_contract_no;
        }

        $hotline_items = "";
        if ((isset($request->hotline))) {
            $hotline = $request->hotline;
            for ($i = 0; $i < count($hotline); $i++) {
                if ($hotline[$i] != "") {
                    if ($hotline[$i] != '0') {
                        $hotline_items .= $hotline[$i];
                    }
                }
            }
        }

        $c_dob = $txt_marriage_anniversary = $txt_wife_dob = $txt_child_dob_1 = $txt_child_dob_2 = $txt_child_dob_3 = date("Y-m-d", strtotime('0000-01-01'));
        if (isset($request->chkKyc)) {
            $c_dob = date("Y-m-d", strtotime($request->txt_cust_dob));
            $txt_marriage_anniversary = date("Y-m-d", strtotime($request->txt_marriage_anniversary));
            $txt_wife_dob = date("Y-m-d", strtotime($request->txt_wife_dob));
            $txt_child_dob_1 = date("Y-m-d", strtotime($request->txt_child_dob_1));
            $txt_child_dob_2 = date("Y-m-d", strtotime($request->txt_child_dob_2));
            $txt_child_dob_3 = date("Y-m-d", strtotime($request->txt_child_dob_3));
        }
        $insert_date = date("Y-m-d");

        $lead_id = date("Y") . "" . str_pad(2, 4, '0', STR_PAD_LEFT);

        // sales agent assign start
        //$sales_agent = (!empty($lead_sales_agent) && $lead_sales_agent[0]->l_lead_sales_agent_pk_no) ? $lead_sales_agent[0]->l_lead_sales_agent_pk_no : 0;
        $first_name = ucwords($request->customer_first_name);
        $last_name = ucwords($request->customer_last_name);
        if ($request->hdn_source_role == 75 || $request->hdn_source_role == 203 || $request->hdn_source_role == 119) {
            if (!empty($get_team_lead)) {
                if ($get_team_lead[0]->auto_distribute == 1 && (date("Y-m-d")) <= date("Y-m-d", strtotime($get_team_lead[0]->distribute_date))) {
                    $sales_agent = DB::select(
                            DB::raw("CALL proc_getsalesagentauto_ind( $request->cmb_category, $request->cmb_area)")
                        )[0]->l_user_pk_no1 * 1;
                    $dist_type = 1;
                } else {
                    $sales_agent = ($request->sales_user_name > 0) ? $request->sales_user_name : 0;
                    $dist_type = 0;
                }
            } else {
                $sales_agent = ($request->sales_user_name > 0) ? $request->sales_user_name : 0;
                $dist_type = 0;
            }
        } else {
            if ($user_type == 2) {
                $sales_agent = $user_id;
                $dist_type = 0;
            } else {
                if (!empty($get_team_lead)) {
                    if ($get_team_lead[0]->auto_distribute == 1 && (date("Y-m-d")) <= date("Y-m-d", strtotime($get_team_lead[0]->distribute_date))) {
                        $sales_agent = DB::select(
                                DB::raw("CALL proc_getsalesagentauto_ind( $request->cmb_category, $request->cmb_area)")
                            )[0]->l_user_pk_no1 * 1;
                        $dist_type = 1;
                    } else {
                        $sales_agent = $dist_type = 0;
                    }
                } else {
                    $sales_agent = $dist_type = 0;
                }
            }
        }
        // sales agent assign end

        // Lead entry procedure

        $cmb_category = isset($request->cmb_category) ? $request->cmb_category : 0;
        $cmb_area = isset($request->cmb_area) ? $request->cmb_area : 0;
        $cmb_project_name = isset($request->cmb_project_name) ? $request->cmb_project_name : 0;
        $cmb_size = isset($request->cmb_size) ? $request->cmb_size : 0;
        $remarks = addslashes($request->remarks);


        $bed_number=isset($request->bed_number)?$request->bed_number:"";
        $budget_range = isset($request->budget_range) ? $request->budget_range : '';
        $quantity=isset($request->quantity)?$request->quantity:"";

        $lead_pk_no = DB::select(
            DB::raw("CALL proc_leads_ins ( $lead_id,'$first_name','$last_name','$request->country_code1','$request->customer_phone1','$request->country_code2','$request->customer_phone2','$request->customer_email','$request->cmb_ocupation',1,$cmb_category,$cmb_area,$cmb_project_name,$cmb_size,$request->hdn_source_id,$request->hdn_source_role,'$request->sub_source_name','$request->gift_source_name','$sac_name','$sac_note','$digital_marketings','$hotline_items','','$ir_emp_id','$ir_emp_name','$ir_emp_position',$ir_contract_no,1,'$c_dob','$request->txt_wife_name','$txt_wife_dob','$txt_marriage_anniversary','$request->txt_child_name_1','$txt_child_dob_1','$request->txt_child_name_2','$txt_child_dob_2','$request->txt_child_name_3','$txt_child_dob_3','$remarks','$request->txt_present_road_no','$request->txt_present_block_no','$request->txt_present_plot_no','$request->txt_present_sector_no','$request->txt_details_no', '$bed_number','$budget_range','$quantity',1,$user_id,'$insert_date' )")
        );

        $lead_pk_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_pk_no : 0;
        $lead_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_id : 0;
        $datetime = date("Y-m-d", strtotime('00-00-0000'));
        // $stage = ($user_type == 1) ? 1 : 3;
        /*
        as request of Mr.Saju from  bti and this email from bti now all entry will saved as lead stage from lead satag

        Romanul bti MIS
        Feb 16, 2022, 4:37 PM (1 day ago)
        to moinuddin7, Mohammad, mostafiz.mis, shumon, me

        ***TOP Urgent!!!!



        Dear Mr. Moin,

        Regards. As per verbal discussion over phone, I informed you that if any Sales Agent entry a new Lead to software then this Lead directly transfer to K Stage.

        Now our management decide that if any Sales Agent entry a new Lead, it’ll stay in L Stage.

        Please take necessary action about this mater.



        Thanks,

        Romanul



        CC: ED, Mkt.


        */
        $stage = ($user_type == 1) ? 1 : 1; //detail reason above

        $lead_qc_datetime = $lead_k1_datetime = 'NULL';
        $lead_qc_flag = $lead_qc_by = $lead_k1_flag = $lead_k1_by = 0;

        if (!empty($get_team_lead)) {
            if ($get_team_lead[0]->is_bypass == 1 && (date("Y-m-d", strtotime($get_team_lead[0]->bypass_date)) >= date("Y-m-d"))) {
                $lead_qc_flag = 1;
                $lead_qc_datetime = "'" . date("Y-m-d") . "'";
                $lead_qc_by = $user_id;
            }
        }
        $hod_pk_no = 0;
        $hot_pk_no = 0;
        $tl_pk_no = 0;
        if ($user_type == 2) {
            $lead_k1_flag = 1;
            $lead_k1_datetime = "'" . date("Y-m-d") . "'";
            $lead_k1_by = $user_id;
            $user_ch = TeamAssign::where("user_pk_no", $user_id)->first();
            $hod_pk_no = isset($user_ch->hod_user_pk_no) ? $user_ch->hod_user_pk_no : 0;
            $hot_pk_no = isset($user_ch->hot_user_pk_no) ? $user_ch->hot_user_pk_no : 0;
            $tl_pk_no = isset($user_ch->team_lead_user_pk_no) ? $user_ch->team_lead_user_pk_no : 0;
        }
        $lead_type = $request->lead_type;

        DB::statement(
            DB::raw("CALL proc_leadlifecycle_ins ( '1',$lead_pk_id,$dist_type,$hod_pk_no,'$insert_date',$hot_pk_no,'$insert_date',$tl_pk_no,'$insert_date',$sales_agent,'$insert_date',$stage,'$lead_qc_flag',$lead_qc_datetime,'$lead_qc_by','$lead_k1_flag',$lead_k1_datetime,'$lead_k1_by','$lead_type',1,$user_id,'$create_date' )")
        );

        return response()->json(['message' => 'New Lead(' . $lead_id . ') created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10];
        $lead_type = config('static_arrays.lead_type');
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = [];
        foreach ($lookup_data as $key => $value) {
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
        }

        //$lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $lead_data = DB::select("SELECT a.lead_followup_pk_no,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no and a.next_followup_flag=1
    		WHERE b.lead_pk_no=$id")[0];

        $lead_transfer_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname from_sales_agent, g.user_fullname to_sales_agent
    		FROM t_leadtransferhistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		WHERE lead_pk_no=$id");

        $lead_followup_data = DB::select("SELECT lead_followup_datetime,followup_Note,Next_FollowUp_date,next_followup_Prefered_Time,next_followup_Note,lead_stage_before_followup,lead_stage_after_followup from t_leadfollowup where lead_pk_no=$id");
        $lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");
        $countries = Country::where("iso3", '!=', '')->get();
        return view('admin.components.lead_edit', compact('lead_data', 'lead_type', 'lead_stage_arr', 'followup_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_transfer_data', 'lead_followup_data', 'lead_stage_data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $this->validate($request, [
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
            'customer_phone1' => 'required|string',
            'customer_phone2' => 'nullable|string',
            'customer_email' => 'nullable|email',
            // 'txt_organization' => 'required'
        ]);

        // return $request;
        $ldata = Lead::findOrFail($id);

        $ldata->customer_firstname = ucwords($request->customer_first_name);
        $ldata->customer_lastname = ucwords($request->customer_last_name);
        $ldata->phone1_code = $request->country_code1;
        $ldata->phone1 = $request->customer_phone1;
        $ldata->phone2_code = $request->country_code2;
        $ldata->phone2 = $request->customer_phone2;
        $ldata->email_id = $request->customer_email;
        $ldata->organization_pk_no = $request->txt_organization;

        if ($ldata->save()) {
            $lhistory = new LeadHistory();
            $lhistory->lead_pk_no = $id;
            $lhistory->customer_firstname = ucwords($request->customer_first_name);
            $lhistory->customer_lastname = ucwords($request->customer_last_name);
            $lhistory->phone1_code = $request->country_code1;
            $lhistory->phone1 = $request->customer_phone1;
            $lhistory->phone2_code = $request->country_code2;
            $lhistory->phone2 = $request->customer_phone2;
            $lhistory->email_id = $request->customer_email;
            $lhistory->organization_pk_no = $request->txt_organization;
            $lhistory->save();
            if($request->lead_type!=""){
                LeadLifeCycle::where("lead_pk_no",$id)->update([
                    "lead_type"=>$request->lead_type,
                ]);
            }

            return response()->json(['message' => 'Lead Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Lead Data update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_list($type, $transfer_type = 0)
    {

        set_time_limit(0);

        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', 0);
        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_team_members = $user_cond = '';
        $team_arr = [];
        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                    $get_all_team_members .= $team->team_members . ",";
                }
                $get_all_team_members = implode(",", array_unique(explode(",", rtrim($get_all_team_members, ", ")))) . "," . $ses_user_id;
            }
        } else {
            $get_all_team_members = $ses_user_id;
        }
        //echo $get_all_team_members;
        $user_type = Session::get('user.user_type');
        $user_role_id = Session::get('user.ses_role_lookup_pk_no');
        $is_super_admin = Session::get('user.is_super_admin');

        if ($is_super_admin == 1) {
            $user_cond = '';
        } else {
            if ($user_type == 2) {
                $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_team_members . ") )"; //or llc.created_by in(" . $get_all_team_members . ")
            } else {
                $user_cond = " and llc.created_by in(" . $get_all_team_members . ")";
            }
        }

        if ($type == 1) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE  lead_current_stage in(1,10,11)
    			 $user_cond order by llc.lead_pk_no desc");
            //AND fnc_checkdataprivs(1,1) >0 COALESCE(lead_k1_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0)=0 AND COALESCE(lead_priority_flag,0)=0 AND COALESCE(lead_transfer_flag,0)=0 AND

            $page_title = "Leads";
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            return view('admin.components.stage_wise_lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type'));
        }
        if ($type == 3) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE  lead_current_stage=3
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "K1 Leads";
        }
        if ($type == 4) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE lead_current_stage=4
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Priority Leads";
        }
        if ($type == 13) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE lead_current_stage=13
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Transferred Leads";
        }
        if ($type == 14) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE lead_current_stage=14
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Accepted Leads";
        }
        if ($type == 7) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE lead_current_stage=7
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Sold Leads";
        }
        if ($type == 5) {
            if ($user_role_id == 73) {
                return redirect()->route('lead_hold_list');
            } else {
                $lead_data = DB::select("SELECT llc.*,u.user_fullname
    			FROM t_lead2lifecycle_vw llc
    			left join s_user u on llc.created_by=u.user_pk_no
    			WHERE lead_current_stage=5
    			AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
                $page_title = "Hold Leads";
                $lead_stage_arr = config('static_arrays.lead_stage_arr');
                return view('admin.components.stage_wise_lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type'));
            }
        }
        if ($type == 6) {
            $lead_data = "";
            /*$lead_data = DB::select("SELECT llc.*,u.user_fullname
            FROM t_lead2lifecycle_vw llc
            left join s_user u on llc.created_by=u.user_pk_no
            WHERE lead_current_stage=6
            AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
             */

            $page_title = "Closed Leads";
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            return view('admin.components.stage_wise_lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type'));
        }

        if ($type == 8) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
                FROM t_lead2lifecycle_vw llc
                left join s_user u on llc.created_by=u.user_pk_no
                WHERE lead_current_stage=8
                AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Upcomming Leads";
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            return view('admin.components.stage_wise_lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type'));
        }
        if ($type == 10) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
                FROM t_lead2lifecycle_vw llc
                left join s_user u on llc.created_by=u.user_pk_no
                WHERE lead_current_stage=10
                AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Follow Up Leads";
        }
        if ($type == 11) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
                FROM t_lead2lifecycle_vw llc
                left join s_user u on llc.created_by=u.user_pk_no
                WHERE lead_current_stage=11
                AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Call Again Leads";
        }
        if ($type == 12) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
                FROM t_lead2lifecycle_vw llc
                left join s_user u on llc.created_by=u.user_pk_no
                WHERE lead_current_stage=12
                AND fnc_checkdataprivs(1,1) >0 $user_cond order by llc.lead_pk_no desc");
            $page_title = "Unaddressed Leads";
        }
        if ($user_type == 2) {
            /*if($transfer_type == 1)
            {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
            FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
            left join s_user u on llc.created_by=u.user_pk_no
            WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 0
            and transfer_from_sales_agent_pk_no=$ses_user_id order by llc.lead_pk_no desc");
            }

            if($transfer_type == 2)
            {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
            FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
            left join s_user u on llc.created_by=u.user_pk_no
            WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
            and transfer_to_sales_agent_pk_no=$ses_user_id order by llc.lead_pk_no desc");
            }*/

            if ($transfer_type == 1) {
                $lead_data = DB::select("SELECT llc.*,u.user_fullname
                    FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
                    left join s_user u on llc.created_by=u.user_pk_no
                    WHERE lt.lead_pk_no=llc.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 0
                    and transfer_from_sales_agent_pk_no=$ses_user_id order by llc.lead_pk_no desc");
            }

            if ($transfer_type == 2) {
                $lead_data = DB::select("SELECT llc.*,u.user_fullname
                    FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
                    left join s_user u on llc.created_by=u.user_pk_no
                    WHERE lt.lead_pk_no=llc.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
                    and transfer_to_sales_agent_pk_no=$ses_user_id order by llc.lead_pk_no desc");
            }
        }

        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        return view('admin.components.lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type'));
    }

    public function stageWiseLeadList($type)
    {
        set_time_limit(0);

        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', 0);

        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_team_members = $user_cond = '';
        $team_arr = [];
        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                    $get_all_team_members .= $team->team_members . ",";
                }
                $get_all_team_members = implode(",", array_unique(explode(",", rtrim($get_all_team_members, ", ")))) . "," . $ses_user_id;
            }
        } else {
            $get_all_team_members = $ses_user_id;
        }
        //echo $get_all_team_members;
        $user_type = Session::get('user.user_type');
        $is_super_admin = Session::get('user.is_super_admin');

        if ($is_super_admin == 1) {
            $user_cond = '';
        } else {
            if ($user_type == 2) {

                $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or created_by in(" . $get_all_team_members . "))";
            } else {
                $user_cond = " and created_by in(" . $get_all_team_members . ")";
            }
        }

        $table = 'stage_wise_lead_vw';

        // Table's primary key
        $primaryKey = 'lead_pk_no';

        //$where = " is_note_sheet_approved != 1";

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes

        $where = " lead_current_stage = '$type'  $user_cond";

        $columns = array(
            array('db' => 'lead_pk_no', 'dt' => 0),
            array('db' => 'lead_id', 'dt' => 1),
            array(
                'db' => 'created_at',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return date('d-m-Y', strtotime($d));
                },
            ),
            array('db' => 'customer_lastname', 'dt' => 3),
            array('db' => 'phone1', 'dt' => 4),
            array('db' => 'project_name', 'dt' => 5),
            array('db' => 'lead_sales_agent_name', 'dt' => 6),
            array('db' => 'user_fullname', 'dt' => 7),
            array('db' => 'lead_current_stage', 'dt' => 8),
            array('db' => 'source_auto_usergroup', 'dt' => 9),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST'),
        );

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        echo json_encode(DataTableClass::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $where));
    }

    public function deleteClosedLead($lead_id)
    {
        if (empty($lead_id)) {
            //return response()->json(['message' => 'Lead(' . $lead_id . ') Not Found.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
            return back()->with('err', 'Lead(' . $lead_id . ') Not Found.');
        }

        //return 'deleted';
        $lead = DB::table('t_leads')
            ->where('lead_pk_no', $lead_id)
            ->get();
        if (!$lead->isEmpty()) {
            DB::table('t_leads')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadlifecycle = DB::table('t_leadlifecycle')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadlifecycle->isEmpty()) {
            DB::table('t_leadlifecycle')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadfollowup = DB::table('t_leadfollowup')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadfollowup->isEmpty()) {
            DB::table('t_leadfollowup')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadshistory = DB::table('t_leadshistory')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadshistory->isEmpty()) {
            DB::table('t_leadshistory')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadstagehistory = DB::table('t_leadstagehistory')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadstagehistory->isEmpty()) {
            DB::table('t_leadstagehistory')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadtransfer = DB::table('t_leadtransfer')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadtransfer->isEmpty()) {

            DB::table('t_leadtransfer')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        $leadtransferhistory = DB::table('t_leadtransferhistory')
            ->where('lead_pk_no', $lead_id)
            ->get();

        if (!$leadtransferhistory->isEmpty()) {
            DB::table('t_leadtransferhistory')
                ->where('lead_pk_no', $lead_id)
                ->delete();
        }

        return back()->with('msg', 'Lead(' . $lead_id . ') deleted successfully.');

        //return response()->json(['message' => 'Lead(' . $lead_id . ') deleted successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_list_view($type, $transfer_type = 0)
    {
        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_team_members = $user_cond = '';
        $team_arr = [];
        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                    $get_all_team_members .= ($team->team_members != "") ? $team->team_members . "," . $ses_user_id : $ses_user_id;
                }
            }
        } else {
            $get_all_team_members = $ses_user_id;
        }

        $user_type = Session::get('user.user_type');
        $is_super_admin = Session::get('user.is_super_admin');

        if ($is_super_admin == 1) {
            $user_cond = '';
        } else {
            if ($user_type == 2) {
                $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or created_by in(" . $get_all_team_members . "))";
            } else {
                $user_cond = " and created_by in(" . $get_all_team_members . ")";
            }
        }

        if ($type == 1) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_k1_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0)=0 AND COALESCE(lead_priority_flag,0)=0 AND COALESCE(lead_transfer_flag,0)=0 AND lead_current_stage in(1,10,11)
				AND fnc_checkdataprivs(1,1) >0 $user_cond");

            $page_title = "Leads";
        }
        if ($type == 3) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_k1_flag,0) = 1 AND COALESCE(lead_priority_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0 AND lead_current_stage=3
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "K1 Leads";
        }
        if ($type == 4) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_priority_flag,0) = 1 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Priority Leads";
        }
        if ($type == 13) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_transfer_from_sales_agent_pk_no,0) = 1
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Transferred Leads";
        }
        if ($type == 14) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_transfer_flag,0) = 1
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Accepted Leads";
        }
        if ($type == 7) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_sold_flag,0) =1
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Sold Leads";
        }
        if ($type == 5) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_hold_flag,0) = 1 AND lead_current_stage=5
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Hold Leads";
        }
        if ($type == 6) {
            $lead_data = DB::select("SELECT llc.*
				FROM t_lead2lifecycle_vw llc
				WHERE COALESCE(lead_closed_flag,0) = 1
				AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Closed Leads";
        }

        if ($user_type == 2) {
            if ($transfer_type == 1) {
                $lead_data = DB::select("SELECT llc.*
					FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
					WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 0
					and transfer_from_sales_agent_pk_no=$ses_user_id");
            }

            if ($transfer_type == 2) {
                $lead_data = DB::select("SELECT llc.*
					FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
					WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
					and transfer_to_sales_agent_pk_no=$ses_user_id");
            }
        }
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        return view('admin.components.lead_list_view', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function get_team_users(Request $request)
    {
        $team_id = $request->team_id;
        $users = User::where('is_super_admin', '!=', 1)->orWhereNull('is_super_admin')->get();
        $user_arr = [];
        foreach ($users as $user) {
            $user_arr[$user->teamUser['user_pk_no']] = $user->name;
        }

        $hod_arr = $hot_arr = $tl_arr = $team_user = $agent_arr = [];
        $get_team_info = TeamAssign::where('team_lookup_pk_no', $team_id)->get();

        if (!empty($get_team_info)) {
            foreach ($get_team_info as $team) {
                if ($team->hod_user_pk_no != 0) {
                    $hod_arr[$team->hod_user_pk_no] = $user_arr[$team->hod_user_pk_no];
                }

                if ($team->hot_user_pk_no != 0) {
                    $hot_arr[$team->hot_user_pk_no] = $user_arr[$team->hot_user_pk_no];
                }

                if ($team->team_lead_user_pk_no != 0) {
                    $tl_arr[$team->team_lead_user_pk_no] = $user_arr[$team->team_lead_user_pk_no];
                }

                if ($team->hod_flag == 0 && $team->hot_flag == 0 && $team->team_lead_flag == 0) {
                    $agent_arr[$team->user_pk_no] = $user_arr[$team->user_pk_no];
                }
            }

            $team_user = array(
                'hod_arr' => $hod_arr,
                'hot_arr' => $hot_arr,
                'tl_arr' => $tl_arr,
                'agent_arr' => $agent_arr,
            );
        }

        return json_encode($team_user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function load_area_project_size(Request $request)
    {
        $cat_id = $request->cat_id;
        $area_id = $request->area_id;
        $ses_lead_type = Session::get('user.ses_lead_type');
        $ses_super_admin = Session::get('user.is_super_admin');
        $group_id = Session::get('user.ses_role_lookup_pk_no');

        $area_arr = $project_arr = $size_arr = [];
        $area_cond = ($area_id > 0) ? " and a.area_lookup_pk_no=$area_id" : "";
        if ($ses_lead_type = '0' || $ses_super_admin == 1 || $group_id == '440' || $ses_lead_type == 2 || $ses_lead_type == 11 || $ses_lead_type == 7 || $ses_lead_type == 9 || $group_id != '73' || $group_id != '74' || $group_id != '75' || $group_id != '76' || $group_id != '447' || $group_id != '203') {
            $lead_type_cond = '';
        } else {
            $lead_type_cond = "and a.lead_type ='$ses_lead_type'";
        }

        $get_area_project_size_info = DB::select("SELECT a.area_lookup_pk_no,b.lookup_name area_name,a.size_lookup_pk_no,c.lookup_name size_name,a.project_lookup_pk_no,d.lookup_name project_name
    		FROM s_projectwiseflatlist a
    		LEFT JOIN s_lookdata b ON a.area_lookup_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.size_lookup_pk_no=c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.project_lookup_pk_no=d.lookup_pk_no
    		WHERE a.category_lookup_pk_no=$cat_id $area_cond and b.lookup_row_status=1 and c.lookup_row_status=1 and d.lookup_row_status=1  $lead_type_cond ");

        if (!empty($get_area_project_size_info)) {
            foreach ($get_area_project_size_info as $aps) {
                if ($aps->area_lookup_pk_no != "") {
                    $area_arr[$aps->area_lookup_pk_no] = $aps->area_name;
                }

                if ($aps->size_lookup_pk_no != "") {
                    $size_arr[$aps->size_lookup_pk_no] = $aps->size_name;
                }

                if ($aps->project_lookup_pk_no != "") {
                    $project_arr[$aps->project_lookup_pk_no] = $aps->project_name;
                }
            }
        }

        $category_wise_agent_data = DB::table('s_user')
            ->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('s_user.user_type', 2)
            ->where('t_teambuild.hod_flag', 0)
            ->where('t_teambuild.hot_flag', 0)
            ->where('t_teambuild.team_lead_flag', 0)
            ->where('category_lookup_pk_no', $cat_id)
            ->get();

        $sales_agent = [];
        if (!empty($category_wise_agent_data)) {
            foreach ($category_wise_agent_data as $row) {
                $sales_agent[$row->user_pk_no] = $row->user_fullname;
            }
        }

        $aps_data = array(
            'area_arr' => $area_arr,
            'size_arr' => $size_arr,
            'project_arr' => $project_arr,
            'sales_agent' => $sales_agent,
        );

        return json_encode($aps_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function load_project_size(Request $request)
    {
        $group_id = Session::get('user.ses_role_lookup_pk_no');

        $cat_id = $request->cat_id;
        $area_id = $request->area_id;
        $project = $request->cmb_project_name;
        $ses_lead_type = Session::get('user.ses_lead_type');
        $ses_super_admin = Session::get('user.is_super_admin');
        if ($ses_lead_type == '0' || $ses_super_admin == 1 || $group_id == '440' || $ses_lead_type == 2 || $ses_lead_type == 11 || $ses_lead_type == 7 || $ses_lead_type == 9 || $group_id != '73' || $group_id != '74' || $group_id != '75' || $group_id != '76' || $group_id != '447' || $group_id != '203') {
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
        if ($ses_super_admin == 1 || $group_id == '440' || $ses_lead_type == 2 || $ses_lead_type == 11 || $ses_lead_type == 7 || $ses_lead_type == 9 || $group_id != '73' || $group_id != '74' || $group_id != '75' || $group_id != '76' || $group_id != '447' || $group_id != '203') {
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
        if ($ses_super_admin == 1 || $group_id == '440' || $ses_lead_type == 2 || $ses_lead_type == 11 || $ses_lead_type == 7 || $ses_lead_type == 9 || $group_id != '73' || $group_id != '74' || $group_id != '75' || $group_id != '76' || $group_id != '447' || $group_id != '203') {
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
        $aps_data = array(
            'cat_arr' => $cat_arr,
            'project_arr' => $project_arr,
            'size_arr' => $size_arr,
        );

        return json_encode($aps_data);

        $area_arr = $project_arr = $size_arr = [];
        $area_cond = ($area_id > 0) ? " and a.area_lookup_pk_no=$area_id" : "";
        $get_area_project_size_info = DB::select("SELECT a.area_lookup_pk_no,b.lookup_name area_name,a.size_lookup_pk_no,c.lookup_name size_name,a.project_lookup_pk_no,d.lookup_name project_name
    		FROM s_projectwiseflatlist a
    		LEFT JOIN s_lookdata b ON a.area_lookup_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.size_lookup_pk_no=c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.project_lookup_pk_no=d.lookup_pk_no
    		WHERE a.category_lookup_pk_no=$cat_id $area_cond and b.lookup_row_status=1 and c.lookup_row_status=1 and d.lookup_row_status=1");

        if (!empty($get_area_project_size_info)) {
            foreach ($get_area_project_size_info as $aps) {
                if ($aps->area_lookup_pk_no != "") {
                    $area_arr[$aps->area_lookup_pk_no] = $aps->area_name;
                }

                if ($aps->size_lookup_pk_no != "") {
                    $size_arr[$aps->size_lookup_pk_no] = $aps->size_name;
                }

                if ($aps->project_lookup_pk_no != "") {
                    $project_arr[$aps->project_lookup_pk_no] = $aps->project_name;
                }
            }
        }

        $category_wise_agent_data = DB::table('s_user')
            ->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('s_user.user_type', 2)
            ->where('t_teambuild.hod_flag', 0)
            ->where('t_teambuild.hot_flag', 0)
            ->where('t_teambuild.team_lead_flag', 0)
            ->where('category_lookup_pk_no', $cat_id)
            ->get();

        $sales_agent = [];
        if (!empty($category_wise_agent_data)) {
            foreach ($category_wise_agent_data as $row) {
                $sales_agent[$row->user_pk_no] = $row->user_fullname;
            }
        }

        $aps_data = array(
            'area_arr' => $area_arr,
            'size_arr' => $size_arr,
            'project_arr' => $project_arr,
            'sales_agent' => $sales_agent,
        );

        return json_encode($aps_data);
    }

    public function lead_view($id)
    {
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = [];
        foreach ($lookup_data as $key => $value) {
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
        }

        //$lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $lead_data = DB::select("SELECT a.lead_followup_pk_no,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no and a.next_followup_flag=1
    		WHERE b.lead_pk_no=$id")[0];

        $lead_transfer_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname from_sales_agent, g.user_fullname to_sales_agent, a.updated_at updated_at
    		FROM t_leadtransferhistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		WHERE lead_pk_no=$id");

        $lead_followup_data = DB::select("SELECT lead_followup_datetime,followup_Note,Next_FollowUp_date,next_followup_Prefered_Time,next_followup_Note,lead_stage_before_followup,lead_stage_after_followup from t_leadfollowup where lead_pk_no=$id");
        $lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");

        $lead_history = DB::select("SELECT CONCAT(customer_firstname,' ',customer_lastname) full_name,phone1,phone2,email_id
            FROM t_leadshistory WHERE lead_pk_no=$id
            GROUP BY lead_pk_no,customer_firstname,customer_lastname,phone1,phone2,email_id");

        $lead_type = config("static_arrays.lead_type");

        return view('admin.components.lead_view', compact('lead_type', 'lead_data', 'lead_stage_arr', 'followup_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_transfer_data', 'lead_followup_data', 'lead_stage_data', 'lead_history'));

        //return view('admin.components.lead_view', compact('lead_data', 'lead_stage_arr', 'followup_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_transfer_data', 'lead_followup_data', 'lead_stage_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function load_sales_agent_by_area(Request $request)
    {
        $category_id = $request->category_id;
        $area_id = $request->area_id;
        $area_cond = ($area_id > 0) ? " and c.area_lookup_pk_no=$area_id" : "";
        $size_wise_agent_data = DB::select("SELECT a.user_pk_no,a.user_fullname ,b.area_lookup_pk_no
    		FROM s_user a,t_teambuild b, t_teambuildchd c
    		WHERE a.user_pk_no=b.user_pk_no AND b.`teammem_pk_no`=c.`teammem_pk_no` AND a.user_type=2 AND b.category_lookup_pk_no=$category_id AND b.hod_flag=0 AND b.hot_flag=0
    		AND b.team_lead_flag=0  $area_cond
    		GROUP BY a.user_pk_no,a.user_fullname ,b.area_lookup_pk_no");

        $sales_agent = [];
        if (!empty($size_wise_agent_data)) {
            foreach ($size_wise_agent_data as $row) {
                $sales_agent[$row->user_pk_no] = $row->user_fullname;
            }

            $sales_agents = array(
                'agent_arr' => $sales_agent,
            );
        }

        return json_encode($sales_agents);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function check_if_phone_no_exist(Request $request)
    {

        $phone_no = $request->phone_no;
        $business_unit=$request->business_unit;   // lead_type

        $ses_user_id = Session::get('user.ses_user_pk_no');
        if($request->business_unit!=""){
        $lead_info = DB::table('t_lead2lifecycle_vw')
            ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
            ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->whereNotIn('lead_current_stage', [5, 6, 7])
            ->where('phone1','like', "%".$phone_no)

            ->where('lead_type',$business_unit)
            ->get();
        }else{
            $lead_info = DB::table('t_lead2lifecycle_vw')
            ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
            ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->whereNotIn('lead_current_stage', [5, 6, 7])
            ->where('phone1','like', "%".$phone_no)
            ->get();
        }


        if (count($lead_info) == 0) {
            if($request->business_unit!=""){
            $lead_info = DB::table('t_lead2lifecycle_vw')
                ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
                ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
                ->whereNotIn('lead_current_stage', [5, 6, 7])
                ->where('phone2','like', "%".$phone_no)
                ->where('lead_type',$business_unit)
                ->get();
            }else{
                $lead_info = DB::table('t_lead2lifecycle_vw')
                ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
                ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
                ->whereNotIn('lead_current_stage', [5, 6, 7])
                ->where('phone2','like', "%".$phone_no)
                ->get();
            }

        }


        $sales_agents = [];
        if (!empty($lead_info)) {
            foreach ($lead_info as $row) {
                $sales_agents = array(
                    'lead_id' => $row->lead_id,
                    'customer_name' => $row->customer_firstname . " " . $row->customer_lastname,
                    'user_group' => $row->user_group_name,
                    'agent_name' => $row->lead_sales_agent_name,
                    'agent_phone' => $row->mobile_no,
                );
            }
        }

        return json_encode($sales_agents);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function check_if_phone_no_exist_hold_stage(Request $request)
    {

        $phone_no = $request->phone_no;

        $ses_user_id = Session::get('user.ses_user_pk_no');
        $lead_info = DB::table('t_lead2lifecycle_vw')
            ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
            ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->whereNotIn('lead_current_stage', [6, 7])
            ->whereNotIn('lead_sales_agent_pk_no', [$ses_user_id])
            ->where('phone1','like', "%".$phone_no)
            ->get();


        if (count($lead_info) == 0) {
            $lead_info = DB::table('t_lead2lifecycle_vw')
                ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
                ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
                ->whereNotIn('lead_current_stage', [6, 7])
                ->whereNotIn('lead_sales_agent_pk_no', [$ses_user_id])
                ->where('phone2','like', "%".$phone_no)
                ->get();
        }

        $to = \Carbon\Carbon::createFromFormat('Y-m-d', date("Y-m-d"));

        $from = \Carbon\Carbon::createFromFormat('Y-m-d', date("Y-m-d", strtotime($lead_info[0]->lead_hold_datetime)));

        $diff_in_months = $to->diffInMonths($from);
        $sales_agents = [];
        if (!empty($lead_info) && ($lead_info[0]->lead_current_stage == 5) && $diff_in_months <= 3) {
            foreach ($lead_info as $row) {
                $sales_agents = array(
                    'lead_id' => $row->lead_id,
                    'customer_name' => $row->customer_firstname . " " . $row->customer_lastname,
                    'user_group' => $row->user_group_name,
                    'agent_name' => $row->lead_sales_agent_name,
                    'agent_phone' => $row->mobile_no,
                );
            }
        }


        return json_encode($sales_agents);
    }


    public function import_csv()
    {
        return view('admin.lead_management.lead_import_by_csv');
    }

    public function csv_to_array($filename = '', $delimiter = ',')
    {

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

    public function store_import_csv(Request $request)
    {
        $user_group_arr = ["CRE" => 73, "DM" => 74, "IR" => 75, "HC" => 76, "ST" => 77, "SAC" => 119, "EC" => 203];
        $lead_stage_arr = [
            'Lead' => 1,
            'QC Passed' => 2,
            'K1' => 3,
            'Priority' => 4,
            'Hold' => 5,
            'Closed' => 6,
            'Sold' => 7,
            'Upcoming' => 8,
            'Junk' => 9,
            'Follow up' => 10,
            'Call again' => 11,
            'Unaddressed' => 12,
        ];
        $lookup_data = LookupData::all();
        $lookup_data_arr = [];
        foreach ($lookup_data as $value) {
            $lookup_data_arr[$value->lookup_name] = $value->lookup_pk_no;
        }

        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        for ($i = 0; $i < count($leadArr); $i++) {
            $ocupation = trim($leadArr[$i]["Occupation"]);
            $category = trim($leadArr[$i]["Category"]);
            $area = trim($leadArr[$i]["Area"]);
            $project_name = trim($leadArr[$i]["Project_Name"]);
            $size = trim($leadArr[$i]["Size"]);
            $cre = trim(@$leadArr[$i]["CRE"]);
            $user_group = trim(@$leadArr[$i]["User_Group"]);
            $sub_source_name = trim($leadArr[$i]["Sub_Source_Name"]);
            $digital_marketing = trim($leadArr[$i]["Digital_Marketing"]);
            $emp_id = trim($leadArr[$i]["Emp_ID"]);
            $ir_name = trim(@$leadArr[$i]["IR_Name"]);
            $ir_position = trim(@$leadArr[$i]["IR_Position"]);
            $ir_contact_number = trim(@$leadArr[$i]["IR_Contact_Number"]);
            $sac_name = trim($leadArr[$i]["SAC_Name"]);
            $sac_note = trim($leadArr[$i]["SAC_Note"]);
            $hotline = trim($leadArr[$i]["Hotline"]);
            $customer_dob = trim($leadArr[$i]["Customer_DOB"]);
            $marriage_anniversary = trim($leadArr[$i]["Marriage_Anniversary"]);
            $wife_name = trim($leadArr[$i]["Wife_Name"]);
            $wife_dob = trim($leadArr[$i]["Wife_DOB"]);
            $children_name1 = trim($leadArr[$i]["Children_Name1"]);
            $children_dob1 = trim($leadArr[$i]["Children_DOB1"]);
            $children_name2 = trim($leadArr[$i]["Children_Name2"]);
            $children_dob2 = trim($leadArr[$i]["Children_DOB2"]);
            $children_name3 = trim($leadArr[$i]["Children_Name3"]);
            $children_dob3 = trim($leadArr[$i]["Children_DOB3"]);
            $agent_name = trim($leadArr[$i]["Agent_Name"]);
            $current_stage = trim($leadArr[$i]["current_stage"]);
            $lead_followup_date = date("Y-m-d", strtotime("2020-03-19"));
            $followup_type = trim($leadArr[$i]["followup_type"]);
            $followup_note = trim($leadArr[$i]["followup_note"]);

            $customer_firstname = (string)trim($leadArr[$i]["Customer_First_Name"]);
            $customer_lastname = (string)trim($leadArr[$i]["Customer_Last_Name"]);
            $phone1_code = (string)trim($leadArr[$i]["Country_Code1"]);
            $phone1 = (string)trim($leadArr[$i]["Phone_Number_1"]);
            $phone2_code = (string)trim($leadArr[$i]["Country_Code2"]);
            $phone2 = (string)trim($leadArr[$i]["Phone_Number_2"]);
            $email_id = (string)trim($leadArr[$i]["Email"]);

            $lead_id = date("Y") . "" . str_pad(2, 4, '0', STR_PAD_LEFT);

            $lead_insert_proc = DB::select(
                DB::raw("CALL proc_leads_ins ( $lead_id,'$customer_firstname','$customer_lastname','$phone1_code','$phone1','$phone2_code','$phone2','$email_id'," . (isset($lookup_data_arr[$ocupation]) ? $lookup_data_arr[$ocupation] : 0) . ",1," . (isset($lookup_data_arr[$category]) ? $lookup_data_arr[$category] : 0) . "," . (isset($lookup_data_arr[$area]) ? $lookup_data_arr[$area] : 0) . "," . (isset($lookup_data_arr[$project_name]) ? $lookup_data_arr[$project_name] : 0) . "," . (isset($lookup_data_arr[$size]) ? $lookup_data_arr[$size] : 0) . "," . (isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0) . "," . (isset($user_group_arr[$user_group]) ? $user_group_arr[$user_group] : 0) . ",'" . ((string)isset($sub_source_name) ? $sub_source_name : '') . "','" . ((string)isset($sac_name) ? $sac_name : '') . "','" . ((string)isset($sac_note) ? $sac_note : '') . "','" . (isset($digital_marketing) ? $digital_marketing : '') . "','" . (isset($hotline) ? $hotline : null) . "',''," . (($emp_id != '') ? $emp_id : 0) . ",'" . ((string)isset($ir_name) ? $ir_name : '') . "','" . ((string)isset($ir_position) ? $ir_position : '') . "'," . (($ir_contact_number != '') ? $ir_contact_number : 0) . ",1,'" . (($customer_dob != '') ? date("Y-m-d", strtotime($customer_dob)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)($wife_name != '') ? $wife_name : '') . "','" . (($wife_dob != '') ? date("Y-m-d", strtotime($wife_dob)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (($marriage_anniversary != '') ? date("Y-m-d", strtotime($marriage_anniversary)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)($children_name1 != '') ? $children_name1 : '') . "','" . (($children_dob1 != '') ? date("Y-m-d", strtotime($children_dob1)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)($children_name2 != '') ? $children_name2 : '') . "','" . (($children_dob2 != '') ? date("Y-m-d", strtotime($children_dob2)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)($children_name3 != '') ? $children_name3 : '') . "','" . (($children_dob3 != '') ? date("Y-m-d", strtotime($children_dob3)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)trim($leadArr[$i]["Remarks"])) . "',1,0,0,0,0,0,0,0," . (isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0) . ",'" . (date('Y-m-d')) . "')")
            );

            /*$leadData = [
            "customer_firstname"=> (string)trim($leadArr[$i]["Customer_First_Name"]),
            "customer_lastname"=> (string)trim($leadArr[$i]["Customer_Last_Name"]),
            "phone1_code"=> (string)trim($leadArr[$i]["Country_Code1"]),
            "phone1"=> (string)trim($leadArr[$i]["Phone_Number_1"]),
            "phone2_code"=> (string)trim($leadArr[$i]["Country_Code2"]),
            "phone2"=> (string)trim($leadArr[$i]["Phone_Number_2"]),
            "email_id"=> (string)trim($leadArr[$i]["Email"]),
            "occupation_pk_no"=> isset($lookup_data_arr[$ocupation])?$lookup_data_arr[$ocupation]:0,
            "project_category_pk_no"=> isset($lookup_data_arr[$category])?$lookup_data_arr[$category]:0,
            "project_area_pk_no"=> isset($lookup_data_arr[$area])?$lookup_data_arr[$area]:0,
            "Project_pk_no"=> isset($lookup_data_arr[$project_name])?$lookup_data_arr[$project_name]:0,
            "project_size_pk_no"=> isset($lookup_data_arr[$size])?$lookup_data_arr[$size]:0,
            "source_auto_pk_no"=> isset($user_data_arr[$cre])?$user_data_arr[$cre]:0,
            "source_auto_usergroup_pk_no"=> isset($user_group_arr[$user_group])?$user_group_arr[$user_group]:0,
            "source_auto_sub"=> (string)isset($sub_source_name)?$sub_source_name:'',

            "source_digital_marketing"=> isset($digital_marketing)?$digital_marketing:'',

            "source_ir_emp_id"=> ($emp_id!='')?$emp_id:0,
            "source_ir_name"=> (string)isset($ir_name)?$ir_name:'',
            "source_ir_position"=> (string)isset($ir_position)?$ir_position:'',

            "source_ir_contact_no"=> ($ir_contact_number!='')?$ir_contact_number:0,
            "source_sac_name"=> (string)isset($sac_name)?$sac_name:'',
            "source_sac_note"=> (string)isset($sac_note)?$sac_note:'',
            "source_hotline"=> isset($hotline)?$hotline:NULL,
            "Customer_dateofbirth"=> ($customer_dob!='')?date("Y-m-d", strtotime($customer_dob)):NULL,
            "Marriage_anniversary"=> ($marriage_anniversary!='')?date("Y-m-d", strtotime($marriage_anniversary)):NULL,
            "customer_wife_name"=> (string)($wife_name!='')?$wife_name:'',
            "customer_wife_dataofbirth"=> ($wife_dob!='')?date("Y-m-d", strtotime($wife_dob)):NULL,
            "children_name1"=> (string)($children_name1!='')?$children_name1:'',
            "children_dateofbirth1"=> ($children_dob1!='')?date("Y-m-d", strtotime($children_dob1)):NULL,
            "children_name2"=> (string)($children_name2!='')?$children_name2:'',
            "children_dateofbirth2"=> ($children_dob2!='')?date("Y-m-d", strtotime($children_dob2)):NULL,
            "children_name3"=> (string)($children_name3!='')?$children_name3:'',
            "children_dateofbirth3"=> ($children_dob3!='')?date("Y-m-d", strtotime($children_dob3)):NULL,
            "remarks"=> '',
            'created_by' => isset($user_data_arr[$cre])?$user_data_arr[$cre]:0
            ];*/

            //$lead_mst_id = DB::table('t_leads')->insertGetId($leadData);
            //echo "<pre>";
            $lead_mst_id = $lead_insert_proc[0]->l_lead_pk_no;
            if ($lead_mst_id != "") {
                $leadLifeCycleData =
                    [
                        'lead_pk_no' => $lead_mst_id,
                        'lead_current_stage' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                        'lead_sales_agent_pk_no' => isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : '',
                        'lead_sales_agent_assign_dt' => date("Y-m-d"),
                        'created_by' => isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0,
                    ];

                if ($current_stage == "K1") {
                    $lead_k1_flag = 1;
                    $lead_k1_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_k1_flag'] = $lead_k1_flag;
                    $leadLifeCycleData['lead_k1_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_k1_by'] = $lead_k1_by;
                }

                if ($current_stage == "Priority") {
                    $lead_priority_flag = 1;
                    $lead_priority_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_priority_flag'] = $lead_priority_flag;
                    $leadLifeCycleData['lead_priority_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_priority_by'] = $lead_priority_by;
                }

                if ($current_stage == "Hold") {
                    $lead_hold_flag = 1;
                    $lead_hold_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_hold_flag'] = $lead_hold_flag;
                    $leadLifeCycleData['lead_hold_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_hold_by'] = $lead_hold_by;
                }

                if ($current_stage == "Closed") {
                    $lead_closed_flag = 1;
                    $lead_closed_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_closed_flag'] = $lead_closed_flag;
                    $leadLifeCycleData['lead_closed_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_closed_by'] = $lead_closed_by;
                }

                if ($current_stage == "Sold") {
                    $lead_sold_flag = 1;
                    $lead_sold_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_sold_flag'] = $lead_sold_flag;
                    $leadLifeCycleData['lead_sold_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_sold_by'] = $lead_sold_by;
                }

                DB::table('t_leadlifecycle')->insert($leadLifeCycleData);

                if ($current_stage != 'Lead' && $lead_followup_date != "") {
                    $leadFollowupData =
                        [
                            'lead_pk_no' => $lead_mst_id,
                            'lead_followup_datetime' => $lead_followup_date,
                            'Followup_type_pk_no' => isset($lookup_data_arr[$followup_type]) ? $lookup_data_arr[$followup_type] : 0,
                            'followup_Note' => (string)($followup_note != '') ? $followup_note : '',
                            'lead_stage_before_followup' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                            'next_followup_flag' => 1,
                            'Next_FollowUp_date' => $lead_followup_date,
                            'next_followup_Note' => (string)($followup_note != '') ? $followup_note : '',
                            'lead_stage_after_followup' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                            'created_by' => isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0,
                        ];

                    DB::table('t_leadfollowup')->insert($leadFollowupData);
                }
            } else {
                echo "Failed" . $customer_firstname . " = " . $phone1 . "<br />";
                die;
            }
        }

        return redirect()->route('lead.index');
        //return response()->json(['message' => 'New Lead(' . $lead_id . ') created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

        //DB::table('table_name')->insert($data);
        //return 'Jobi done or what ever';
    }

    public function lead_download($id)
    {
        $lead_data = DB::table("t_lead2lifecycle_vw")->where("lead_pk_no", $id)->get();
        $lead_followup = DB::table("t_leadfollowup")->where("lead_pk_no", $id)->orderBy("lead_followup_datetime", "desc")->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );
        $columns = array("Created Date", "Lead ID", "Customer Name", "Mobile", "Category Name", "Area", "Project", "Size", "Current Stage", "Sales Agent Name", "Creator Name");
        $columns_1 = array("Followup Date", "Followup Previous Stage", "Followup After Stage", "Followup Note", "Next Followup date", "Next Followup time", "Next Followup Note");

        $callback = function () use ($lead_data, $columns, $lead_followup, $columns_1) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            $iteration = 1;
            foreach ($lead_data as $ldata) {

                $stage = isset($lead_stage_arr[$ldata->lead_current_stage]) ? $lead_stage_arr[$ldata->lead_current_stage] : "N/A";

                fputcsv($file, array(
                    date("d/m/Y", strtotime($ldata->created_at)),
                    $ldata->lead_id,
                    $ldata->customer_firstname . ' ' . $ldata->customer_lastname,
                    $ldata->phone1, $ldata->project_category_name, $ldata->project_area, $ldata->project_name, $ldata->project_size, $stage, $ldata->lead_sales_agent_name, $ldata->user_full_name
                ));
                $iteration = $iteration + 1;
            }
            fclose($file);
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_1);
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            $iteration = 1;
            foreach ($lead_followup as $ldata) {

                $b_stage = isset($lead_stage_arr[$ldata->lead_stage_before_followup]) ? $lead_stage_arr[$ldata->lead_stage_before_followup] : "N/A";
                $a_stage = isset($lead_stage_arr[$ldata->lead_stage_after_followup]) ? $lead_stage_arr[$ldata->lead_stage_after_followup] : "N/A";

                fputcsv($file, array(
                    date('d/m/Y', strtotime($ldata->lead_followup_datetime)),
                    $b_stage, $a_stage,
                    $ldata->followup_Note, date("d/m/Y", strtotime($ldata->Next_FollowUp_date)),
                    date("h:i:s", strtotime($ldata->next_followup_Prefered_Time)), $ldata->next_followup_Note
                ));
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function cancel_sale($lead_pk_no)
    {
        $lead_life = LeadLifeCycle::where('lead_pk_no', $lead_pk_no)->first();
        FlatSetup::where('flatlist_pk_no', $lead_life->flatlist_pk_no)->update(['flat_status' => 0]);
        $lead_life->is_cancel = 1;
        $lead_life->is_cancel_by = Session::get('user.ses_user_pk_no');
        $lead_life->save();
        return response()->json(['message' => 'Lead Cancel successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    public function lead_hold_list()
    {
        $sales_agent = DB::table('s_user')->where('user_type', 2)->get();
        return view("admin.lead_management.lead_hold.hold_lead_list", compact("sales_agent"));
    }

    public function lead_hold_list_table()
    {
        $table = 'stage_wise_lead_vw';

        // Table's primary key
        $primaryKey = 'lead_pk_no';

        //$where = " is_note_sheet_approved != 1";

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes

        $where = " lead_current_stage = '5' ";

        $columns = array(
            array('db' => 'lead_pk_no', 'dt' => 0),
            array('db' => 'lead_id', 'dt' => 1),
            array(
                'db' => 'created_at',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return date('d-m-Y', strtotime($d));
                },
            ),
            array('db' => 'customer_lastname', 'dt' => 3),
            array('db' => 'phone1', 'dt' => 4),
            array('db' => 'project_name', 'dt' => 5),
            array('db' => 'lead_sales_agent_name', 'dt' => 6),
            array('db' => 'user_fullname', 'dt' => 7),
            array('db' => 'lead_pk_no', 'dt' => 8),
        );

        // SQL server connection information
        $sql_details = array(
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST'),
        );

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        echo json_encode(DataTableClass::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $where));
    }

    public function lead_transfer_by_cre(Request $request)
    {

        if (!empty($request->lead_pk_no)) {
            for ($i = 0; $i < count($request->lead_pk_no); $i++) {
                $lead = LeadLifeCycle::where('lead_pk_no', $request->lead_pk_no[$i])->first();
                $lead->lead_current_stage = 1;
                $lead->lead_hold_flag = 0;
                $lead->lead_sales_agent_pk_no = $request->salesAgent;
                $lead->lead_sales_agent_assign_dt = date("Y-m-d");
                $lead->save();
                $lead = DB::table('t_lead2lifecycle_vw')->where('lead_pk_no', $request->lead_pk_no[$i])->first();
                $lead_transfer = new TransferHistory();
                $lead_transfer->lead_pk_no = $lead->lead_pk_no;
                $lead_transfer->project_category_pk_no = $lead->project_category_pk_no;
                $lead_transfer->project_area_pk_no = $lead->project_area_pk_no;
                $lead_transfer->Project_pk_no = $lead->Project_pk_no;
                $lead_transfer->project_size_pk_no = $lead->project_size_pk_no;
                $lead_transfer->transfer_from_sales_agent_pk_no = Session::get('user.ses_user_pk_no');
                $lead_transfer->transfer_to_sales_agent_pk_no = $request->salesAgent;
                $lead_transfer->created_by = Session::get('user.ses_user_pk_no');
                $lead_transfer->created_at = date("Y-m-d");
                $lead_transfer->save();
            }
        }
        return response()->json(['message' => 'Lead Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    public function lead_closed_by_cre(Request $request)
    {

        if (!empty($request->lead_pk_no)) {
            for ($i = 0; $i < count($request->lead_pk_no); $i++) {
                $lead = LeadLifeCycle::where('lead_pk_no', $request->lead_pk_no[$i])->first();
                $lead->lead_current_stage = 6;
                $lead->lead_closed_flag = Session::get('user.ses_user_pk_no');
                $lead->lead_closed_by = date("Y-m-d");
                $lead->lead_closed_datetime = date("Y-m-d");
                $lead->save();
            }
        }
        return response()->json(['message' => 'Lead Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    public function sold_lead_migration()
    {
        $lead_sold_list = LeadLifeCycleView::where('lead_current_stage', 7)->get();

        if (!empty($lead_sold_list)) {
            foreach ($lead_sold_list as $lead) {
                $lead_life = LeadLifeCycle::where('lead_pk_no', $lead->lead_pk_no)->first();
                $user_ch = TeamAssign::where("user_pk_no", $lead_life->lead_sales_agent_pk_no)->first();
                $lead_life->lead_ch_pk_no = isset($user_ch->hod_user_pk_no) ? $user_ch->hod_user_pk_no : 0;
                $lead_life->lead_tl_pk_no = isset($user_ch->team_lead_user_pk_no) ? $user_ch->team_lead_user_pk_no : 0;
                $lead_life->lead_hot_pk_no = isset($user_ch->hot_user_pk_no) ? $user_ch->hot_user_pk_no : 0;
                $lead_life->save();
            }
        }
        echo count($lead_sold_list);
    }
}
