<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\MediaCategory;
use App\Models\Company;
use \stdClass;

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
        Schema::defaultStringLength(191);
        View::share('default_vat', 11);
        $bank_account = new stdClass();
        $bank_account->number = '040 420 0000';
        $bank_account->name = 'VISUAL MANDIRI CV';
        $bank_account->bank = 'BCA Cabang Hasanudin, Denpasar - Bali';
        View::share('bank_account', $bank_account);
        View::share('categories', MediaCategory::all());
        View::share('company', Company::where('id', '=' , 3)->firstOrFail());
    }
}
