<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
                ->sortable(),
            BelongsTo::make(__('Kasir'), 'kasir', 'App\Nova\Kasirs')->sortable(),
            Text::make('Nama', 'nama')
                ->sortable()
                ->rules('required'),
            Number::make('Total', 'total')
                ->sortable()
                ->rules('required'),
            Text::make('Produk(Jumlah)', 'list_produk')
                ->sortable()
                ->rules('required')->displayUsing(function ($text) {
                    $ret = [];
                    $i = 0;
                    $dataReturn = '';
                    $data = json_decode($text, true);
                    if ($data != null) {
                        foreach ($data as $s) {
                            $dataReturn .= $s['nama'] . "(" . $s['qty'] . ")" . ', ';
                        }
                        return $dataReturn;
                    }
                    // return '1615822571179.png';
                }),
            Text::make('Pembayaran', 'metode')
                ->sortable()
                ->rules('required'),
            Text::make('Akun', 'nohp')
                ->sortable()
                ->rules('required'),
                // DateTime::make('Created At')
                // ->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
