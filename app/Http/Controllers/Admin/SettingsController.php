<?php

namespace App\Http\Controllers\Admin;

use App\BrokarageSalesReportSetup;
use App\FlatSetup;
use App\Http\Controllers\Controller;
use App\InventoryTargetDetails;
use App\LookupData;
use App\ReportSetup;
use App\TeamAssign;
use App\YearSetup;
use Carbon\carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SettingsController extends Controller
{
    public function index()
    {

        $lookup_type = config('static_arrays.lookup_array');
        $lookup_data = LookupData::where("lookup_type", ">", 0)->get();
        return view('admin.settings.lookup.index', compact('lookup_data', 'lookup_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lookup_type = config('static_arrays.lookup_array');
        return view('admin.settings.lookup.create', compact('lookup_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create_date = date('Y-m-d');
        $order_execute = DB::statement(
            DB::raw("CALL proc_lookdata_ins ( $request->cmbLookupType,1,'$request->txtLookupName',$request->cmbLookupStatus,1,1,'$create_date' )")
        );

        return response()->json(['message' => 'Lookup Data created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lookup_type = config('static_arrays.lookup_array');
        $where = array('lookup_pk_no' => $id);
        $lookup_data = LookupData::where($where)->first();

        return view('admin.settings.lookup.create', compact('lookup_data', 'lookup_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ldata = LookupData::findOrFail($id);
        $ldata->lookup_name = $request->txtLookupName;
        $ldata->lookup_type = $request->cmbLookupType;
        $ldata->lookup_row_status = $request->cmbLookupStatus;

        if ($ldata->save()) {
            return response()->json(['message' => 'Lookup Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Lookup Data update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_project_wise_flat(Request $request)
    {
        $ids = $request->ids;
        DB::table("s_projectwiseflatlist")->whereIn('flatlist_pk_no',explode(",",$ids))->delete();
        return response()->json(['success'=>"Products Deleted successfully."]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_list(Request $request)
    {
        $lookup_type = config('static_arrays.lookup_array');
        $lookup_data = LookupData::all();
        return view('admin.settings.lookup.lookup_list', compact('lookup_data', 'lookup_type'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function project_wise_flat()
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 6) {
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 7) {
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }
        $lead_type = config("static_arrays.lead_type");
        $group_id = Session::get('user.ses_role_lookup_pk_no');
        $ses_lead_type = Session::get('user.ses_lead_type');
        $ses_super_admin = Session::get('user.is_super_admin');
        if ($ses_super_admin == 1 || $group_id == '440') {
            $flat_list = FlatSetup::orderBy('flatlist_pk_no', 'desc')->get();
        } else {
            $flat_list = FlatSetup::where('lead_type', $ses_lead_type)->orderBy('flatlist_pk_no', 'desc')->get();
        }
        return view('admin.settings.project_wise_flat_list', compact('lead_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'flat_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_project_wise_flat()
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->orderBy('lookup_name')->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 6) {
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 7) {
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }
        $lead_type = config("static_arrays.lead_type");

        return view('admin.settings.flat_setup.flat_setup_form', compact('project_cat', 'project_area', 'project_name', 'project_size', 'lead_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_flat_setup(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'area' => 'required',
            'project_name' => 'required',
            'flat_size' => 'required',
        ]);

        $user_id = 1; //Session::get('user.ses_user_pk_no');

        $fsetup = new FlatSetup();
        $fsetup->category_lookup_pk_no = $request->category;

        $fsetup->lead_type = $request->lead_type;

        $fsetup->area_lookup_pk_no = $request->area;
        $fsetup->project_lookup_pk_no = $request->project_name;
        $fsetup->size_lookup_pk_no = $request->flat_size;
        $fsetup->flat_name = $request->flat_name;
        $fsetup->flat_description = $request->flat_description;
        $fsetup->flat_cost = $request->flat_cost;
        $fsetup->selling_period = $request->selling_period;
        $target_closing_month = Carbon::now()->addMonths($request->selling_period);
        $fsetup->target_starting_month = date("Y-m-d");
        $fsetup->target_closing_month = date("Y-m-d", strtotime($target_closing_month));

        $fsetup->bed_count = $request->bed_count;
        $fsetup->rent_value = $request->rent_value;
        $fsetup->c_service_charge = $request->c_service_charge;

        $fsetup->flat_status = 0;
        $fsetup->created_by = $user_id;
        $fsetup->created_at = date("Y-m-d");

        if ($fsetup->save()) {
            $inventory = new InventoryTargetDetails();
            $inventory->target_month = date('m');
            $inventory->target_year = date("Y");
            $inventory->inventory_id = $fsetup->flatlist_pk_no;
            $inventory->month_target = $request->flat_cost;
            $inventory->ytd_target = $request->flat_cost;
            $inventory->remaining_peroid = $request->selling_period;
            $inventory->save();
            return response()->json(['message' => 'Data Saved Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Data Save Failed.', 'title' => 'Failed', 'positionClass' => 'toast-top-right']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_project_wise_flat($id)
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 6) {
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 7) {
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }
        $flat_data = FlatSetup::find($id);
        $lead_type = config("static_arrays.lead_type");
        return view('admin.settings.flat_setup.flat_setup_form', compact('lead_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'flat_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_flat_setup(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
            'area' => 'required',
            'project_name' => 'required',
            'flat_size' => 'required',
            'flat_name' => 'required',
        ]);

        $user_id = 1; //Session::get('user.ses_user_pk_no');

        $fsetup = FlatSetup::findOrFail($request->hdnFlatSetupId);
        if ($request->lead_type == 1) {
            $fsetup->is_quantity = 1;
        } else {
            $fsetup->is_quantity = 0;
        }
        $fsetup->category_lookup_pk_no = $request->category;
        $fsetup->lead_type = $request->lead_type;
        $fsetup->area_lookup_pk_no = $request->area;
        $fsetup->project_lookup_pk_no = $request->project_name;
        $fsetup->size_lookup_pk_no = $request->flat_size;
        $fsetup->flat_name = $request->flat_name;
        $fsetup->flat_description = $request->flat_description;
        $fsetup->flat_cost = $request->flat_cost;
        $fsetup->selling_period = $request->selling_period;
        $target_closing_month = Carbon::now()->addMonths($request->selling_period);
        $fsetup->target_starting_month = date("Y-m-d");
        $fsetup->target_closing_month = date("Y-m-d", strtotime($target_closing_month));
        //$fsetup->flat_status = 0;

        $fsetup->bed_count = $request->bed_count;
        $fsetup->rent_value = $request->rent_value;
        $fsetup->c_service_charge = $request->c_service_charge;

        $fsetup->created_by = $user_id;
        $fsetup->created_at = date("Y-m-d");

        if ($fsetup->save()) {
            $inventory = new InventoryTargetDetails();
            $inventory->target_month = date('m');
            $inventory->target_year = date("Y");
            $inventory->inventory_id = $fsetup->flatlist_pk_no;
            $inventory->month_target = $request->flat_cost;
            $inventory->ytd_target = $request->flat_cost;
            $inventory->remaining_peroid = $request->selling_period;
            $inventory->save();
            return response()->json(['message' => 'Data Updated Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Data Update Failed.', 'title' => 'Failed', 'positionClass' => 'toast-top-right']);
        }
    }
    public function getFlatListByType(Request $request)
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 6) {
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 7) {
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }

        $flat_list = FlatSetup::where('is_quantity', $request->value)->get();

        return view('admin.settings.project_wise_flat_list_table', compact('project_cat', 'project_area', 'project_name', 'project_size', 'flat_list'));
    }
    public function year_to_year_setup()
    {
        $month_arr = config("static_arrays.months_arr");
        $year_setup_list = YearSetup::orderBy("id", "desc")->get();
        return view("admin.settings.year_to_year_setup.index", compact('year_setup_list', 'month_arr'));
    }
    public function create_year_to_year_setup()
    {
        return view("admin.settings.year_to_year_setup.create_year_to_year_setup");
    }
    public function report_setup()
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
        return view("admin.settings.report_setup.team_target", compact('team_arr', 'target_year'));
    }
    public function load_team_list_by_team(Request $request)
    {
        $team_id = $request->team_id;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('team_lookup_pk_no', $team_id)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')->get();
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

        $target_data = ReportSetup::where('team_id', $team_id)->where('target_year_id', $team_target_date)->get();
        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['date_of_join'] = $tdata->date_of_join;
                $target_arr[$tdata->member_id]['date_of_report'] = $tdata->date_of_report;
                $target_arr[$tdata->member_id]['lead_type'] = $tdata->lead_type;
                if ($tdata->e_status != "0") {
                    $sales_agent_arr[$tdata->member_id] = $tdata->e_status;
                }
            }
        }
        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = DB::table('s_user')->where('user_type', 2)->get(); //TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')->get();

        return view('admin.settings.report_setup.teammember_list_by_team', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }

    public function store_year_setup(Request $request)
    {
        $year_setup = new YearSetup();
        $year_setup->starting_year = $request->s_year_name;
        $year_setup->starting_month = $request->s_year_month;
        $year_setup->closing_year = $request->c_year_name;
        $year_setup->closing_month = $request->c_year_month;

        $year_setup->fin_year = $request->s_year_name . '-' . $request->c_year_name;
        $year_setup->fin_year_month = $request->s_year_month . '-' . $request->c_year_month;
        $year_setup->save();
        return response()->json(['message' => 'Year Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }
    public function edit_year_setup($id)
    {
        $year_setup = YearSetup::find($id);
        return view("admin.settings.year_to_year_setup.create_year_to_year_setup", compact("year_setup"));
    }
    public function update_year_setup(Request $request)
    {
        $year_setup = YearSetup::find($request->hdnYearSetup);
        $year_setup->starting_year = $request->s_year_name;
        $year_setup->starting_month = $request->s_year_month;
        $year_setup->closing_year = $request->c_year_name;
        $year_setup->closing_month = $request->c_year_month;

        $year_setup->fin_year = $request->s_year_name . '-' . $request->c_year_name;
        $year_setup->fin_year_month = $request->s_year_month . '-' . $request->c_year_month;
        $year_setup->save();
        return response()->json(['message' => 'Year Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }
    public function load_ch_by_team(Request $request)
    {
        $team_id = $request->team_id;
        $team_arr = [];
        $team_mem = TeamAssign::where('team_lookup_pk_no', $team_id)
            ->leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', 1)
            ->get();
        if (!empty($team_mem)) {
            foreach ($team_mem as $member) {
                $team_arr[$member->user_pk_no] = $member->user_fullname;
            }
        }
        return view('admin.components.ch_dropdown', compact('team_arr'));
    }
    public function store_report_target(Request $request)
    {
        //dd($request->all());
        DB::table('report_setup')->where('team_id', $request->team_name)->where('target_year_id', $request->team_target_date)->delete();
        if (!empty($request->team_user)) {
            for ($i = 0; $i < count($request->team_user); $i++) {
                $report_setup = new ReportSetup();
                $report_setup->team_id = $request->team_name;
                $report_setup->target_year_id = $request->team_target_date;
                $report_setup->member_id = $request->team_user[$i];
                $report_setup->category_id = $request->category_id[$i];
                $report_setup->date_of_join = $request->date_of_join[$i];
                $report_setup->date_of_report = $request->date_of_resign[$i];
                $report_setup->prev_position = $request->prev_position[$i];
                $report_setup->new_position = $request->new_position[$i];
                $report_setup->e_status = 0;
                $report_setup->created_at = date('Y-m-d');
                //$report_setup->lead_type = $request->lead_type[$i];
                $report_setup->save();
            }
        }
        if (!empty($request->sales_agent)) {
            for ($i = 0; $i < count($request->sales_agent); $i++) {
                if ($request->sales_agent[$i] != 0) {
                    $user_info = TeamAssign::where('user_pk_no', $request->sales_agent[$i])->first();

                    $report_setup = new ReportSetup();
                    $report_setup->team_id = $request->team_name;
                    $report_setup->target_year_id = $request->team_target_date;
                    $report_setup->member_id = $request->sales_agent[$i];
                    $report_setup->category_id = $request->category_id[0];
                    $report_setup->e_status = $request->emp_status[$i];
                    $report_setup->prev_position = $request->prev_position[$i];
                    $report_setup->new_position = $request->new_position[$i];
                    $report_setup->created_at =  date('Y-m-d');
                    $report_setup->lead_type = isset($user_info->lead_type) ? $user_info->lead_type : 0;
                    $report_setup->save();
                }
            }
        }

        return response()->json(['message' => 'Report Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    public function brokarage_report_setup()
    {
        $target_year = YearSetup::orderby("id", "desc")->get();
        $cluster_head = DB::table("t_teambuild")
            ->join("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
            ->select("s_user.user_pk_no", "s_user.user_fullname")
            ->groupBy("s_user.user_pk_no", "s_user.user_fullname")
            ->get();
        $month_arr = config("static_arrays.months_arr");

        return view("admin.settings.brokarage_sales_report.team_target", compact('month_arr', 'target_year', 'cluster_head'));
    }
    public function load_team_list_by_ch(Request $request)
    {
        $cluster_id = $request->cluster_id;
        $team_target_date = $request->team_target_date;
        $team_member = TeamAssign::where('hod_user_pk_no', $cluster_id)
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

        //$target_data = ReportSetup::where('team_id', $team_id)->where('target_year_id', $team_target_date)->get();
        $target_arr = [];
        $sales_agent_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->member_id]['date_of_join'] = $tdata->date_of_join;
                $target_arr[$tdata->member_id]['date_of_report'] = $tdata->date_of_report;
                $target_arr[$tdata->member_id]['lead_type'] = $tdata->lead_type;
                if ($tdata->e_status != "0") {
                    $sales_agent_arr[$tdata->member_id] = $tdata->e_status;
                }
            }
        }
        $lead_type = config('static_arrays.lead_type');
        // dd( $sales_agent_arr,$target_data);
        $sales_agent = TeamAssign::leftJoin('s_user', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('hod_flag', '=', 0)
            ->where('hot_flag', '=', 0)
            ->where('team_lead_flag', '=', 0)->get();

        return view('admin.settings.brokarage_sales_report.teammember_list_by_ch', compact('sales_agent_arr', 'sales_agent', 'lead_type', 'team_member', 'project_cat', 'project_area', 'target_arr'));
    }
    public function store_brokarage_setup(Request $request)
    {

        if (!empty($request->team_user)) {
            for ($i = 0; $i < count($request->team_user); $i++) {
                $brokarage_setup = new BrokarageSalesReportSetup();
                $brokarage_setup->cluster_id = $request->cluster_head;
                $brokarage_setup->finc_yr_id_fk = $request->team_target_date;
                $brokarage_setup->member_id = $request->team_user[$i];
                $brokarage_setup->category_id = $request->category_id[$i];
                $brokarage_setup->date_of_join = date("Y-m-d", strtotime($request->date_of_join[$i]));
                $brokarage_setup->date_of_report = date("Y-m-d", strtotime($request->date_of_resign[$i]));
                $brokarage_setup->target_value = $request->target_value[$i];
                $brokarage_setup->lead_type = $request->lead_type[$i];
                $brokarage_setup->target_yy = $request->target_yy;
                $brokarage_setup->target_mm = $request->target_mm;
                $brokarage_setup->save();
            }
        }
        return response()->json(['message' => 'Brokarage Setup done successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }
    public function update_selling_period()
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 4) {
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 5) {
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 6) {
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }

                if ($ldata->lookup_type == 7) {
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
                }
            }
        }
        $lead_type = config("static_arrays.lead_type");

        return view('admin.settings.flat_setup.update_selling_period', compact('project_cat', 'project_area', 'project_name', 'project_size', 'lead_type'));
    }
    public function store_update_selling_period(Request $request)
    {
        $project = FlatSetup::where('project_lookup_pk_no', $request->project_name)->get();
        // dd( $project , $request->all());
        if (!empty($project)) {
            foreach ($project as $row) {
                $data = FlatSetup::find($row->flatlist_pk_no);
                $data->selling_period = $request->selling_period;
                //$target_closing_month = Carbon::now()->addMonths($request->selling_period);
                $target_closing_month = Carbon::now()->addMonths($request->selling_period);
                $data->target_starting_month = date("Y-m-d");
                $data->target_closing_month = date("Y-m-d", strtotime($target_closing_month));
                $data->save();
            }
        }
        return response()->json(['message' => 'Selling Period updated.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }
}
