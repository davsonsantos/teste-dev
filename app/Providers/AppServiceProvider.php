<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configModels();
        $this->configCommands();
        $this->configDate();
    }



    /**
     * Configures Eloquent models by disabling the requirement for defining
     * the fillable property and enforcing strict checking to ensure that
     * all accessed properties exist within the model.
     */
    public function configModels(): void
    {
        // --
        // Remove the need of the property fillable on each model
        Model::unguard();

        // --
        // Make sure that all properties being called exists in the model
        Model::shouldBeStrict();
    }

    /**
     * Configures the application to use CarbonImmutable for date and time handling.
     */
    private function configDate(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configures database commands to prohibit execution of destructive statements
     * when the application is running in a production environment.
     */
    private function configCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }
}
