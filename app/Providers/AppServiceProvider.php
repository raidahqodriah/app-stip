<?php

namespace App\Providers;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'employee' => Employee::class,
            'student' => Student::class,
        ]);

        Gate::guessPolicyNamesUsing(function (string $modelClass): string {
            return 'App\\Policies\\'.class_basename($modelClass).'Policy';
        });
    }
}
