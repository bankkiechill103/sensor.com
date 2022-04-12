@extends('layouts.master')
@section('content')
  <div class="container stylesheetmain">
    <div class="row pt-3">
      <div class="col-12 text-center">
        <h1>ข้อมูลตรวจวัด การเฝ้าระวังและติดตามผลกระทบสิ่งแวดล้อม</h1>
        <span><i class="fa-solid fa-building"></i> โครงการเซ็นทรัลเอ็มบาสซี่ ส่วนขยาย</span>
      </div>
    </div>
    <form action="#" class="frm_submit_serach">
      <div class="row mt-3">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <select class="form-control selectmain" name="id" id="id">
              @foreach (getnameid() as $key => $value)
                <option @if($key == 1) selected @endif value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <select class="form-control select1 reload_data" name="type" id="type">
              @foreach (getnamemain() as $key => $value)
                <option @if($key == 1) selected @endif value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <label for="startdate"><i class="fa-solid fa-right-long"></i> เริ่มวันที่</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
            </div>
            <input name="start_date" id="start_date" class="form-control datepicker reload_data" type="text" data-provide="datepicker" data-date-language="th-th" placeholder="mm/dd/yyyy" value="{{ date("d/m/Y") }}">
          </div>
        </div>
        <div class="col-12 col-md-6">
          <label for="enddate"><i class="fa-solid fa-left-long"></i> ถึงวันที่</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
            </div>
            <input name="end_date" id="end_date" class="form-control datepicker reload_data" type="text" data-provide="datepicker" data-date-language="th-th" placeholder="mm/dd/yyyy" value="{{ date("d/m/Y") }}">
          </div>
        </div>
        <div class="col-12 text-center">
          <button type="button" class="btn btn-success reload_data_1"><i class="fa-solid fa-magnifying-glass"></i> ดูข้อมูล</button>
        </div>
        <input type="hidden" id="urlRechercheG" name="" value="{{ route('getdatag') }}">
        <input type="hidden" id="urlRechercheT" name="" value="{{ route('getdatat') }}">
      </div>
    </form>
    <div class="row mt-3">
      <div class="col-12 col-md-6 text-left">
        <h4><i class="fa-solid fa-chart-line"></i> กราฟข้อมูลตรวจวัด</h4>
      </div>
      <div class="col-12 col-md-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-file-export"></i> ส่งออก</button>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-12">
        <div class="chart_sensor" id="chart_sensor"></div>
        <div class="chart_sensor" id="chart_sensor1"></div>
        <div class="chart_sensor" id="chart_sensor2"></div>
        <div class="chart_sensor" id="chart_sensor3"></div>
        <div class="chart_sensor" id="chart_sensor4"></div>
        <div class="chart_sensor" id="chart_sensor5"></div>
        <div class="chart_sensor" id="chart_sensor6"></div>
        <div class="chart_sensor" id="chart_sensor7"></div>
      </div>
    </div>
    <div class="row mt-3 pb-5">
      <div class="col-12">
        <table id="datatables" style="width:100%">
          <thead>
              <tr class="text-center">
                  <th class="f1">เวลา</th>
                  <th class="f2">LAEQ</th>
                  <th class="f3">LMax</th>
                  <th class="f4">L90</th>
                  <th class="f5">f5</th>
                  <th class="f6">f6</th>
                  <th class="f7">f7</th>
                  <th class="f8">f8</th>
                  <th class="f9">f9</th>
                  <th class="f10">f10</th>
                  <th class="f11">f11</th>
                  <th class="f12">f12</th>
                  <th class="f13">f13</th>
              </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
@stop
