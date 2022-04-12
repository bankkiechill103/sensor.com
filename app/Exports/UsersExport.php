<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ResultController;

class UsersExport implements WithMultipleSheets
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        $sheets = [];
        $ResultController = new ResultController;
        $getnamemain = getnamemain();
        if($this->request->type == 0){
          if($this->request->id == 1 || $this->request->id == 2){
            for ($i=1; $i <= 12 ; $i++) {
              $this->request->type = $i;
              $data = $ResultController::getData($this->request);
              $title = $getnamemain[$i];
              $sheets[] = new Check1All($data, $title, $i, $this->request->id);
            }
          }else{
            for ($i=1; $i <= 4 ; $i++) {
              $this->request->type = $i;
              $data = $ResultController::getData($this->request);
              $title = $getnamemain[$i];
              $sheets[] = new Check1All($data, $title, $i, $this->request->id);
            }
            for ($i=9; $i <= 12 ; $i++) {
              $this->request->type = $i;
              $data = $ResultController::getData($this->request);
              $title = $getnamemain[$i];
              $sheets[] = new Check1All($data, $title, $i, $this->request->id);
            }
          }
        }else{
          $data = $ResultController::getData($this->request);
          $title = $getnamemain[$this->request->type];
          $sheets[] = new Check1All($data, $title, $this->request->type, $this->request->id);
        }
        return $sheets;
    }
}
?>
