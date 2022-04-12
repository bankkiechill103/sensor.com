<?php
function DateThai($strDate, $type = 0)
{
  if($type == 3){
    $tempDate = explode(" ", $strDate);
    $strDate = $tempDate[0];
  }
  $locale = 'th';
  if($locale == "en"){
    $strMonthCut = Array("","Jan.","Feb.","Mar.","Apr.","May.","Jun.","Jul.","Aug.","Sep.","Oct.","Nov.","Dec.");
    $strYear = date("Y",strtotime($strDate));
  }else{
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $strYear = date("Y",strtotime($strDate))+543;
  }
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));
  $strHour= date("H",strtotime($strDate));
  $strMinute= date("i",strtotime($strDate));
  $strSeconds= date("s",strtotime($strDate));
  // $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
  $strMonthThai=$strMonthCut[$strMonth];
  if($type == 1){
    $datas = "$strDay $strMonthThai $strYear";
  }elseif($type == 2){
    $datas = "$strHour:$strMinute";
  }elseif($type == 3){
    if(isset($tempDate[1])){
      $datas = "$strDay $strMonthThai $strYear ".$tempDate[1].":00";
    }else{
      $datas = "$strDay $strMonthThai $strYear";
    }
  }elseif($type == 11){
    $datas = "$strHour:$strMinute";
  }else{
    $datas = "$strDay $strMonthThai $strYear $strHour:$strMinute";
  }
  return $datas;
}
function getnameid()
{
  $id = [
    "1" => "จุดที่ 1 ภายในพื้นที่โครงการด้านติด CE เดิม",
    "2" => "จุดที่ 2 ภายในพื้นที่โครงการด้านติดพาร์คนายเลิศ",
    "3" => "จุดที่ 3 พื้นที่อ่อนไหว อาคารนิวเฮาส์",
    "4" => "จุดที่ 4 อาคารศิวาเทล",
    "5" => "จุดที่ 5 สถานฑูตสวิสเซอร์แลนด์"];
  return $id;
}
function getnamemain()
{
  $main = [
    "1" => "เสียง 1 ชั่วโมง",
    "2" => "เสียง 24 ชั่วโมง",
    "3" => "เสียง LDN 24 ชั่วโมง",
    "4" => "เสียง 5 นาที",
    "5" => "สั่นสะเทือนแกน X 1 ชั่วโมง",
    "6" => "สั่นสะเทือนแกน Y 1 ชั่วโมง",
    "7" => "สั่นสะเทือนแกน Z 1 ชั่วโมง",
    "8" => "สั่นสะเทือนแกน X, Y, Z มากกว่าค่ามาตรฐาน",
    "9" => "ฝุ่น PM 2.5 1 ชั่วโมง",
    "10" => "ฝุ่น PM 10 1 ชั่วโมง",
    "11" => "ฝุ่น PM 2.5 24 ชั่วโมง",
    "12" => "ฝุ่น PM 10 24 ชั่วโมง"
  ];
  return $main;
}
function getnamemain1()
{
  $main = [
    "1" => "เสียง 1 ชั่วโมง",
    "2" => "เสียง 24 ชั่วโมง",
    "3" => "เสียง LDN 24 ชั่วโมง",
    "4" => "เสียง 5 นาที",
    "9" => "ฝุ่น PM 2.5 1 ชั่วโมง",
    "10" => "ฝุ่น PM 10 1 ชั่วโมง",
    "11" => "ฝุ่น PM 2.5 24 ชั่วโมง",
    "12" => "ฝุ่น PM 10 24 ชั่วโมง"
  ];
  return $main;
}
function formatDate($date)
{
  $date = str_replace('/', '-', $date);
  $temp_date = date_create($date);
  $date = date_format($temp_date,"Y-m-d");
  return $date;
}
function getLimitLine($mainval, $val, $namelimit, $type = 0)
{
  if($type == 1 || $type == 4){
    $name = "ค่าเส้นกราฟ = ".$mainval;
  }else{
    $name = "ค่ามาตรฐาน ".$namelimit." = ".$mainval;
  }
  $name = "ค่ามาตรฐาน ".$namelimit." = ".$mainval;
  $limitline = ["name" => $name, "symbol" => "none", "type" => "line", "data" => $val, "itemStyle" =>["color" => "rgb(244, 56, 56)"], "markLine" => ["data" => [["type" => "average", "name" => "ค่ามาตราฐานมลพิษ"]]]];
  return $limitline;
}
function query_ce_sound_1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  if($tablename == "sivatel_sound" || $tablename== "swiss_sound"){
    $namelmax = "lmax";
  }else{
    $namelmax = "lafmax";
  }
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        str_to_date(datetime,'%Y-%m-%d %H:%i:%s') as f1
        ,round(10*log10((1/12)*sum(caled)),2) as f2
        ,round(max(lmax),2) as f3
        ,round(min(l90),2) as f4
      from
        (
        select
          if(date_format(datetime,'%i:%s') <> '00:00'
            ,concat(date(datetime),' ',date_format(datetime,'%H:00:00'))
            ,concat(date(datetime),' ',date_format(subtime(datetime,'1000'),'%H:00:00'))
          ) as datetime
          ,power(10,0.1*laeq) as caled
          ,la90 as l90
          ,$namelmax as lmax
        from
          cpn.$tablename
        where
          (
            datetime >= '$start_date'
            AND datetime <= '$end_date'
          )
        ) as cal_1
      group by
        datetime
      order by
        $fsort $sortBy
  ";
  return $sql;
}
function query_ce_sound_5min($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  if($tablename == "sivatel_sound" || $tablename== "swiss_sound"){
    $namelmax = "lmax";
  }else{
    $namelmax = "lafmax";
  }
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        datetime as f1
        ,laeq as f2
        ,$namelmax as f3
        ,la90 as f4
      from
        cpn.$tablename
      where
        (
          datetime >= '$start_date'
          AND datetime <= '$end_date'
        )
      order by
        $fsort $sortBy
  ";
  return $sql;
}
function query_ce_sound_24hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  if($tablename == "sivatel_sound" || $tablename== "swiss_sound"){
    $namelmax = "lmax";
  }else{
    $namelmax = "lafmax";
  }
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        str_to_date(datetime,'%Y-%m-%d 00:00:00') as f1
        ,round(10*log10((1/24)*sum(caled_)),2) as f2
        ,round(max(lmax),2) as f3
      from
      (
        select
          date_format(datetime,'%Y-%m-%d') as datetime
          ,power(10,0.1*raw_) as caled_
          ,lmax
        from
        (
          select
            datetime
            ,round(10*log10((1/12)*sum(caled_)),2) as raw_
            ,max(lmax) as lmax
          from
            (
            select
              if(date_format(datetime,'%i:%s') <> '00:00'
                ,concat(date(datetime),' ',date_format(datetime,'%H'))
                ,concat(date(datetime),' ',date_format(subtime(datetime,'1000'),'%H'))
              ) as datetime
              ,'ce' as site

              ,power(10,0.1*laeq) as caled_
              ,$namelmax as lmax
            from
              cpn.$tablename
            where
              (
                datetime >= '$start_date'
                AND datetime <= '$end_date'
              )

            ) as cal_1
          group by
            datetime
        ) as cal_2

        group by
          datetime

      ) as cal_3
      group by
        datetime
      order by
        $fsort $sortBy
  ";
  return $sql;
}
function query_ce_sound_ldn($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
    select datetime as f1 ,round(10*log10(sum(valuecaled)),2) as f2
    from
    (
      select * ,if(ldn = 'ld' ,0.625*power(10,value/10) ,0.375*power(10,value/10) ) as valuecaled
      from
      (
        select str_to_date(datetime,'%Y-%m-%d 00:00:00') as datetime ,'ld' as ldn ,round(10*log10((1/15)*sum(laeq)),2) as value
        from
        (
          select datetime ,datetimehour ,power(10,laeq/10) as laeq
          from
          (
            select date_format(datetime,'%Y-%m-%d') as datetime ,date_format(datetime,'%Y-%m-%d %H') as datetimehour ,10*log10((1/12)*sum(laeq)) as laeq
            from
            (
              select if(date_format(datetime,'%i:%s') <> '00:00' ,concat(date(datetime),' ',date_format(datetime,'%H')) ,concat(date(datetime),' ',date_format(subtime(datetime,'1000'),'%H'))
            ) as datetime
            ,power(10,laeq/10) as laeq
            from
               cpn.$tablename
            where
              datetime >= '$start_date'
              AND datetime <= '$end_date'
           ) as cal_1
           group by
            datetime
           order by
            datetimehour
          ) as cal_2
          where
          datetimehour like '%07'
          or
          datetimehour like '%08'
          or
          datetimehour like '%09'
          or
          datetimehour like '%10'
          or
          datetimehour like '%11'
          or
          datetimehour like '%12'
          or
          datetimehour like '%13'
          or
          datetimehour like '%14'
          or
          datetimehour like '%15'
          or
          datetimehour like '%16'
          or
          datetimehour like '%17'
          or
          datetimehour like '%18'
          or
          datetimehour like '%19'
          or
          datetimehour like '%20'
          or
          datetimehour like '%21'
        ) as cal_3
        group by
          datetime
        union
        select str_to_date(datetime,'%Y-%m-%d 00:00:00') as datetime ,'ln' as ldn ,round(10*log10((1/9)*sum(laeq)),2) as value
        from
        (
          select datetime ,datetimehour ,power(10,(laeq+10)/10) as laeq
          from
          (
            select
              date_format(datetime,'%Y-%m-%d') as datetime
              ,date_format(datetime,'%Y-%m-%d %H') as datetimehour
              ,10*log10((1/12)*sum(laeq)) as laeq
            from
            (
              select
              if(date_format(datetime,'%i:%s') <> '00:00'
                ,concat(date(datetime),' ',date_format(datetime,'%H'))
                ,concat(date(datetime),' ',date_format(subtime(datetime,'1000'),'%H'))
              ) as datetime ,power(10,laeq/10) as laeq
              from
                cpn.$tablename
              where
                datetime >= '$start_date'
                AND datetime <= '$end_date'
            ) as cal_1
            group by
              datetime
            order by
              datetimehour
          ) as cal_2
          where
          datetimehour like '%00'
          or
          datetimehour like '%01'
          or
          datetimehour like '%02'
          or
          datetimehour like '%03'
          or
          datetimehour like '%04'
          or
          datetimehour like '%05'
          or
          datetimehour like '%06'
          or
          datetimehour like '%22'
          or
          datetimehour like '%23'
        ) as cal_3
        group by
          datetime
      ) as ldunionln
    ) as ldnfinish
    group by
      datetime
    order by
      $fsort $sortBy
  ";
  return $sql;
}
function query_ce_vibration_x1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename, $tablename1)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        V.hourdatetime as f1
        ,V.ft_x_at_max_vx as f2
        ,V.v_x_ref as f3
        ,format(V.max_vx,3) as f4
        ,V.result as f5
      from
      (
        select
          IV.hourdatetime as hourdatetime
          ,IV.max_vx as max_vx
          ,IV.max_ft_x as ft_x_at_max_vx
          ,IV.v_x_ref as v_x_ref
          ,if(IV.max_vx < IV.v_x_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result
        from
        (
          select
            III.hourdatetime as hourdatetime
            ,III.max_vx as max_vx
            ,III.max_ft_x as max_ft_x
            ,if(III.max_ft_x <= 10
              ,5
              ,if(III.max_ft_x > 10 and III.max_ft_x <= 50
                ,round((0.25*III.max_ft_x)+2.5,2)
                ,if(III.max_ft_x > 50 and III.max_ft_x <= 100
                  ,round((0.1*III.max_ft_x)+10,2)
                  ,20
                )
              )
            ) as v_x_ref
          from
          (
            select
              hourdatetime
              ,max_vx
              ,max(f_ft_x) as max_ft_x
            from
            (
              select
                a.datetime as realdatetime
                ,I.datetime as hourdatetime
                ,I.max_vx
                ,a.f_ft_x
              from
                cpn.$tablename a ,
                (
                  select
                    date_format(datetime,'%Y-%m-%d %H') as datetime
                    ,max(v_x) as max_vx
                  from
                    cpn.$tablename
                  where
                    datetime >= '$start_date'
                    AND datetime <= '$end_date'
                  group by
                    date_format(datetime,'%Y-%m-%d %H')
                ) as I
              where
                date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
                and
                a.v_x = I.max_vx
            ) as II
            group by
              hourdatetime
          ) as III
        ) as IV
      ) as V
      union
      select
        V.hourdatetime as f1
        ,V.ft_x_at_max_vx as f2
        ,V.v_x_ref as f3
        ,format(V.max_vx,3) as f4
        ,V.result as f5
      from
      (
        select
          IV.hourdatetime as hourdatetime
          ,IV.max_vx as max_vx
          ,IV.max_ft_x as ft_x_at_max_vx
          ,IV.v_x_ref as v_x_ref
          ,if(IV.max_vx < IV.v_x_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result
        from
        (
          select
            III.hourdatetime as hourdatetime
            ,III.max_vx as max_vx
            ,III.max_ft_x as max_ft_x
            ,if(III.max_ft_x <= 10
              ,5
              ,if(III.max_ft_x > 10 and III.max_ft_x <= 50
                ,round((0.25*III.max_ft_x)+2.5,2)
                ,if(III.max_ft_x > 50 and III.max_ft_x <= 100
                  ,round((0.1*III.max_ft_x)+10,2)
                  ,20
                )
              )
            ) as v_x_ref
          from
          (
            select
              hourdatetime
              ,max_vx
              ,max(f_ft_x) as max_ft_x
            from
            (
              select
                a.datetime as realdatetime
                ,I.datetime as hourdatetime
                ,I.max_vx
                ,a.f_ft_x
              from
                cpn.$tablename1 a ,
                (
                  select
                    date_format(datetime,'%Y-%m-%d %H') as datetime
                    ,max(v_x) as max_vx
                  from
                    cpn.$tablename1
                  where
                    datetime >= '$start_date'
                    AND datetime <= '$end_date'
                  group by
                    date_format(datetime,'%Y-%m-%d %H')
                ) as I
              where
                date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
                and
                a.v_x = I.max_vx
            ) as II
            group by
              hourdatetime
          ) as III
        ) as IV
      ) as V
      order by
        $fsort $sortBy

  ";
  return $sql;
}
function query_ce_vibration_y1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename, $tablename1)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
    select
      V.hourdatetime as f1
      ,V.ft_y_at_max_vy as f2
      ,V.v_y_ref as f3
      ,format(V.max_vy,3) as f4
      ,V.result as f5
    from
    (
      select
        IV.hourdatetime as hourdatetime
        ,IV.max_vy as max_vy
        ,IV.max_ft_y as ft_y_at_max_vy
        ,IV.v_y_ref as v_y_ref
        ,if(IV.max_vy < IV.v_y_ref
          ,'ปกติ'
          ,'มากกว่าเกณฑ์'
        ) as result
      from
      (
        select
          III.hourdatetime as hourdatetime
          ,III.max_vy as max_vy
          ,III.max_ft_y as max_ft_y
          ,if(III.max_ft_y <= 10
            ,5
            ,if(III.max_ft_y > 10 and III.max_ft_y <= 50
              ,round((0.25*III.max_ft_y)+2.5,2)
              ,if(III.max_ft_y > 50 and III.max_ft_y <= 100
                ,round((0.1*III.max_ft_y)+10,2)
                ,20
              )
            )
          ) as v_y_ref
          from
          (
            select
              hourdatetime
              ,max_vy
              ,max(f_ft_y) as max_ft_y
            from
            (
              select
                a.datetime as realdatetime
                ,I.datetime as hourdatetime
                ,I.max_vy
                ,a.f_ft_y

              from
                cpn.$tablename a ,
              (
                select
                  date_format(datetime,'%Y-%m-%d %H') as datetime
                  ,max(v_y) as max_vy
                from
                  cpn.$tablename
                where
                  datetime >= '$start_date'
                  AND datetime <= '$end_date'
                group by
                  date_format(datetime,'%Y-%m-%d %H')
              ) as I
              where
                date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
                and
                a.v_y = I.max_vy
            ) as II
          group by
            hourdatetime
        ) as III
      ) as IV
    ) as V
    union
    select
      V.hourdatetime as f1
      ,V.ft_y_at_max_vy as f2
      ,V.v_y_ref as f3
      ,format(V.max_vy,3) as f4
      ,V.result as f5
    from
    (
      select
        IV.hourdatetime as hourdatetime
        ,IV.max_vy as max_vy
        ,IV.max_ft_y as ft_y_at_max_vy
        ,IV.v_y_ref as v_y_ref
        ,if(IV.max_vy < IV.v_y_ref
          ,'ปกติ'
          ,'มากกว่าเกณฑ์'
        ) as result
      from
      (
        select
          III.hourdatetime as hourdatetime
          ,III.max_vy as max_vy
          ,III.max_ft_y as max_ft_y
          ,if(III.max_ft_y <= 10
            ,5
            ,if(III.max_ft_y > 10 and III.max_ft_y <= 50
              ,round((0.25*III.max_ft_y)+2.5,2)
              ,if(III.max_ft_y > 50 and III.max_ft_y <= 100
                ,round((0.1*III.max_ft_y)+10,2)
                ,20
              )
            )
          ) as v_y_ref
        from
        (
          select
            hourdatetime
            ,max_vy
            ,max(f_ft_y) as max_ft_y
          from
          (
            select
              a.datetime as realdatetime
              ,I.datetime as hourdatetime
              ,I.max_vy
              ,a.f_ft_y
            from
              cpn.$tablename1 a ,
            (
              select
                date_format(datetime,'%Y-%m-%d %H') as datetime
                ,max(v_y) as max_vy
              from
                cpn.$tablename1
              where
                datetime >= '$start_date'
                AND datetime <= '$end_date'
              group by
                date_format(datetime,'%Y-%m-%d %H')
            ) as I
            where
              date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
              and a.v_y = I.max_vy
          ) as II
          group by
            hourdatetime
        ) as III
      ) as IV
    ) as V
    order by
      $fsort $sortBy
  ";
  return $sql;
}
function query_ce_vibration_z1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename, $tablename1)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
       V.hourdatetime as f1
      ,V.ft_z_at_max_vz as f2
      ,V.v_z_ref as f3
      ,format(V.max_vz,3) as f4
      ,V.result as f5

      from
      (
        select
          IV.hourdatetime as hourdatetime
          ,IV.max_vz as max_vz
          ,IV.max_ft_z as ft_z_at_max_vz
          ,IV.v_z_ref as v_z_ref
          ,if(IV.max_vz < IV.v_z_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result
        from
        (

          select
            III.hourdatetime as hourdatetime
            ,III.max_vz as max_vz
            ,III.max_ft_z as max_ft_z
            ,if(III.max_ft_z <= 10
              ,5
              ,if(III.max_ft_z > 10 and III.max_ft_z <= 50
                ,round((0.25*III.max_ft_z)+2.5,2)
                ,if(III.max_ft_z > 50 and III.max_ft_z <= 100
                  ,round((0.1*III.max_ft_z)+10,2)
                  ,20
                )
              )
            ) as v_z_ref

          from
          (

            select
              hourdatetime
              ,max_vz
              ,max(f_ft_z) as max_ft_z
            from
            (

              select
                a.datetime as realdatetime
                ,I.datetime as hourdatetime
                ,I.max_vz
                ,a.f_ft_z

              from
                cpn.$tablename a
                ,
                (

                  select

                    date_format(datetime,'%Y-%m-%d %H') as datetime

                    ,max(v_z) as max_vz

                  from
                    cpn.$tablename

                  where
                    datetime >= '$start_date'
                    AND datetime <= '$end_date'
                  group by
                    date_format(datetime,'%Y-%m-%d %H')
                ) as I

              where
                date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
                and
                a.v_z = I.max_vz
            ) as II

            group by
              hourdatetime

          ) as III

        ) as IV

      ) as V
      union
    select
      V.hourdatetime as f1
      ,V.ft_z_at_max_vz as f2
      ,V.v_z_ref as f3
      ,format(V.max_vz,3) as f4
      ,V.result as f5

    from
    (
      select
        IV.hourdatetime as hourdatetime
        ,IV.max_vz as max_vz
        ,IV.max_ft_z as ft_z_at_max_vz
        ,IV.v_z_ref as v_z_ref
        ,if(IV.max_vz < IV.v_z_ref
          ,'ปกติ'
          ,'มากกว่าเกณฑ์'
        ) as result
      from
      (

        select
          III.hourdatetime as hourdatetime
          ,III.max_vz as max_vz
          ,III.max_ft_z as max_ft_z
          ,if(III.max_ft_z <= 10
            ,5
            ,if(III.max_ft_z > 10 and III.max_ft_z <= 50
              ,round((0.25*III.max_ft_z)+2.5,2)
              ,if(III.max_ft_z > 50 and III.max_ft_z <= 100
                ,round((0.1*III.max_ft_z)+10,2)
                ,20
              )
            )
          ) as v_z_ref

        from
        (

          select
            hourdatetime
            ,max_vz
            ,max(f_ft_z) as max_ft_z
          from
          (

            select
              a.datetime as realdatetime
              ,I.datetime as hourdatetime
              ,I.max_vz
              ,a.f_ft_z

            from

              cpn.$tablename1 a
              ,
              (

                select

                  date_format(datetime,'%Y-%m-%d %H') as datetime

                  ,max(v_z) as max_vz

                from

                  cpn.$tablename1

                where
                  datetime >= '$start_date'
                  AND datetime <= '$end_date'
                group by
                  date_format(datetime,'%Y-%m-%d %H')
              ) as I

            where
              date_format(a.datetime,'%Y-%m-%d %H') = I.datetime
              and
              a.v_z = I.max_vz
          ) as II

          group by
            hourdatetime

        ) as III

      ) as IV

    ) as V
    order by
      $fsort $sortBy

  ";
  return $sql;
}
function query_ce_vibration_minute($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename, $tablename1)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
        select
          datetime as f1,
          fx as f2,
          vx_ref as f3,
          vx as f4,
          result_x as f5,
          fy as f6,
          vy_ref as f7,
          vy as f8,
          result_y as f9,
          fz as f10,
          vz_ref as f11,
          vz as f12,
          result_z as f13
        from
        (

        select
          datetime
          ,site

          ,fx
          ,vx_ref
          ,vx
          ,if(vx < vx_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_x

          ,fy
          ,vy_ref
          ,vy
          ,if(vy < vy_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_y

          ,fz
          ,vz_ref
          ,vz
          ,if(vz < vz_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_z

        from
        (
          select
            datetime
            ,'ce' as site

            ,f_ft_x as fx
            ,if(f_ft_x <= 10
              ,5
              ,if(f_ft_x > 10 and f_ft_x <= 50
                ,round((f_ft_x*0.25)+2.5,0)
                ,if(f_ft_x > 50 and f_ft_x <= 100
                  ,round((f_ft_x*0.1)+10,0)
                  ,20
                )
              )
            ) as vx_ref
            ,v_x as vx

            ,f_ft_y as fy
            ,if(f_ft_y <= 10
              ,5
              ,if(f_ft_y > 10 and f_ft_y <= 50
                ,round((f_ft_y*0.25)+2.5,0)
                ,if(f_ft_y > 50 and f_zc_y <= 100
                  ,round((f_ft_y*0.1)+10,0)
                  ,20
                )
              )
            ) as vy_ref
            ,v_y as vy

            ,f_ft_z as fz
            ,if(f_ft_z <= 10
              ,5
              ,if(f_ft_z > 10 and f_ft_z <= 50
                ,round((f_ft_z*0.25)+2.5,0)
                ,if(f_ft_z > 50 and f_zc_z <= 100
                  ,round((f_ft_z*0.1)+10,0)
                  ,20
                )
              )
            ) as vz_ref
            ,v_z as vz
          from

            cpn.$tablename

          WHERE

          (
            datetime >= '$start_date'
            AND datetime <= '$end_date'
          )

        ) as first


        union

        select
          datetime
          ,site

          ,fx
          ,vx_ref
          ,vx
          ,if(vx < vx_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_x

          ,fy
          ,vy_ref
          ,vy
          ,if(vy < vy_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_y

          ,fz
          ,vz_ref
          ,vz
          ,if(vz < vz_ref
            ,'ปกติ'
            ,'มากกว่าเกณฑ์'
          ) as result_z

        from
        (
          select
            datetime
            ,'ce' as site

            ,f_ft_x as fx
            ,if(f_ft_x <= 10
              ,5
              ,if(f_ft_x > 10 and f_ft_x <= 50
                ,round((f_ft_x*0.25)+2.5,0)
                ,if(f_ft_x > 50 and f_ft_x <= 100
                  ,round((f_ft_x*0.1)+10,0)
                  ,20
                )
              )
            ) as vx_ref
            ,v_x as vx

            ,f_ft_y as fy
            ,if(f_ft_y <= 10
              ,5
              ,if(f_ft_y > 10 and f_ft_y <= 50
                ,round((f_ft_y*0.25)+2.5,0)
                ,if(f_ft_y > 50 and f_zc_y <= 100
                  ,round((f_ft_y*0.1)+10,0)
                  ,20
                )
              )
            ) as vy_ref
            ,v_y as vy

            ,f_ft_z as fz
            ,if(f_ft_z <= 10
              ,5
              ,if(f_ft_z > 10 and f_ft_z <= 50
                ,round((f_ft_z*0.25)+2.5,0)
                ,if(f_ft_z > 50 and f_zc_z <= 100
                  ,round((f_ft_z*0.1)+10,0)
                  ,20
                )
              )
            ) as vz_ref
            ,v_z as vz
          from

            cpn.$tablename1

          WHERE

          (
            datetime >= '$start_date'
            AND datetime <= '$end_date'
          )

        ) as first

        ) as unionresult


        having
        (
          result_x = 'มากกว่าเกณฑ์'
            or
            result_y = 'มากกว่าเกณฑ์'
            or
            result_z = 'มากกว่าเกณฑ์'
        )

        order by
          $fsort $sortBy

  ";
  return $sql;
}
function query_ce_pm25_1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        date_format(data_date_time,'%Y-%m-%d %H') as f1
        ,round(min(pm25),2) as f2
        ,round(avg(pm25),2) as f3
        ,round(max(pm25),2) as f4
      from
        $tablename
      where
        (
          data_date_time >= '$start_date'
          AND data_date_time <= '$end_date'
        )
      group by
        date_format(data_date_time,'%Y-%m-%d %H')
      order by
        $fsort $sortBy
  ";
  return $sql;
}
function query_ce_pm10_1hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        date_format(data_date_time,'%Y-%m-%d %H') as f1
        ,round(min(pm10),2) as f2
        ,round(avg(pm10),2) as f3
        ,round(max(pm10),2) as f4
      from
        $tablename
      where
        (
          data_date_time >= '$start_date'
          AND data_date_time <= '$end_date'
        )
      group by
        date_format(data_date_time,'%Y-%m-%d %H')
      order by
        $fsort $sortBy
  ";
  return $sql;
}
function query_ce_pm25_24hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        date_format(data_date_time,'%Y-%m-%d') as f1
        ,round(min(pm25),2) as f2
        ,round(avg(pm25),2) as f3
        ,round(max(pm25),2) as f4
      from
          $tablename
      where
        (
          data_date_time >= '$start_date'
          AND data_date_time <= '$end_date'
        )
      group by
        date_format(data_date_time,'%Y-%m-%d')
      order by
        $fsort $sortBy
    ";
  return $sql;
}
function query_ce_pm10_24hr($start_date, $end_date, $fsort = "f1", $sortBy = "asc", $tablename)
{
  $start_date = $start_date." 00:00:00";
  $end_date = $end_date." 23:59:99";
  $sql = "
      select
        date_format(data_date_time,'%Y-%m-%d') as f1
        ,round(min(pm10),2) as f2
        ,round(avg(pm10),2) as f3
        ,round(max(pm10),2) as f4
      from
          $tablename
      where
        (
          data_date_time >= '$start_date'
          AND data_date_time <= '$end_date'
        )
      group by
        date_format(data_date_time,'%Y-%m-%d')
      order by
        $fsort $sortBy
    ";
  return $sql;
}
?>
