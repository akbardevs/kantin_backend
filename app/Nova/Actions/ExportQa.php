<?php

namespace App\Nova\Actions;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\UserQuestionAnswer;
use App\Models\Village;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportQa extends DownloadExcel implements WithMapping, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Nama Penulis',
            'Lokasi Penulis',
            'Tanggal Created',
            'Jenis Post',
        ];
    }

    /**
     * @param $order
     *
     * @return array
     */
    public function map($data): array
    {
        
        return [
            $data->id,
            $data->title,
            $this->userData($data->user_id),
            $this->location($data->user_id),
            $data->created_at,
            $this->type(),
        ];
    }

    public function userData($id){
        $user = User::find($id);
        if($user)return $user->name;
        return '-';
    }

    public function type(){
        return 'Tanya Jawab';
    }

    public function location($id){
        $user = User::find($id);
        if($user){
            $location = Village::with('district.city.province')->where('id',$user->village_id)->first();
            $data='';
            if($location != null){
                $data.=$location['district']['city']['province']['name'].' > '.$location['district']['city']['name'].' > '.$location['district']['name'].' > '.$location['name'];
            }
            return $data;
        }
        return '-';
    }
}
