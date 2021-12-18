<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Nova\Nova;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\TotalUser;

use App\Nova\Metrics\TotalReport;
use App\Models\UserActivationStatus;
use App\Nova\Metrics\TotalUserAdmin;
use Illuminate\Support\Facades\Gate;
use App\Nova\Metrics\UserGenderReport;
use App\Nova\Dashboards\ReportInsights;
use App\Nova\Metrics\TotalKomentar;
use App\Nova\Metrics\TotalPostInformasi;
use App\Nova\Metrics\TotalPostPromosi;
use App\Nova\Metrics\TotalPostTanyaJawab;
use App\Nova\Metrics\UserEyesightReport;
use App\Nova\Metrics\UserLocationReport;
use App\Nova\Metrics\UserUsingMaskReport;
use App\Nova\Metrics\UserCommunicationReport;
use App\Nova\Metrics\UserVaccineReadinessReport;
use Laravel\Nova\NovaApplicationServiceProvider;
use SimonHamp\LaravelNovaCsvImport\LaravelNovaCsvImport;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    
  }

  /**
   * Register the Nova routes.
   *
   * @return void
   */
  protected function routes()
  {
    Nova::routes()
      ->withAuthenticationRoutes()
      ->withPasswordResetRoutes()
      ->register();
  }

  /**
   * Register the Nova gate.
   *
   * This gate determines who can access Nova in non-local environments.
   *
   * @return void
   */
  protected function gate()
  {
    Gate::define("viewNova", function ($user) {
      if ($user->activation_status !== UserActivationStatus::ACTIVE) {
        return false;
      }

      return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN]);
    });
  }

  /**
   * Get the cards that should be displayed on the default Nova dashboard.
   *
   * @return array
   */
  protected function cards()
  {
    return [
      // new Help,
      // new TotalUser(),
      // new TotalUserAdmin(),
      // new TotalKomentar(),
      // new TotalPostInformasi(),
      // new TotalPostTanyaJawab(),
      // new TotalPostPromosi(),
      
    ];
  }

  /**
   * Get the extra dashboards that should be displayed on the Nova dashboard.
   *
   * @return array
   */
  protected function dashboards()
  {
    // return [new ReportInsights];
    return [];
  }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
          
        ];
    }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
