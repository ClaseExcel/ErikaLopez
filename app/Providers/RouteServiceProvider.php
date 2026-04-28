<?php

namespace App\Providers;

use App\Http\Controllers\Traits\PermissionsApiTrait;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class RouteServiceProvider extends ServiceProvider
{
    use PermissionsApiTrait;

    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // Obtener permisos de la API
        // $permissionsApi = $this->getPermissionsApi(env('COMPANY_ID')); // ID empresa
        // session(['permissionsApi' => $permissionsApi]);
        // View::share('permissionsApi', $permissionsApi);

        // $this->routes(function () use ($permissionsApi) {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // if (!empty($permissionsApi['estado']) && $permissionsApi['estado'] == '1') {
            // }

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/empresas.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/actividades.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/agendas.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/calendario_actividades.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/cotizaciones.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/empleados.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/gestiones.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/informes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/requerimientos.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/calendariotributario.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/checklist_empresas.php'));
            //informes contables
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/centroscostos.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/clientes.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/modificaciones.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/estadosfinancieros.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/informesgerenciales.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/gestion_humana.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
