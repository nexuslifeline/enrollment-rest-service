<?php

namespace App\Providers;

use App\AcademicRecord;
use App\Observers\AcademicRecordObserver;
use App\Observers\StudentFeeObserver;
use App\Observers\TermObserver;
use App\StudentFee;
use App\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();

        if (Config::get('database.log')) {
            DB::listen(function($query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }
        StudentFee::observe(StudentFeeObserver::class);
        AcademicRecord::observe(AcademicRecordObserver::class);
        Term::observe(TermObserver::class);
    }
}
