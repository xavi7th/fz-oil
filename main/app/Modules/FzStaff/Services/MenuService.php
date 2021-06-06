<?php

namespace App\Modules\FzStaff\Services;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as RouteObject;

class MenuService
{
  private $user;
  private $is_heirarchical = false;

  public function setUser(User $user): self
  {
    $this->user = $user;
    return $this;
  }

  public function setHeirarchical(bool $is_heirarchical): self
  {
    $this->is_heirarchical = $is_heirarchical;
    return $this;
  }

  public function getRoutes(): array
  {
    if (!$this->user) {
      return $this->getPublicRoutes();
    } else {
      return $this->getUserRoutes();
    }
  }

  public function getAllRoutes(): array
  {
    if (!$this->user) {
      return $this->getPublicRoutes();
    } else {
      return $this->getAllUserRoutes();
    }
  }

  public function getRoutesUsingMiddleware(): array
  {
    if (!$this->user) {
      return $this->getPublicRoutes();
    } else {
      return $this->getUserRoutesViaMiddleware();
    }
  }

  private function getUserRoutes(): array
  {
    $routes = collect(Route::getRoutes()->getRoutesByMethod()['GET'])
      ->filter(function ($value, $key) {
        return isset($value->defaults['menu']) && !$value->defaults['menu']['nav_skip'] && Gate::allows(Str::before($value->defaults['menu']['authorization'], ','), Str::after($value->defaults['menu']['authorization'], ','));
      })
      ->map(function (RouteObject $route) {
        return (object)[
          'uri' => $route->uri(),
          'name' => $route->getName(),
          'nav_skip' => $route->defaults['menu']['nav_skip'] ?? false,
          'icon' => $route->defaults['menu']['icon'] ?? null,
          'menu_name' => $route->defaults['menu']['name']
        ];
      });

    /**
     * ? Set the Dashboard as the first menu item
     */
    $user_slug = Str::of($this->user->getType())->snake()->replace('_', '-')->__toString();
    $routes->prepend($routes->pull($user_slug), $user_slug);

    return $this->is_heirarchical ? $this->getHeirachicalRoutes($routes) : $routes->values()->toArray();
  }

  private function getUserRoutesViaMiddleware(): array
  {
    $routes = collect(Route::getRoutes()->getRoutesByMethod()['GET'])
      ->filter(function ($value, $key) {
        return isset($value->defaults['menu']) && !$value->defaults['menu']['nav_skip'] && Gate::allows(Str::of(collect($value->middleware())->first(fn ($v) => Str::contains($v, 'can:')))->after('can:')->before(',')->__toString(), Str::of(collect($value->middleware())->first(fn ($v) => Str::contains($v, 'can:')))->after('can:')->after(',')->__toString());
      })
      ->map(function (RouteObject $route) {
        return (object)[
          'uri' => $route->uri(),
          'name' => $route->getName(),
          'nav_skip' => $route->defaults['menu']['nav_skip'] ?? false,
          'icon' => $route->defaults['menu']['icon'] ?? null,
          'menu_name' => $route->defaults['menu']['name']
        ];
      });

    return $this->is_heirarchical ? $this->getHeirachicalRoutes($routes) : $routes->values()->toArray();
  }

  private function getAllUserRoutes(): array
  {
    $routes = collect(Route::getRoutes()->getRoutesByName())
      ->filter(function ($value, $key) {
        return isset($value->defaults['menu']) && !$value->defaults['menu']['nav_skip'] && Gate::allows(Str::before($value->defaults['menu']['authorization'], ','), Str::after($value->defaults['menu']['authorization'], ','));
      })
      ->map(function (RouteObject $route) {
        return (object)[
          'uri' => $route->uri(),
          'method' => $route->methods()[0],
          'name' => $route->getName(),
          'nav_skip' => $route->defaults['menu']['nav_skip'] ?? false,
          'icon' => $route->defaults['menu']['icon'] ?? null,
          'menu_name' => $route->defaults['menu']['name']
        ];
      });

    return $this->is_heirarchical ? $this->getHeirachicalRoutes($routes) : $routes->values()->toArray();
  }

  private function getHeirachicalRoutes(&$routes): array
  {
    $tmp = $routes;
    $routes = [];
    /**
     * * Group them based on the route url prefix into arrays
     * eg all company-bank-accounts/* get lumped into one array
     */
    $tmp->map(function ($route) use (&$routes) {
      return $routes[Str::of($route->uri)->before('/')->replace('-', ' ')->title()->__toString()][] = $route;
    });
    return $routes;
  }
}
