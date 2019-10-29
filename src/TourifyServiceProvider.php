<?php
namespace Zusamarehan\Tourify;
use Zusamarehan\Tourify\Model\Tourifies;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TourifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__.'/resources/views/productTour/', 'Tourify');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/assets/css/hopscotch.css' => public_path('vendor/zusamarehan/tourify/css/hopscotch.css'),
            __DIR__.'/assets/js/hopscotch.js' => public_path('vendor/zusamarehan/tourify/js/hopscotch.js'),
            __DIR__.'/assets/img/' => public_path('vendor/zusamarehan/tourify/img/'),
        ], 'tourifyAssets');

        $currentRouteName = self::getRouteName();

        if($currentRouteName){

            $currentTourData = Tourifies::where('route' , '=', $currentRouteName)->first();
            if($currentTourData) {
                Blade::directive('tour', function () use($currentTourData) {
                    $temp = $currentTourData->file_name;
                    return '<script defer src="/storage/tourify/'.$temp.'"> </script>';
                });
            }
        }
    }

    /**
     * @param Tourifies $tour
     *
     * @return string
     */
    public function tourDirective (Tourifies $tour) {
        $temp = $tour->file_name;
        return '<script defer src="/storage/'.$temp.'"> </script>';
    }

    /**
     * @return string
     */
    public function getRouteName () {
        $routeName = '';
        $currentURI = $this->app->request->getRequestUri();
        if($currentURI[0] === '/' && strlen($currentURI) > 1){
            $currentURI = substr($currentURI, 1);
        }
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if($route->methods[0] === 'GET' && $currentURI === $route->uri && array_key_exists('as', $action)) {

                $routeName =  $action['as'];
            }
        }
        return $routeName;
    }
}
