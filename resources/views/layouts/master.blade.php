<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"> --}}

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap-4.0.0/dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.0.0-beta3-web/css/all.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@500&family=Mitr:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/master.css') }}">
    <title>Sensor.com</title>
  </head>
  <body>
    @yield('content')
    <div class="footers mt-5 text-center">
      Bluework Consultant 2020 CO., LTD. All Rights Reserved.
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <h3><i class="fa-solid fa-calendar-plus"></i> เลือกวันที่ต้องการส่งออก</h3>
            <form>
              <div class="row mt-3">
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="export_id">
                        <h4>จุดตรวจวัด</h4>
                      </label>
                      <select class="form-control selectmain" name="id" id="export_id">
                        @foreach (getnameid() as $key => $value)
                          <option @if($key == 1) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="export_type">
                        <h4>พารามิเตอร์</h4>
                      </label>
                      <select class="form-control select1" name="type" id="export_type">
                        <option value="0">ทั้งหมด</option>
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
                      <input class="form-control icon_date_picker datepicker" id="export_start_date" type="text" data-provide="datepicker" data-date-language="th-th" placeholder="mm/dd/yyyy" value="{{ date("d/m/Y") }}">
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="enddate"><i class="fa-solid fa-left-long"></i> ถึงวันที่</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa-solid fa-calendar-days"></i></div>
                      </div>
                      <input class="form-control icon_date_picker datepicker" id="export_end_date" type="text" data-provide="datepicker" data-date-language="th-th" placeholder="mm/dd/yyyy" value="{{ date("d/m/Y") }}">
                    </div>
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> ปิด</button>
            <button type="button" class="btn btn-primary export_data"><i class="fa-solid fa-file-export"></i> ส่งออก Excel</button>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap-4.0.0/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/echarts.min.js') }}"></script>
    <script src="{{ asset('bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.th.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="{{ asset('js/master.js') }}"></script>
    <script src="https://api.longdo.com/map/?key=68a2e7ba828c715b21b16ed97efd0f1b"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".export_btn").click(function(event) {
          $("#export_type").val(0);
        });
        $(".export_data").click(function(event) {
          id = $('#export_id').val();
          type = $('#export_type').val();
          start_date = $('#export_start_date').val();
          end_date = $('#export_end_date').val();
          start_date = start_date.replace("/", "-");
          start_date = start_date.replace("/", "-");
          end_date = end_date.replace("/", "-");
          end_date = end_date.replace("/", "-");
          link = "{{ ENV('APP_URL') }}/exports/"+id+"/"+type+"/"+start_date+"/"+end_date;
          if (confirm("คุณต้องการส่งออกข้อมูลที่เลือกใช่หรือไม่") == true) {
            window.open(link, '_blank');
          }
        });
        var map;
        var marker1 = new longdo.Marker({ lon: 100.56, lat: 13.74 },
        {
          title: 'Marker',
          icon: {
            url: 'https://map.longdo.com/mmmap/images/pin_mark.png',
            offset: { x: 12, y: 45 }
          }
        });
        var popup3 = new longdo.Popup({ lon: 100.56, lat: 13.74 },
        {
          html: '<div style="background: #eeeeff; padding:15px 15px;">TESTsss</div>',
        });
        map = new longdo.Map({
          placeholder: document.getElementById('result')
        });
        map.Overlays.add(marker1);
        map.Overlays.add(popup3);
      });
    </script>
  </body>
</html>
