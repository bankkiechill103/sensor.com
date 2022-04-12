<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;


class Check1All implements FromCollection, WithTitle, WithHeadings
{
    private $data;
    private $title;
    private $type;
    private $id;
    public function __construct($data, $title, $type, $id)
    {
        $this->data = $data;
        $this->title = $title;
        $this->type = $type;
        $this->id = $id;
    }
    /**
     * @return Builder
     */
    public function collection()
    {
        foreach ($this->data as $key => $value) {
          if($this->type == 1 || $this->type == 4){
            $value->f1 = DateThai($value->f1, 0);
          }elseif($this->type == 2 || $this->type == 3 || $this->type == 11 || $this->type == 12){
            $value->f1 = DateThai($value->f1, 1);
          }elseif($this->type == 5 || $this->type == 6 || $this->type == 7 || $this->type == 9 || $this->type == 10){
            $value->f1 = DateThai($value->f1, 3);
          }elseif($this->type == 8){
            $value->f1 = DateThai($value->f1, 0);
          }
        }
        return $this->data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
    public function headings(): array
    {
      if($this->id == 1){
        $nametype = "ce";
      }elseif($this->id == 2){
        $nametype = "nailert";
      }elseif($this->id == 3){
        $nametype = "newhouse";
      }elseif($this->id == 4){
        $nametype = "sivatel";
      }else{
        $nametype = "swiss";
      }
      if($this->type == 1 || $this->type == 4){
        $header = [$nametype."_เวลา", $nametype."_LAeq", $nametype."_LMax", $nametype."_L90"];
      }elseif($this->type == 2){
        $header = [$nametype."_เวลา", $nametype."_LAeq", $nametype."_LMax"];
      }elseif($this->type == 3){
        $header = [$nametype."_เวลา", $nametype."_LDN"];
      }elseif($this->type == 5){
        $header = [$nametype."_เวลา", $nametype."_Frequency X", $nametype."_Vibration Reference X", $nametype."_Vibration X", "Result"];
      }elseif($this->type == 6){
        $header = [$nametype."_เวลา", $nametype."_Frequency Y", $nametype."_Vibration Reference Y", $nametype."_Vibration Y", "Result"];
      }elseif($this->type == 7){
        $header = [$nametype."_เวลา", $nametype."_Frequency Z", $nametype."_Vibration Reference Z", $nametype."_Vibration Z", "Result"];
      }elseif($this->type == 8){
        $header = [$nametype."_เวลา", $nametype."_Frequency X", $nametype."_Vibration Reference X", $nametype."_Vibration X", $nametype."_Result", $nametype."_Frequency Y", $nametype."_Vibration Reference Y", $nametype."_Vibration Y", $nametype."_Result", $nametype."_Frequency Z", $nametype."_Vibration Reference Z", $nametype."_Vibration Z", $nametype."_Result"];
      }else{
        $header = [$nametype."_เวลา", $nametype."_Min", $nametype."_ค่าตรวจวัด", $nametype."_Max"];
      }
      return $header;
    }
}
?>
