<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LookupData;
use App\TeamAssign;
use App\TeamBDLand;
use App\TeamTarget;
use App\TeamTargetSalesA;
use App\YearSetup;
use DB;
use Illuminate\Http\Request;

class TargetSetupController extends Controller
{
    //
    public function target_sales_a()
    {
        $teams = LookupData::leftJoin('t_teambuild', 't_teambuild.team_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->where('lookup_type', 18)
            ->get();

        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.settings.target_sales_a.target_sales_a", compact('team_arr', 'target_year'));
    }
    public function load_team_member_by_team_for_sales_a(Request $request)
    {

        $team_id = $request->team_name;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('team_lookup_pk_no', $team_id)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();
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

        $target_data = TeamTargetSalesA::where('team_id', $request->team_name)
            ->where('target_yy', $request->target_year)
            ->where("target_mm", $request->team_target_month)
            ->where("finc_yy", $request->finc_year)
            ->get();

        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['target_mm'] = $tdata->target_month;
                $target_arr[$tdata->member_id]['target_ytd'] = $tdata->target_ytd;
                $target_arr[$tdata->member_id]['designation'] = $tdata->designation;

            }
        }

        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();

        return view('admin.settings.target_sales_a.teammember_list_by_team', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }
    public function store_target_sales_a(Request $request)
    {
        DB::table('target_sales_a_setup')->where('team_id', $request->team_name)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->team_target_month)
            ->where('finc_yy', $request->finc_year)
            ->delete();
        DB::table('t_teamtarget')->where('teammem_pk_no', $request->team_name)
            ->where('yy_mm', date("Y-m", strtotime($request->target_year . '-' . $request->team_target_month)))
            ->delete();

        $team_info = DB::table('t_teambuild')->where('team_lookup_pk_no', $request->team_name)->first();
        if (!empty($request->team_user)) {
            for ($i = 0; $i < count($request->team_user); $i++) {

                // $target = new TeamTarget();
                // $target->teammem_pk_no = $request->team_name;
                // $target->lead_pk_no = isset($team_info->team_lead_user_pk_no) ? $team_info->team_lead_user_pk_no : 0;
                // $target->user_pk_no = $request->team_user[$i];
                // $target->category_lookup_pk_no = $request->category_id[$i];
                // $target->area_lookup_pk_no = $request->area_id[$i];
                // $target->yy_mm = date("Y-m", strtotime($request->target_year . '-' . $request->team_target_month));
                // $target->target_amount = $request->target[$i];
                // $target->created_by = 1;
                // $target->created_at = date("Y-m-d");

                // $target->save();

                $team_target = new TeamTargetSalesA();
                $team_target->team_id = $request->team_name;
                $team_target->target_yy = $request->target_year;
                $team_target->target_mm = $request->team_target_month;
                $team_target->finc_yy = $request->finc_year;

                $team_target->member_id = $request->team_user[$i];
                $team_target->category_id = $request->category_id[$i];
                $team_target->target_month = $request->target[$i];
                $team_target->target_ytd = $request->ytd_target[$i];
                $team_target->designation = isset($request->desgination[$i])?$request->desgination[$i] : '';
                $team_target->save();

            }
        }

        return response()->json(['message' => 'Report Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

    }
    public function target_bd_land()
    {
        $teams = LookupData::leftJoin('t_teambuild', 't_teambuild.team_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->where('lookup_type', 18)
            ->get();

        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.settings.target_bd_land.target_bd_land", compact('team_arr', 'target_year'));
    }
    public function load_team_member_by_bd_land(Request $request)
    {

        $team_id = $request->team_name;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('team_lookup_pk_no', $team_id)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();
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

        $target_data = TeamBDLand::where('team_id', $request->team_name)
            ->where('target_yy', $request->target_year)
            ->where("target_mm", $request->team_target_month)
            ->where("finc_yy", $request->finc_year)
            ->get();

        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['target_mm'] = $tdata->target_month;
                $target_arr[$tdata->member_id]['target_ytd'] = $tdata->target_ytd;

            }
        }

        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();

        return view('admin.settings.target_sales_a.teammember_list_by_team', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }
    public function store_bd_land(Request $request)
    {
        DB::table('target_bd_land')->where('team_id', $request->team_name)
            ->where('target_yy', $request->target_year)
            ->where('target_mm', $request->team_target_month)
            ->where('finc_yy', $request->finc_year)
            ->delete();
        if (!empty($request->team_user)) {
            for ($i = 0; $i < count($request->team_user); $i++) {
                $team_target = new TeamBDLand();
                $team_target->team_id = $request->team_name;
                $team_target->target_yy = $request->target_year;
                $team_target->target_mm = $request->team_target_month;
                $team_target->finc_yy = $request->finc_year;

                $team_target->member_id = $request->team_user[$i];
                $team_target->category_id = $request->category_id[$i];
                $team_target->target_month = $request->target[$i];
                $team_target->target_ytd = $request->ytd_target[$i];
                $team_target->save();
            }
        }

        return response()->json(['message' => 'Report Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }
    public function target_hot_performance()
    {
        $teams = LookupData::leftJoin('t_teambuild', 't_teambuild.team_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->where('lookup_type', 18)
            ->get();

        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.settings.sales_hot_performance.sales_hot_performance", compact('team_arr', 'target_year'));
    }
    public function load_hot(Request $request)
    {
        $team_id = $request->team_name;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('team_lead_flag', '=', 1)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('agent_type', 2)
            ->get();

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

        $target_data = TeamTargetSalesA::where('target_yy', $request->target_year)
            ->where("target_mm", $request->team_target_month)
            ->where("finc_yy", $request->finc_year)
            ->get();

        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['target_mm'] = $tdata->target_month;
                $target_arr[$tdata->member_id]['target_ytd'] = $tdata->target_ytd;

            }
        }

        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();

        return view('admin.settings.sales_hot_performance.load_hot', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }
    public function target_chs_performance()
    {
        $teams = LookupData::leftJoin('t_teambuild', 't_teambuild.team_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->where('lookup_type', 18)
            ->get();

        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }
        $target_year = YearSetup::orderby("id", "desc")->get();
        return view("admin.settings.sales_hot_performance.target_chs_performance", compact('team_arr', 'target_year'));
    }
    public function load_chs(Request $request)
    {
        $team_id = $request->team_name;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('hod_flag', '=', 1)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('lead_type', 1)
            ->groupBy('s_user.user_pk_no')
            ->get();

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

        $target_data = TeamTargetSalesA::where('target_yy', $request->target_year)
            ->where("target_mm", $request->team_target_month)
            ->where("finc_yy", $request->finc_year)
            ->get();

        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['target_mm'] = $tdata->target_month;
                $target_arr[$tdata->member_id]['target_ytd'] = $tdata->target_ytd;

            }
        }

        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();

        return view('admin.settings.sales_hot_performance.load_hot', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }

}
