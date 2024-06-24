<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;
// use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteServiceProvider;


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
    public function boot(Factory $view): void
    {
        $this->registerViewComposers($view);
        // $this->loadTranslations();
    }
    
    public function lte(Factory $view): void{
        $view->composer('layouts.page', AdminLteComposer::class);
    }

    private function pages(Factory $view)
    {
        $this->lte($view);
        return $this;
    }

    private function registerViewComposers(Factory $view)
    {
        // $view->composer('adminlte::page', AdminLteComposer::class);
        $view->composer('layouts.page', AdminLteComposer::class);
    }

 
//     protected $pkgPrefix = 'idep';
//     private function loadTranslations():void
//     {
//         $translationsPath = $this->packagePaths('resources/lang');
//         $this->loadTranslationsFrom($translationsPath,$this->pkgPrefix);
//     }

//     public function packagePaths($path){
//         $path = __DIR__."../../$path";
//    }
}
