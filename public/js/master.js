var myChart = echarts.init(document.getElementById('chart_sensor'));
var myChart1 = echarts.init(document.getElementById('chart_sensor1'));
var myChart2 = echarts.init(document.getElementById('chart_sensor2'));
var myChart3 = echarts.init(document.getElementById('chart_sensor3'));
var myChart4 = echarts.init(document.getElementById('chart_sensor4'));
var myChart5 = echarts.init(document.getElementById('chart_sensor5'));
var myChart6 = echarts.init(document.getElementById('chart_sensor6'));
var myChart7 = echarts.init(document.getElementById('chart_sensor7'));
var fontsty = 'IBM Plex Sans Thai';
var option_data_table = {
  "sLengthMenu": "แสดง _MENU_",
  "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
  "sInfo": "แสดง _START_ ถึง _END_ ของ _TOTAL_ รายการ",
  "sInfoEmpty": "แสดง 0 ถึง 0 ของ 0 รายการ",
  "sInfoFiltered": "(จากทั้งหมด _MAX_ รายการ)",
  "sSearch": "ค้นหา :",
  "aaSorting" :[[0,'asc']],
  "oPaginate": {
    "sFirst":    "หน้าแรก",
    "sPrevious": "ก่อนหน้า",
    "sNext":     "ถัดไป",
    "sLast":     "หน้าสุดท้าย"
    },
};
var table = $('#datatables').DataTable({
    "processing": true,
    "serverSide": true,
    "scrollX": true,
    "ajax": {
      "url": $('#urlRechercheT').val(),
      "type": "GET",
      "data": function(d){
        d.id = $('#id').val(),
        d.type = $('#type').val(),
        d.start_date = $('#start_date').val(),
        d.end_date = $('#end_date').val()
      }
    },
    "searching": false,
    "bLengthChange": true,
    "oLanguage": option_data_table,
    "lengthMenu": [[20, 50, 100, 200], [20, 50, 100, "ทั้งหมด"]],
    "ordering": true
});
$(document).ready(function() {
  $('.datepicker').change(function(event) {
    $("#export_start_date").val($("#start_date").val());
    $("#export_end_date").val($("#end_date").val());
  });
  $('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose:true
  });
  reload_graft();
  $(".reload_data_1").click(function(event) {
    reload_graft();
    table.ajax.reload(null, false);
  });
  $(".reload_data").change(function(event) {
    reload_graft();
    table.ajax.reload(null, false);
  });
  $(".selectmain").change(function(event) {
    $(".select1").val(1);
    table.columns( [0,1,2,3] ).visible( true );
    table.columns( [4,5,6,7,8,9,10,11,12] ).visible( false );
    $(".f2").html("LAeq");
    $(".f3").html("LMax");
    $(".f4").html("L90");
    reload_graft();
    table.ajax.reload(null, false);
  });
  table.columns( [ 4,5,6,7,8,9,10,11,12 ] ).visible( false );
  $(".selectmain").change(function(event) {
    id = $(this).val();
    if(id == 3 || id == 4 || id == 5){
      $(".select1 option[value=5]").hide();
      $(".select1 option[value=6]").hide();
      $(".select1 option[value=7]").hide();
      $(".select1 option[value=8]").hide();
    }else{
      $(".select1 option[value=5]").show();
      $(".select1 option[value=6]").show();
      $(".select1 option[value=7]").show();
      $(".select1 option[value=8]").show();
    }
  });
  $(".select1").change(function(event) {
    id = $(this).val();
    if(id == 1 || id == 4 || id == 9 || id == 10 || id == 11 || id == 12){
      table.columns( [0,1,2,3] ).visible( true );
      table.columns( [4,5,6,7,8,9,10,11,12] ).visible( false );
      if(id == 1 || id == 4){
        $(".f2").html("LAeq");
        $(".f3").html("LMax");
        $(".f4").html("L90");
      }else{
        $(".f2").html("Min");
        $(".f3").html("ค่าตรวจวัด");
        $(".f4").html("Max");
      }
    }else if(id == 2){
      table.columns( [0,1,2] ).visible( true );
      table.columns( [3,4,5,6,7,8,9,10,11,12] ).visible( false );
      $(".f2").html("LAeq");
      $(".f3").html("LMax");
    }else if(id == 3){
      table.columns( [0,1] ).visible( true );
      table.columns( [2,3,4,5,6,7,8,9,10,11,12] ).visible( false );
      $(".f2").html("LDN");
    }else if(id == 5 || id == 6  || id == 7){
      table.columns( [0,1,2,3,4] ).visible( true );
      table.columns( [5,6,7,8,9,10,11,12] ).visible( false );
      if(id == 5){
        $(".f2").html("Frequency X");
        $(".f3").html("Vibration Reference X");
        $(".f4").html("Vibration X");
        $(".f5").html("Result");
      }else if(id == 6){
        $(".f2").html("Frequency Y");
        $(".f3").html("Vibration Reference Y");
        $(".f4").html("Vibration Y");
        $(".f5").html("Result");
      }else{
        $(".f2").html("Frequency z");
        $(".f3").html("Vibration Reference Z");
        $(".f4").html("Vibration z");
        $(".f5").html("Result");
      }
    }else if(id == 8){
      table.columns( [0,1,2,3,4,5,6,7,8,9,10,11,12] ).visible( true );
      $(".f2").html("Frequency X");
      $(".f3").html("Vibration Reference X");
      $(".f4").html("Vibration X");
      $(".f5").html("Result");
      $(".f6").html("Frequency Y");
      $(".f7").html("Vibration Reference Y");
      $(".f8").html("Vibration Y");
      $(".f9").html("Result");
      $(".f10").html("Frequency Z");
      $(".f11").html("Vibration ReferenceZ");
      $(".f12").html("Vibration Z");
      $(".f13").html("Result");
    }
  });
});
function reload_graft() {
  $(".chart_sensor").hide();
  $.ajax({
    url: $('#urlRechercheG').val(),
    type: 'GET',
    dataType: 'json',
    data: {
      id : $('#id').val(),
      type : $('#type').val(),
      start_date : $('#start_date').val(),
      end_date : $('#end_date').val()
    }
  })
  .done(function(re) {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(re.status == 1){
      if($('#type').val() == 2){
        $("#chart_sensor1").show();
        option = setOptionCharts(re.data_g, re.data_under, re.title, start_date, end_date, re.namey);
        myChart1.setOption(option);
      }else if($('#type').val() == 3){
        $("#chart_sensor2").show();
        option = setOptionCharts(re.data_g, re.data_under, re.title, start_date, end_date, re.namey);
        myChart2.setOption(option);
      }else if($('#type').val() == 5 || $('#type').val() == 6 || $('#type').val() == 7){
        $("#chart_sensor3").show();
        option = setOptionCharts(re.data_g, re.data_under, re.title, start_date, end_date, re.namey);
        myChart3.setOption(option);
      }else if($('#type').val() == 8){
        $("#chart_sensor4").show();
        $("#chart_sensor6").show();
        $("#chart_sensor7").show();
        option = setOptionCharts(re.data_g1, re.data_under, re.title1, start_date, end_date, re.namey);
        myChart4.setOption(option);
        option = setOptionCharts(re.data_g2, re.data_under, re.title2, start_date, end_date, re.namey);
        myChart6.setOption(option);
        option = setOptionCharts(re.data_g3, re.data_under, re.title3, start_date, end_date, re.namey);
        myChart7.setOption(option);
      }else if($('#type').val() == 9 || $('#type').val() == 10 || $('#type').val() == 11 || $('#type').val() == 12){
        $("#chart_sensor5").show();
        option = setOptionCharts(re.data_g, re.data_under, re.title, start_date, end_date, re.namey);
        myChart5.setOption(option);
      }else{
        $("#chart_sensor").show();
        option = setOptionCharts(re.data_g, re.data_under, re.title, start_date, end_date, re.namey);
        myChart.setOption(option);
      }
    }else{
      $("#chart_sensor1").show();
      $("#chart_sensor1").html("ไม่พบข้อมูลที่ค้นหา");
    }
  })
  .fail(function() {
    reload_graft();
  });
}
function setOptionCharts(datas, data_under, title, start_date, end_date, namey) {
  if(start_date == end_date){
    var name_date = start_date;
  }else{
    var name_date = start_date+" ถึง "+end_date;
  }
  option = {
    title: {
      text: title,
      subtext :"ข้อมูลวันที่ "+name_date,
      subtextStyle:{
        fontSize : 14,
        fontFamily: fontsty
      },
      left: 'center',
      textStyle:{
        fontSize : 20,
        fontFamily: fontsty
      }
    },
    tooltip: {
      trigger: 'item',
      textStyle: {
        fontSize: '14',
        fontFamily: fontsty
      },
      formatter: function (params) {

        if(params.seriesName == null){
          html = "ระดับค่ามาตราฐาน : "+params.value;
        }else{
          html = params.seriesName+"<br />"+params.name+"<br />ระดับค่ามลพิษ : "+params.value;
        }
        return html;
      }
    },
    xAxis: {
      show: false,
      type: 'category',
      data: data_under,
      axisLabel: {
        interval: 0,
        rotate: 30,
        textStyle: {
          fontSize : 10,
          fontFamily: fontsty
        }
      }
    },
    yAxis: {
      type : 'value',
      name: namey,
      nameLocation: 'middle',
      nameGap: 50,
      nameTextStyle: {
        fontSize : 20,
        fontFamily: fontsty
      },
      axisLabel: {
        interval: 0,
        textStyle: {
          fontSize : 14,
          fontFamily: fontsty
        }
      }
    },
    legend: {
      show: 'true',
      bottom : 40,
      z: 2,
      textStyle:{
        fontSize : 14,
        fontFamily: fontsty
      }
    },
    series: datas,
    dataZoom: [
      {
        type: 'slider',
        xAxisIndex: [0, 1],
        realtime: true,
        start: 0,
        end: 260,
        height: 20,
        handleIcon:
          'path://M10.7,11.9H9.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4h1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
      }
    ],
  };
  return option;
}
