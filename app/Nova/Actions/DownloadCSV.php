<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as Cs;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use App\Models\Report;
use App\Models\ReportAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DownloadCSV extends Action
{
  use InteractsWithQueue, Queueable;

  /**
   * The displayable name of the action.
   *
   * @var string
   */
  public $name = "Unduh ";

  public $file = "/tmp/report.csv";
  public $fileName = "report.csv";

  /**
   * Get the fields available on the action.
   *
   * @return array
   */
  public function fields()
  {
    return [];
  }

  /**
   * Perform the action on the given models.
   *
   * @param  \Laravel\Nova\Fields\ActionFields  $fields
   * @param  \Illuminate\Support\Collection  $models
   * @return mixed
   */
  public function handle(ActionFields $fields, Collection $models)
  {
    $fp = fopen($this->file, 'w');
    $is_header_filled = false;
      
    foreach ($models as $model) {
      // get report and it's answer
      $report = Report::where("id", $model->id)->first();

      // if header not set
      if ($is_header_filled == false) {

        $column_header_1 = [];
        $column_header_2 = [];

        foreach ($report->reportanswers as $answer) {

          array_push($column_header_1, $answer->category);
          array_push($column_header_2, $answer->question);

        }

        array_unshift($column_header_1, "","","");
        array_unshift($column_header_2, "ID","Waktu Submit Data (DD/MM/YYY)","Jam Submit Data (HH:MM)");

        fputcsv($fp, $column_header_1);
        fputcsv($fp, $column_header_2);

        $is_header_filled = true;
      }

      fputcsv($fp, $this->constructAnswer($model, $report->reportanswers));
    }

    fclose($fp);
    Storage::putFileAs('public', new File($this->file), $this->fileName);
    return Action::download(Storage::url($this->fileName), $this->fileName);
  }

  /**
   * construct answer for csv export
   * @param  App\Models\Report $model  
   * @param  Illuminate\Database\Eloquent\Collection[] $answer  
   * @return array
   */
  private function constructAnswer(Report $model,Cs $answers) {
    $date = date_parse($model->created_at);
    $column = [];
    foreach ($answers as $answer) {
      array_push($column, $answer->answer);
    }
    array_unshift(
      $column, 
      $model->id,
      sprintf('%s/%s/%s',$date["day"], $date["month"], $date["year"]),
      sprintf('%s:%s',$date["hour"], $date["minute"]
    ));
    return $column;
  }

}
