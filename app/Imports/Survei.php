<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Information_types;
use App\Models\Informations;
use Carbon\Carbon;
use App\Models\Report;
use Maatwebsite\Excel\Row;
use App\Models\ReportAnswer;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\OnEachRow;

class Survei implements OnEachRow
{
    /**
    * @param array $row
    * 
    * @return 
    */

    
    public function onRow(Row $row)
    {

        $rowIndex = $row->getIndex();
        if($rowIndex>=2 && $row[0]!=null){
            $id_type = null;
            $id_city = null;
            $valWA = 0;
            $valTLP = 0;

            foreach ($row->getDelegate()->getCellIterator() as $cell) {
                $cellObj = new Cell($cell); 
                $cellPHPOffice = $cellObj->getDelegate(); 
                if ($cellPHPOffice->getHyperlink()->getUrl()) {
                    $valWA = 1; 
                } 
            }

            $type = Information_types::all();
            foreach ($type as $data){ 
                if(strtolower($data['name']) == strtolower($row[0])){
                    $id_type = $data['id'];
                }
            }
            if($id_type == null){
                $save = Information_types::create([
                    'name' => $row[0],
                    'sort' => 0
                ]);
                if($save)$id_type=$save['id'];
            }

            $city = City::where('province_id',74)->get();
            foreach ($city as $data){ 
                if(strtolower($data['name']) == strtolower($row[2]) || strpos(strtolower($data['name']), strtolower($row[2])) !== false){
                    $id_city = $data['id'];
                }
            }
            if($id_city == null)$id_city=7471;
            if($valWA==0)$valTLP=1;
            $dataTitle = '-';
            if($row[3]==null){
                $dataTitle=$row[1];
            } else $dataTitle=$row[3];
            $dataContact=0;
            if($row[4]==null)$dataContact=00; else {
                $data = explode(",",$row[4]);
                $outputString = preg_replace('/[^0-9]/', '', $data[0]); 
                if($outputString!=null && $outputString>=1)$dataContact=$outputString; else $dataContact=00;
            }
            $dataAddress=$row[5];
            if($row[5]==null)$dataAddress='-';
            $dataLink=$row[6];
            if($row[6]==null)$dataLink='-';

            $add = Informations::create([
                'type_id' => $id_type,
                'informant_id' => null,
                'title' => $dataTitle,
                'information' => $row[1],
                'city_id' => $id_city,
                'from' => $dataLink,
                'contact' => $dataContact,
                'whatsapp' => $valWA,
                'phone' => $valTLP,
                'address' => $dataAddress,
                'status' => 1
            ]);
        }
        
        
    }
}
