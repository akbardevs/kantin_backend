<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;

use App\Nova\Metrics\TotalUser;


class ReportInsights extends Dashboard
{
  /**
   * The logical group associated with the resource.
   *
   * @var string
   */
  public static $group = '<i class="hidden">1</i> Survei';
  /**
   * Get the displayable name of the dashboard.
   *
   * @return string
   */
  public static function label()
  {
    return "Dashboard";
  }
  /**
   * Get the cards for the dashboard.
   *
   * @return array
   */
  public function cards()
  {
    return [
      //
      new TotalUser(),
      
    ];
  }

  /**
   * Get the URI key for the dashboard.
   *
   * @return string
   */
  public static function uriKey()
  {
    return "dashboard";
  }
}
