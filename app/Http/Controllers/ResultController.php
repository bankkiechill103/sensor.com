<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Maatwebsite\Excel\Facades\Excel;
use Hash;

class ResultController extends Controller
{
    public function getMap(Request $request)
    {
      $allDatas = $this->getData_all_parametor($request->id);
      $lasttime = DateThai($allDatas["sound_1hr"]->f1);
      $allDatas["lasttime"] = $lasttime;
      $getnameid = getnameid();
      $allDatas["name"] = $getnameid[$request->id];
      return json_encode($allDatas);
    }
    public function export(Request $request)
    {
        $getnameid = getnameid();
        $getnamemain = getnamemain();
        if($request->type == 0){
          $name = $getnameid[$request->id]."_".$request->start_date."_".$request->end_date."_ทั้งหมด.xlsx";
        }else{
          $name = $getnameid[$request->id]."_".$request->start_date."_".$request->end_date."_".$getnamemain[$request->type].".xlsx";
        }
        return Excel::download(new UsersExport($request), $name);
    }
    public function getDataG(Request $request)
    {
      $getnamemain = getnamemain();
      $getnameid = getnameid();
      $allData = $this->getData($request);
      $data_count = $allData->count();
      $col3 = [1,4,9,10,11,12];
      $col5 = [5,6,7];
      if($data_count > 0){
        if(in_array($request->type, $col3)){
          if($request->type == 9 || $request->type == 11){
            $limit = 50;
            if($request->type == 11){
              $type = 1;
            }else{
              $type = 3;
            }
          }else if($request->type == 10 || $request->type == 12){
            $limit = 120;
            $type = 1;
          }else if($request->type == 4){
            $limit = 80;
            $type = 10;
          }else{
            $limit = 80;
            $type = 99;
          }
          if($request->type == 10 || $request->type == 9){
            $type = 98;
          }
          if($request->type == 11 || $request->type == 12){
            $type = 97;
          }
          $graft = $this->formatGraft1($allData, $limit, $type, $request->type);
          $graft["title"] = $getnameid[$request->id]." ".$getnamemain[$request->type];
        }elseif($request->type == 2){
          $graft = $this->formatGraft2($allData, 70);
          $graft["title"] = $getnameid[$request->id]." ".$getnamemain[$request->type];
        }elseif(in_array($request->type, $col5)){
          $graft = $this->formatGraft4($allData, 5, 98, $request->type);
          $graft["title"] = $getnameid[$request->id]." ".$getnamemain[$request->type];
        }elseif($request->type == 8){
          $graft = $this->formatGraft5($allData, 5, 0, $request->type);
          $graft["title1"] = $getnameid[$request->id]." สั่นสะเทือนแกน X มากกว่าค่ามาตรฐาน";
          $graft["title2"] = $getnameid[$request->id]." สั่นสะเทือนแกน Y มากกว่าค่ามาตรฐาน";
          $graft["title3"] = $getnameid[$request->id]." สั่นสะเทือนแกน Z มากกว่าค่ามาตรฐาน";
        }elseif($request->type == 3){
          $graft = $this->formatGraft3($allData, 70);
          $graft["title"] = $getnameid[$request->id]." ".$getnamemain[$request->type];
        }
        $graft["status"] = 1;
      }else{
        $graft =["status" => 0];
      }
      return json_encode($graft);
    }
    public function getDataT(Request $request)
    {
      $allData = $this->getData($request);
      $data_count = $allData->count();
      $col3 = [1,4,9,10,11,12];
      if($data_count > 0){
        $data_useTable = $allData->skip($request->start)->take($request->length);
        if(in_array($request->type, $col3)){
          if($request->type == 1 || $request->type == 4){
            $data_use = $this->formatTable1($data_useTable);
          }else{
            $data_use = $this->formatTable1($data_useTable, 3);
          }
        }elseif($request->type == 2){
          $data_use = $this->formatTable2($data_useTable);
        }elseif($request->type == 3){
          $data_use = $this->formatTable3($data_useTable);
        }elseif($request->type == 5 || $request->type == 6 || $request->type == 7){
          $data_use = $this->formatTable4($data_useTable);
        }elseif($request->type == 8){
          $data_use = $this->formatTable5($data_useTable);
        }
        $data_format = ["draw" => $request->draw, "recordsTotal" => $data_count, "recordsFiltered" => $data_count, "data" => $data_use];
      }else{
        $data_format =["status" => 0];
      }
      return json_encode($data_format);
    }
    public function getData_all_parametor($id)
    {
      $start_date = date("Y-m-d");
      $end_date = date("Y-m-d");
      $fsort = "f1";
      $sortBy = "desc";
      if($id == 1){
        $tablename = "ce_sound_actual";
        $tablename1 = "ce_vibrationconverted";
        $tablename2 = "ce_vibration_total";
        $tablename3 = "view005";
      }elseif($id == 2){
        $tablename = "nailert_sound";
        $tablename1 = "nailert_vibrationconverted";
        $tablename2 = "nailert_vibration_total";
        $tablename3 = "view006";
      }elseif($id == 3){
        $tablename = "newhouse_sound_actual";
        $tablename1 = "newhouse_vibrationconverted";
        $tablename2 = "newhouse_vibration_total";
        $tablename3 = "view007";
      }elseif($id == 4){
        $tablename = "sivatel_sound";
        $tablename1 = "sivatel_vibrationconverted";
        $tablename2 = "sivatel_vibration_total";
        $tablename3 = "view004";
      }elseif($id == 5){
        $tablename = "swiss_sound";
        $tablename1 = "swiss_vibrationconverted";
        $tablename2 = "swiss_vibration_total";
        $tablename3 = "view003";
      }
      $sql = query_ce_sound_1hr($start_date, $end_date, $fsort, $sortBy, $tablename);
      $sound_1hr = DB::connection('mysql1')->select( DB::raw($sql) );
      $sound_1hr = collect($sound_1hr)->first();
      if($id == 1 || $id == 2){
        $sql = query_ce_vibration_x1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
        $vibration_x1hr = DB::connection('mysql1')->select( DB::raw($sql) );
        $vibration_x1hr = collect($vibration_x1hr)->first();
        $sql = query_ce_vibration_y1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
        $vibration_y1hr = DB::connection('mysql1')->select( DB::raw($sql) );
        $vibration_y1hr = collect($vibration_y1hr)->first();
        $sql = query_ce_vibration_z1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
        $vibration_z1hr = DB::connection('mysql1')->select( DB::raw($sql) );
        $vibration_z1hr = collect($vibration_z1hr)->first();
      }
      $sql = query_ce_pm25_1hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      $pm25_1hr = DB::connection('mysql1')->select( DB::raw($sql) );
      $pm25_1hr = collect($pm25_1hr)->first();
      $sql = query_ce_pm10_1hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      $pm10_1hr = DB::connection('mysql1')->select( DB::raw($sql) );
      $pm10_1hr = collect($pm10_1hr)->first();
      if($id == 1 || $id == 2){
        $data = ["sound_1hr" => $sound_1hr,
        "vibration_x1hr" => $vibration_x1hr,
        "vibration_y1hr" => $vibration_y1hr,
        "vibration_z1hr" => $vibration_z1hr,
        "pm25_1hr" => $pm25_1hr,
        "pm10_1hr" => $pm10_1hr];
      }else{
        $data = ["sound_1hr" => $sound_1hr,
        "pm25_1hr" => $pm25_1hr,
        "pm10_1hr" => $pm10_1hr];
      }
      return $data;
    }
    public function getData($request)
    {
      if(isset($request->start_date) && isset($request->end_date)){
        $start_date = formatDate($request->start_date);
        $end_date = formatDate($request->end_date);
      }else{
        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d");
      }
      if(isset($request->order[0]["dir"])){
        $sortBy = $request->order[0]["dir"];
        $col = $request->order[0]["column"]+1;
      }else{
        $sortBy = "asc";
        $col = 1;
      }
      if($start_date == $end_date){
        $show_date = 1;
      }else{
        $show_date = 0;
      }
      $fsort = "f".$col;
      if($request->id == 1){
        $tablename = "ce_sound_actual";
        $tablename1 = "ce_vibrationconverted";
        $tablename2 = "ce_vibration_total";
        $tablename3 = "view005";
      }elseif($request->id == 2){
        $tablename = "nailert_sound";
        $tablename1 = "nailert_vibrationconverted";
        $tablename2 = "nailert_vibration_total";
        $tablename3 = "view006";
      }elseif($request->id == 3){
        $tablename = "newhouse_sound_actual";
        $tablename1 = "newhouse_vibrationconverted";
        $tablename2 = "newhouse_vibration_total";
        $tablename3 = "view007";
      }elseif($request->id == 4){
        $tablename = "sivatel_sound";
        $tablename1 = "sivatel_vibrationconverted";
        $tablename2 = "sivatel_vibration_total";
        $tablename3 = "view004";
      }elseif($request->id == 5){
        $tablename = "swiss_sound";
        $tablename1 = "swiss_vibrationconverted";
        $tablename2 = "swiss_vibration_total";
        $tablename3 = "view003";
      }
      if($request->type == 1){
        $sql = query_ce_sound_1hr($start_date, $end_date, $fsort, $sortBy, $tablename);
      }elseif($request->type == 2){
        $sql = query_ce_sound_24hr($start_date, $end_date, $fsort, $sortBy, $tablename);
      }elseif($request->type == 3){
        $sql = query_ce_sound_ldn($start_date, $end_date, $fsort, $sortBy, $tablename);
      }elseif($request->type == 4){
        $sql = query_ce_sound_5min($start_date, $end_date, $fsort, $sortBy, $tablename);
      }elseif($request->type == 5){
        $sql = query_ce_vibration_x1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
      }elseif($request->type == 6){
        $sql = query_ce_vibration_y1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
      }elseif($request->type == 7){
        $sql = query_ce_vibration_z1hr($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
      }elseif($request->type == 8){
        $sql = query_ce_vibration_minute($start_date, $end_date, $fsort, $sortBy, $tablename1, $tablename2);
      }elseif($request->type == 9){
        $sql = query_ce_pm25_1hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      }elseif($request->type == 10){
        $sql = query_ce_pm10_1hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      }elseif($request->type == 11){
        $sql = query_ce_pm25_24hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      }elseif($request->type == 12){
        $sql = query_ce_pm10_24hr($start_date, $end_date, $fsort, $sortBy, $tablename3);
      }
      $allData = DB::connection('mysql1')->select( DB::raw($sql) );
      $allData = collect($allData);
      return $allData;
    }
    public function formatGraft1($allData, $limit, $date_type = 0, $type)
    {
      $data_date = [];
      $data1 = [];
      $data2 = [];
      $data3 = [];
      $data_limit = [];
      foreach ($allData as $key => $value) {
        array_push($data_date, DateThai($value->f1, $date_type));
        array_push($data1, $value->f2);
        array_push($data2, $value->f3);
        array_push($data3, $value->f4);
        array_push($data_limit, $limit);
      }
      $data = [];
      if($type == 1 || $type == 4){
        $dataname1 = "LAeq";
        $dataname2 = "LMax";
        $dataname3 = "L90";
        $namelimit = $dataname1;
        $namey = "ระดับค่าตรวจวัด (dB A)";
        $data1 = style_line($dataname1, $data1, getcolorgreen());
        $data2 = style_line($dataname2, $data2, getcoloror());
        $data3 = style_line($dataname3, $data3, getcolorblue());
        array_push($data, $data1);
        array_push($data, $data2);
        array_push($data, $data3);
      }else{
        $dataname1 = "Min";
        $dataname2 = "ค่าตรวจวัด";
        $dataname3 = "Max";
        if($type == 9 || $type == 11){
          $namelimit = "PM 2.5";
        }else{
          $namelimit = "PM 10";
        }
        $namey = "ระดับค่าตรวจวัด (ไมโครกรัม / ลูกบาศก์เมตร)";
        $data1 = ["name" => $dataname1, "symbolSize" => 10, "symbol" => "circle", "type" => "line", "data" => $data1, "smooth" => true];
        $data2 = style_line($namelimit, $data2, getcolorgreen());
        $data3 = ["name" => $dataname3, "symbolSize" => 10, "symbol" => "circle", "type" => "line", "data" => $data3, "smooth" => true];
        array_push($data, $data2);
      }
      $data_def = getLimitLine($limit, $data_limit, $namelimit, $type);
      array_push($data, $data_def);
      $datas = ["data_g" => $data, "data_under" => $data_date, "namey" => $namey];
      return $datas;
    }
    public function formatGraft2($allData, $limit)
    {
      $data_date = [];
      $data_LAEQ = [];
      $data_LMax = [];
      $data_limit = [];
      foreach ($allData as $key => $value) {
        array_push($data_date, DateThai($value->f1, 97));
        array_push($data_LAEQ, $value->f2);
        array_push($data_LMax, $value->f3);
        array_push($data_limit, $limit);
      }
      $fdata_LAEQ = style_line("LAeq", $data_LAEQ, getcolorgreen());
      $data_LMax = style_line("LMax", $data_LMax, getcoloror());
      $data_def = getLimitLine($limit, $data_limit, "LAeq");
      $data = [];
      array_push($data, $fdata_LAEQ);
      array_push($data, $data_LMax);
      array_push($data, $data_def);
      $datas = ["data_g" => $data, "data_under" => $data_date, "namey" => "ระดับค่าตรวจวัด (dB A)"];
      return $datas;
    }
    public function formatGraft3($allData, $limit)
    {
      $data_date = [];
      $data_LAEQ = [];
      $data_limit = [];
      foreach ($allData as $key => $value) {
        array_push($data_date, DateThai($value->f1, 97));
        array_push($data_LAEQ, $value->f2);
        array_push($data_limit, $limit);
      }
      $fdata_LAEQ = style_line("LDN", $data_LAEQ, getcolorgreen());
      $data_def = getLimitLine($limit, $data_limit, "LAeq");
      $data = [];
      array_push($data, $fdata_LAEQ);
      array_push($data, $data_def);
      $datas = ["data_g" => $data, "data_under" => $data_date, "namey" => "ระดับค่าตรวจวัด (dB A)"];
      return $datas;
    }
    public function formatGraft4($allData, $limit, $date_type = 0, $type)
    {
      $data_date = [];
      $data1 = [];
      $data2 = [];
      $data3 = [];
      $data_limit = [];
      foreach ($allData as $key => $value) {
        array_push($data_date, DateThai($value->f1, $date_type));
        array_push($data1, $value->f2);
        array_push($data2, $value->f3);
        array_push($data3, $value->f4);
        array_push($data_limit, $limit);
      }
      if($type == 5){
        $nameType = "X";
      }elseif($type == 6){
        $nameType = "Y";
      }else{
        $nameType = "Z";
      }
      $data1 = style_line("Frequency ".$nameType, $data1, getcolorblue());
      $data2 = style_line("Vibration Reference ".$nameType, $data2, getcolorred());
      $data3 = style_line("Vibration ".$nameType, $data3, getcolorgreen());
      $data = [];
      array_push($data, $data1);
      array_push($data, $data2);
      array_push($data, $data3);
      // array_push($data, $data_def);
      $datas = ["data_g" => $data, "data_under" => $data_date, "namey" => "ระดับค่าตรวจวัด (mm / s)"];
      return $datas;
    }
    public function formatGraft5($allData, $limit, $date_type = 0, $type)
    {
      $data_date = [];
      $data1 = [];
      $data2 = [];
      $data3 = [];
      $data4 = [];
      $data5 = [];
      $data6 = [];
      $data7 = [];
      $data8 = [];
      $data9 = [];
      $data_limit = [];
      foreach ($allData as $key => $value) {
        array_push($data_date, DateThai($value->f1, 0));
        array_push($data1, $value->f2);
        array_push($data2, $value->f3);
        array_push($data3, $value->f4);
        array_push($data4, $value->f6);
        array_push($data5, $value->f7);
        array_push($data6, $value->f8);
        array_push($data7, $value->f10);
        array_push($data8, $value->f11);
        array_push($data9, $value->f12);
        array_push($data_limit, $limit);
      }
      $data1 = style_line("Frequency X", $data1, getcolorblue());
      $data2 = style_line("Vibration Reference X", $data2, getcolorred());
      $data3 = style_line("Vibration X", $data3, getcolorgreen());
      $data4 = style_line("Frequency Y", $data4, getcolorblue());
      $data5 = style_line("Vibration Reference Y", $data5, getcolorred());
      $data6 = style_line("Vibration Y", $data6, getcolorgreen());
      $data7 = style_line("Frequency Z", $data7, getcolorblue());
      $data8 = style_line("Vibration Reference Z", $data8, getcolorred());
      $data9 = style_line("Vibration Z", $data9, getcolorgreen());
      // $data_def = getLimitLine($data_limit);
      $data_set1 = [];
      $data_set2 = [];
      $data_set3 = [];
      array_push($data_set1, $data1);
      array_push($data_set1, $data2);
      array_push($data_set1, $data3);
      array_push($data_set2, $data4);
      array_push($data_set2, $data5);
      array_push($data_set2, $data6);
      array_push($data_set3, $data7);
      array_push($data_set3, $data8);
      array_push($data_set3, $data9);
      // array_push($data, $data_def);
      $datas = ["data_g1" => $data_set1, "data_g2" => $data_set2, "data_g3" => $data_set3, "data_under" => $data_date, "namey" => "ระดับค่าตรวจวัด (mm / s)"];
      return $datas;
    }
    public function formatTable1($allData, $type = 0)
    {
      foreach ($allData as $key => $value) {
        $data_user[] = [DateThai($value->f1, $type), $value->f2, $value->f3, $value->f4, null, null, null, null, null, null, null, null, null];
      }
      return $data_user;
    }
    public function formatTable2($allData)
    {
      foreach ($allData as $key => $value) {
        $data_user[] = [DateThai($value->f1), $value->f2, $value->f3, null, null, null, null, null, null, null, null, null, null];
      }
      return $data_user;
    }
    public function formatTable3($allData)
    {
      foreach ($allData as $key => $value) {
        $data_user[] = [DateThai($value->f1), $value->f2, null, null, null, null, null, null, null, null, null, null, null];
      }
      return $data_user;
    }
    public function formatTable4($allData)
    {
      foreach ($allData as $key => $value) {
        if($value->f5 == "ปกติ"){
          $value->f5 = '<span class="badge badge-success">ปกติ</span>';
        }elseif($value->f5 == "มากกว่าเกณฑ์"){
          $value->f5 = '<span class="badge badge-danger">มากกว่าเกณฑ์</span>';
        }
        $data_user[] = [DateThai($value->f1,3), $value->f2, $value->f3, $value->f4, $value->f5, null, null, null, null, null, null, null, null];
      }
      return $data_user;
    }
    public function formatTable5($allData)
    {
      foreach ($allData as $key => $value) {
        if($value->f5 == "ปกติ"){
          $value->f5 = '<span class="badge badge-success">ปกติ</span>';
        }elseif($value->f5 == "มากกว่าเกณฑ์"){
          $value->f5 = '<span class="badge badge-danger">มากกว่าเกณฑ์</span>';
        }
        if($value->f9 == "ปกติ"){
          $value->f9 = '<span class="badge badge-success">ปกติ</span>';
        }elseif($value->f9 == "มากกว่าเกณฑ์"){
          $value->f9 = '<span class="badge badge-danger">มากกว่าเกณฑ์</span>';
        }
        if($value->f13 == "ปกติ"){
          $value->f13 = '<span class="badge badge-success">ปกติ</span>';
        }elseif($value->f13 == "มากกว่าเกณฑ์"){
          $value->f13 = '<span class="badge badge-danger">มากกว่าเกณฑ์</span>';
        }
        $data_user[] = [DateThai($value->f1, 0), $value->f2, $value->f3, $value->f4, $value->f5, $value->f6, $value->f7, $value->f8, $value->f9, $value->f10, $value->f11, $value->f12, $value->f13];
      }
      return $data_user;
    }
}
