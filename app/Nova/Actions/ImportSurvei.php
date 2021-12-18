<?php

namespace App\Nova\Actions;

use App\Imports\Survei;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class ImportSurvei extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public $onlyAnIndex = true;
    public function name()
    {
        return __('Import Data');
    }

    public function uriKey() : string
    {
        return 'import-survei';
    }
    public function handle(ActionFields $fields, Collection $models)
    {
        Excel::import(new Survei, $fields->file); 
        return Action::message('Done');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('File')->rules('required'),
        ];
    }
}
