<?php

namespace App\Providers;

use App\Term;
use App\StudentFee;
use App\GradingPeriod;
use App\AcademicRecord;
use App\TranscriptRecord;
use App\Observers\TermObserver;
use App\TranscriptRecordSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Observers\StudentFeeObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Observers\GradingPeriodObserver;
use App\Observers\AcademicRecordObserver;
use App\Observers\TranscriptRecordObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Observers\TranscriptRecordSubjectObserver;

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
        TranscriptRecord::observe(TranscriptRecordObserver::class);
        GradingPeriod::observe(GradingPeriodObserver::class);
        TranscriptRecordSubject::observe(TranscriptRecordSubjectObserver::class);
    }
}
