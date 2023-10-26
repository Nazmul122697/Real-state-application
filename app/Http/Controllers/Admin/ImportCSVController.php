<?php

namespace App\Http\Controllers\Admin;

use App\FlatSetup;
use App\Http\Controllers\Controller;
use App\InventoryTargetDetails;
use App\LookupData;
use App\ReportSetup;
use App\TeamAssign;
use App\TeamTarget;
use App\TeamTargetSalesA;
use App\TeamUser;
use App\YearSetup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
class ImportCSVController extends Controller
{
    //
    public function import_csv_report_setup()
    {
        return view("admin.import_csv.import_csv_for_report_setup");
    }
    public function store_import_csv_report_setup(Request $request)
    {
        $lookup_data = LookupData::all();
        $cate_data_arr = [];
        $project_data_arr = [];
        $area_data_arr = [];
        $size_data_arr = [];
        $lookup_data_arr = [];
        $team_data_arr = [];
        foreach ($lookup_data as $value) {
            if ($value->lookup_type == 4) {
                $cate_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 6) {
                $project_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 5) {
                $area_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 7) {
                $size_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 18) {
                $team_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }

        }
        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        $target_year_arr = [];
        $target_year = YearSetup::orderby("id", "desc")->get();
        if (!empty($target_year)) {
            foreach ($target_year as $row) {
                $target_year_arr[$row->fin_year] = $row->id;
            }
        }
        //dd($leadArr);
        for ($i = 0; $i < count($leadArr); $i++) {
            $team_name = isset($team_data_arr[trim($leadArr[$i]["team_name"])]) ? $team_data_arr[trim($leadArr[$i]["team_name"])] : '0';
            $team_finc_target_year = isset($target_year_arr[trim($leadArr[$i]["team_finc_target_year"])]) ? $target_year_arr[trim($leadArr[$i]["team_finc_target_year"])] : '';

            DB::table('report_setup')->where('team_id', $team_name)
                ->where('target_year_id', $team_finc_target_year)->delete();
        }

        for ($i = 0; $i < count($leadArr); $i++) {
            $team_name = isset($team_data_arr[trim($leadArr[$i]["team_name"])]) ? $team_data_arr[trim($leadArr[$i]["team_name"])] : '0';
            $team_finc_target_year = isset($target_year_arr[trim($leadArr[$i]["team_finc_target_year"])]) ? $target_year_arr[trim($leadArr[$i]["team_finc_target_year"])] : '';
            $team_user = isset($user_data_arr[trim($leadArr[$i]["team_user"])]) ? $user_data_arr[trim($leadArr[$i]["team_user"])] : "";
            $category_id = isset($cate_data_arr[trim($leadArr[$i]["category_id"])]) ? $cate_data_arr[trim($leadArr[$i]["category_id"])] : '';
            $date_of_join = date("Y-m-d", strtotime(trim($leadArr[$i]["date_of_join"])));
            $date_of_report = date("Y-m-d", strtotime(trim($leadArr[$i]["date_of_report"])));
            $e_status = trim($leadArr[$i]["e_status"]);
            if (!empty($e_status)) {
                $report_setup = new ReportSetup();
                $report_setup->team_id = $team_name;
                $report_setup->target_year_id = $team_finc_target_year;
                $report_setup->member_id = $team_user;
                $report_setup->category_id = $category_id;
                $report_setup->e_status = $e_status;
                //$report_setup->lead_type = $request->lead_type[$i];
                $report_setup->save();
            } else {
                $report_setup = new ReportSetup();
                $report_setup->team_id = $team_name;
                $report_setup->target_year_id = $team_finc_target_year;
                $report_setup->member_id = $team_user;
                $report_setup->category_id = $category_id;
                $report_setup->date_of_join = $date_of_join;
                $report_setup->date_of_report = $date_of_report;
                $report_setup->e_status = 0;
                //$report_setup->lead_type = $request->lead_type[$i];
                $report_setup->save();
            }

        }

        return redirect()->back()->with('success', 'File Uploaded Succesfully!');
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
    public function import_csv_target_setup()
    {
        return view("admin.import_csv.import_csv_target_setup");
    }
    public function store_import_csv_target_setup(Request $request)
    {
        $lookup_data = LookupData::all();
        $cate_data_arr = [];
        $project_data_arr = [];
        $area_data_arr = [];
        $size_data_arr = [];
        $lookup_data_arr = [];
        $team_data_arr = [];
        foreach ($lookup_data as $value) {
            if ($value->lookup_type == 4) {
                $cate_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 6) {
                $project_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 5) {
                $area_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 7) {
                $size_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 18) {
                $team_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }

        }

        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        $target_year_arr = [];
        $target_year = YearSetup::orderby("id", "desc")->get();
        if (!empty($target_year)) {
            foreach ($target_year as $row) {
                $target_year_arr[$row->fin_year] = $row->id;
            }
        }
        //dd($leadArr);
        for ($i = 0; $i < count($leadArr); $i++) {
            $team_name = isset($team_data_arr[trim($leadArr[$i]["team_name"])]) ? $team_data_arr[trim($leadArr[$i]["team_name"])] : '0';
            $team_finc_target_year = isset($target_year_arr[trim($leadArr[$i]["team_finc_target_year"])]) ? $target_year_arr[trim($leadArr[$i]["team_finc_target_year"])] : '';
            $team_user = isset($user_data_arr[trim($leadArr[$i]["team_user"])]) ? $user_data_arr[trim($leadArr[$i]["team_user"])] : "";
            $category_id = isset($cate_data_arr[trim($leadArr[$i]["category_id"])]) ? $cate_data_arr[trim($leadArr[$i]["category_id"])] : '';
            $target_yy = date("Y", strtotime(trim($leadArr[$i]["target_yy"])));
            $target_mm = (int) date("m", strtotime(trim($leadArr[$i]["target_mm"])));
            $target_month = trim($leadArr[$i]["target_month"]);
            $target_ytd = trim($leadArr[$i]["target_ytd"]);
            $designation = trim($leadArr[$i]["designation"]);
            DB::table('target_sales_a_setup')->where('team_id', $team_name)
                ->where('target_yy', $target_yy)
                ->where('target_mm', $target_mm)
                ->where('finc_yy', $team_finc_target_year)
                ->delete();
            DB::table('t_teamtarget')->where('teammem_pk_no', $team_name)
                ->where('yy_mm', date("Y-m"))
                ->delete();
        }
        for ($i = 0; $i < count($leadArr); $i++) {
            $team_name = isset($team_data_arr[trim($leadArr[$i]["team_name"])]) ? $team_data_arr[trim($leadArr[$i]["team_name"])] : '0';
            $team_finc_target_year = isset($target_year_arr[trim($leadArr[$i]["team_finc_target_year"])]) ? $target_year_arr[trim($leadArr[$i]["team_finc_target_year"])] : '';
            $team_user = isset($user_data_arr[trim($leadArr[$i]["team_user"])]) ? $user_data_arr[trim($leadArr[$i]["team_user"])] : "";
            $category_id = isset($cate_data_arr[trim($leadArr[$i]["category_id"])]) ? $cate_data_arr[trim($leadArr[$i]["category_id"])] : '';
            $target_yy = trim($leadArr[$i]["target_yy"]);
            $target_mm = (int) date("m", strtotime(trim($leadArr[$i]["target_mm"])));
            $target_month = trim($leadArr[$i]["target_month"]);
            $target_ytd = trim($leadArr[$i]["target_ytd"]);
            $designation = trim($leadArr[$i]["designation"]);
           // dd( $target_yy,$leadArr[$i]["target_yy"],strtotime(trim($leadArr[$i]["target_yy"])));
            $team_target = new TeamTargetSalesA();
            $team_target->team_id = $team_name;
            $team_target->target_yy = $target_yy;
            $team_target->target_mm = $target_mm;
            $team_target->finc_yy = $team_finc_target_year;

            $team_target->member_id = $team_user;
            $team_target->category_id = $category_id;
            $team_target->designation = $designation;
            $team_target->target_month = $target_month;
            $team_target->target_ytd = $target_ytd;
            $team_target->save();

            // $team_details = TeamAssign::where('team_lookup_pk_no', $team_name)->first();
            // $target = new TeamTarget();
            // $target->teammem_pk_no = $team_name;
            // $target->lead_pk_no = isset($team_details->team_lead_user_pk_no) ? $team_details->team_lead_user_pk_no : '0';
            // $target->user_pk_no = $team_user;
            // $target->category_lookup_pk_no = $category_id;
            // $target->area_lookup_pk_no = isset($team_details->area_lookup_pk_no) ? $team_details->area_lookup_pk_no : '0';
            // $target->yy_mm = date("Y-m");
            // $target->target_amount = $target_month;
            // $target->target_by_lead_qty = 0;
            // $target->created_by = 1;
            // $target->created_at = date("Y-m-d");
            // $target->save();

        }

        return redirect()->back()->with('success', 'File Uploaded Succesfully!');
    }
    public function import_csv_flat_setup()
    {
        return view("admin.import_csv.import_csv_flat_setup");
    }
    public function store_import_csv_flat_setup(Request $request)
    {
        $lookup_data = LookupData::all();
        $cate_data_arr = [];
        $project_data_arr = [];
        $area_data_arr = [];
        $size_data_arr = [];
        $lookup_data_arr = [];
        foreach ($lookup_data as $value) {
            if ($value->lookup_type == 4) {
                $cate_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 6) {
                $project_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 5) {
                $area_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }
            if ($value->lookup_type == 7) {
                $size_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            }

        }
        //dd($cate_data_arr);
        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        $target_year_arr = [];
        $target_year = YearSetup::orderby("id", "desc")->get();
        if (!empty($target_year)) {
            foreach ($target_year as $row) {
                $target_year_arr[$row->fin_year] = $row->id;
            }
        }
        $lead_type_arr = config("static_arrays.lead_type");
        $lead_type_data = [];
        foreach ($lead_type_arr as $key => $value) {
            $lead_type_data[$value] = $key;
        }
        //dd($leadArr);
        for ($i = 0; $i < count($leadArr); $i++) {
            $category_id = isset($cate_data_arr[trim($leadArr[$i]["category"])]) && !empty($cate_data_arr[trim($leadArr[$i]["category"])]) ? $cate_data_arr[trim($leadArr[$i]["category"])] : '';
            $area = isset($area_data_arr[trim($leadArr[$i]["area"])]) && !empty($area_data_arr[trim($leadArr[$i]["area"])]) ? $area_data_arr[trim($leadArr[$i]["area"])] : '';
            $project_name = isset($project_data_arr[trim($leadArr[$i]["project_name"])]) && !empty($project_data_arr[trim($leadArr[$i]["project_name"])]) ? $project_data_arr[trim($leadArr[$i]["project_name"])] : '';

            $flat_size = isset($size_data_arr[trim($leadArr[$i]["flat_size"])]) && !empty($size_data_arr[trim($leadArr[$i]["flat_size"])]) ? $size_data_arr[trim($leadArr[$i]["flat_size"])] : '';
            $lead_type = isset($lead_type_data[trim($leadArr[$i]["lead_type"])]) && !empty($lead_type_data[trim($leadArr[$i]["lead_type"])]) ? $lead_type_data[trim($leadArr[$i]["lead_type"])] : '';

            $flat_name = trim($leadArr[$i]["flat_name"]);
            $flat_description = trim($leadArr[$i]["flat_description"]);
            $flat_cost = trim($leadArr[$i]["flat_cost"]);
            $selling_period = trim($leadArr[$i]["selling_period"]);
            $bed_count = trim($leadArr[$i]["bed_count"]);
            $rent_value = trim($leadArr[$i]["rent_value"]);
            $c_service_charge = trim($leadArr[$i]["c_service_charge"]);

            $fsetup = new FlatSetup();
            $fsetup->category_lookup_pk_no = $category_id;
            $fsetup->lead_type = $lead_type;
            $fsetup->area_lookup_pk_no = $area;
            $fsetup->project_lookup_pk_no = $project_name;
            $fsetup->size_lookup_pk_no = $flat_size;
            $fsetup->flat_name = $flat_name;
            $fsetup->flat_description = $flat_description;
            $fsetup->flat_cost = $flat_cost;
            $fsetup->selling_period = $selling_period;
            $target_closing_month = Carbon::now()->addMonths($selling_period);
            $fsetup->target_starting_month = date("M/d/Y");
            $fsetup->target_closing_month = date("M/d/Y", strtotime($target_closing_month));
            $fsetup->flat_status = 0;

            $fsetup->bed_count = $bed_count;
            $fsetup->rent_value = $rent_value;
            $fsetup->c_service_charge = $c_service_charge;

            $fsetup->created_by = Auth::id();
            $fsetup->created_at = date("Y-m-d");
            $fsetup->save();

            $inventory = new InventoryTargetDetails();
            $inventory->target_month = date('m');
            $inventory->target_year = date("Y");
            $inventory->inventory_id = $fsetup->flatlist_pk_no;
            $inventory->month_target = $flat_cost;
            $inventory->ytd_target = $flat_cost;
            $inventory->remaining_peroid = $selling_period;
            $inventory->save();

        }

        return redirect()->back()->with('success', 'File Uploaded Succesfully!');
    }

}
