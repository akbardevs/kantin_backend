<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Ampeco\Filters\DateRangeFilter;

// abstract class FilterDateExport extends DateRangeFilter
// {
//     /**
//      * Apply the filter to the given query.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Illuminate\Database\Eloquent\Builder  $query
//      * @param  mixed  $value
//      * @return \Illuminate\Database\Eloquent\Builder
//      */
//     public function apply(Request $request, $query, $value)
//     {
//         $from = Carbon::parse($value[0])->startOfDay();
//         $to = Carbon::parse($value[1])->endOfDay();

//         return $query->whereBetween('created_at', [$from, $to]);
//     }
// }
