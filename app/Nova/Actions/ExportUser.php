<?php

namespace App\Nova\Actions;

use App\Models\Answer;
use App\Models\Question;
use App\Models\UserQuestionAnswer;
use App\Models\Village;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportUser extends DownloadExcel implements WithMapping, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'User Created Date',
            'Nama Lengkap',
            'Lokasi',
            'Email',
            'Telepon',
        ];
    }

    /**
     * @param $order
     *
     * @return array
     */
    public function map($user): array
    {
        
        return [
            $user->id,
            $user->created_at,
            $user->name,
            $this->village($user->village_id),
            $user->email,
            $user->phone,
        ];
        
    }

    public function selectAge($value){
        switch ($value) {
            case $value=='UNDER_17':
                return "Kurang dari 17 Tahun";
            case $value=='FROM_17_TO_25':
                return "17 - 25 Tahun";
            case $value=='FROM_17_TO_25':
                return "17 - 40 Tahun";
            case $value=='ABOVE_40':
                return "Lebih dari 40 Tahun";
            default:
            return "-";
        }

    }

    public function village($value){
            $location = Village::with('district.city.province')->where('id',$value)->first();
            $data='';
            if($location != null){
                $data.=$location['district']['city']['province']['name'].' > '.$location['district']['city']['name'].' > '.$location['district']['name'].' > '.$location['name'];
            }
            return $data;
        } 
    
    // public function question($value){
    //     $questionAll = Question::all();
    //     $i=1;
    //     $data='';
    //     $question = UserQuestionAnswer::where('user_id',$value)->get();
    //     foreach($question as $question){
    //         foreach($questionAll as $q){
    //             if($q->id==$question->question_id){
    //                 $data .= $i.'. '.$q->question.'/';
    //                 $i++;
    //             }
    //         }
    //     }
    //     return $data;
    // } 
    
    // public function answer($value){
    //     $answerAll = Answer::all();
    //     $i=1;
    //     $data='';
    //     $answer = UserQuestionAnswer::where('user_id',$value)->get();
    //     foreach($answer as $answer){
    //         foreach($answerAll as $q){
    //             if($q->id==$answer->answer_id){
    //                 $data .= $i.'. '.$q->answer.'/';
    //                 $i++;
    //             }
    //         }
    //     }
    //     return $data;
    // }
}
