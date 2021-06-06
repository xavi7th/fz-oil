<?php

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FileNotFoundException as FileDownloadException;
use Illuminate\Contracts\Filesystem\FileNotFoundException as FileGetException;

if (!function_exists('to_naira')) {
  /**
   * convert a number to naira with the naira symbol
   *
   * @param float $amount The amount to convert
   * @return string
   * @throws Exception when the amount supplied is not a number
   **/

  function to_naira($amount): string
  {
    if (!is_numeric($amount)) {
      throw new Exception("can only convert numbers to naira", 500);
    }
    return '₦' . number_format($amount, 2);
  }
}

if (!function_exists('generate_422_error')) {
  /**
   * Generate a 422 error in a format that axios and sweetalert 2 can display it
   *
   * @param  array|string  $errors An array of errors to display
   * @return Response
   */
  function generate_422_error($errors)
  {
    if (request()->isApi()) return response()->json(['error' => 'form validation error', 'message' => $errors], 422);
    throw ValidationException::withMessages(['message' => $errors])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
  }
}

if (!function_exists('get_11_digit_nigerian_number')) {
  /**
   * convert a number from international standard to 11 digit number
   *
   * @param float $number The number to convert
   *
   * @return string
   **/

  function get_11_digit_nigerian_number(string $number): string
  {
    return '0' . mb_substr(preg_replace('/(\D+)/i', '', $number), -10);
  }
}

if (!function_exists('get_related_routes')) {
  /**
   * Generate an array of all routes unnder the given namespace that correspond to the provided methods
   *
   * @param  string $namespace
   * @param  array $methods
   * @param  bool $isHeirarchical Determines whether to return an array that can be used for drop down mwnus
   * @return array
   *
   * Route::get('', [self::class, 'getProductAccessories'])->name('accessories')->defaults('ex', __e('ss,a,ac,d,sk,s,q,w', 'codesandbox'))->middleware('auth:super_admin,stock_keeper,sales_rep,quality_control,admin,dispatch_admin,web_admin,accountant');
   */
  function get_related_routes(string $namespace, array $methods, bool $isHeirarchical = false)
  {
    if (!function_exists('getHeirachicalRoutes')) {
      function getHeirachicalRoutes(&$routes)
      {
        $tmp = $routes;
        $routes = [];
        /**
        * * Group them based on the route names into arrays
        * eg all products.* get lumped into one array
        */
        collect($tmp)->map(function ($route, $key) use (&$routes) {
          return $routes[Str::title(Str::of($key)->explode('.')->take(-2)->first())][] = $route;
          // return $routes[Str::of($key)->after('.')->before('.')->title()->__toString()][] = $route;
        });
        return $routes;
      }
    }

    // dd(
    //   collect([
    //     request()->user()->getDashboardRoute() => collect(tap(\Illuminate\Support\Facades\Route::getRoutes()->getByName(request()->user()->getDashboardRoute()),
    //     fn ($instance) => $instance->defaults['ex']['navSkip'] = false))])->merge(collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName()))
    //      ->filter(function ($value, $key) use ($methods, $namespace) {
    //       return (\Str::startsWith($key, $namespace) || \Str::startsWith($key, 'multiaccess')) && \Str::of(implode('|', $value->methods()))->contains($methods);
    //       })
    //       ->map(function (\Illuminate\Routing\Route $route) use ($namespace) {
    //         return (object)[
    //           // 'uri' => $route->uri(),
    //           'name' => $route->getName(),
    //           'nav_skip' => $route->defaults['ex']['navSkip'] ?? false,
    //           'icon' => $route->defaults['ex']['icon'] ?? null,
    //           'permitted_users' => $route->defaults['ex']['permittedUsers'] ?? null,
    //           'menu_name' => \Str::of($route->getName())->afterLast('.')->replaceMatches('/[^A-Za-z0-9]++/', ' ')->after($namespace)->title()->trim()->__toString()
    //         ];
    //       })
    //   );

    /**
     * * Get the dashboard route seperately if there is a logged in user
     * * Then get all other routes and merge it to that.
     * ? This is so that the user's dashiard route is always the first on the list
     * ! We have to add nav_skip to dashboard routes to prevent duplication of dashboard route in the generated route list
     */
    $routes = rescue(
      fn () => collect([
        request()->user()->getDashboardRoute() => collect(tap(\Illuminate\Support\Facades\Route::getRoutes()->getByName(request()->user()->getDashboardRoute()), fn ($instance) => $instance->defaults['ex']['navSkip'] = false))
      ])->merge(collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName())),
      fn () => collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName()),
      false
    )
    /**
     * * Remove any route that does not start with the user's type or multiaccess or generic
     * * Remove any route that does not start with the specified method
     */
      ->filter(function ($value, $key) use ($methods, $namespace) {
      return (\Str::startsWith($key, $namespace) || \Str::startsWith($key, 'multiaccess') || \Str::startsWith($key, 'generic')) && \Str::of(implode('|', $value->methods()))->contains($methods);
      })
      /**
     * * Format the data
     */
      ->map(function (\Illuminate\Routing\Route $route) use ($namespace) {
      return (object)[
        // 'uri' => $route->uri(),
        'name' => $route->getName(),
        'nav_skip' => $route->defaults['ex']['navSkip'] ?? false,
        'icon' => $route->defaults['ex']['icon'] ?? null,
        'permitted_users' => $route->defaults['ex']['permittedUsers'] ?? null,
        'menu_name' => \Str::of($route->getName())->afterLast('.')->replaceMatches('/[^A-Za-z0-9]++/', ' ')->after($namespace)->title()->trim()->__toString()
      ];
      })
      /**
     * Remove any routes that the user has no access to
     */
      ->reject(function ($val) {
      $allUserTypes = [
        'a' => 'Admin',
        'ss' => 'SuperAdmin',
        'ac' => 'Accountant',
        'd' => 'DispatchAdmin',
        'q' => 'QualityControl',
        's' => 'SalesRep',
        'sk' => 'StockKeeper',
        'w' => 'WebAdmin',
      ];
      $permittedUser = false;
      Str::of($val->permitted_users)->explode(',')->each(function ($v) use ($allUserTypes, &$permittedUser) {
        $permittedType = $allUserTypes[$v] ?? null;
        if ((request()->user() && request()->user()->getType() === $permittedType)) {
          $permittedUser = true;
          return false;
        }
      });
      return $val->nav_skip || !$permittedUser;
    })
    /**
     * Convert to array
     */
      ->toArray();

    // dd($routes);
    // dd(collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName()));
    /**
     * If a heirichical structure is required eg for drop down menus, format the data to multi-level array
     */
    return $isHeirarchical ? getHeirachicalRoutes($routes) : $routes;
  }
}

if (!function_exists('compress_image_upload')) {

  /**
   * Compress uploaded image files for better optimisation
   *
   * Uses the Intervention library to compress files into the specified size at 85% quality
   *  and optionally create thumbnail images and saves them in the paths provided
   * for the image and the thumbnail. The aspect ration can optionally be maintained
   * returns an array of file names.
   *
   * @param string $key The index name of the file field in the request object
   * @param string $save_path The path to save the compressed image
   * @param string $thumb_path The optional path to save the thumbnail. If provided, thumbnails will be generated
   * @param int $size The size to compress the image into. Defaults to 1400px
   * @param bool $constrain_aspect_ration Boolean value indication whether to constrain the aspect ratio on compression
   *
   * @package \Intervention\Image\Facades\Image
   *
   * composer require intervention/image
   *
   * compress_image_upload('img', 'product_models_images/', 'product_models_images/thumbs/', 800, true, 50)['img_url'],
   *
   * @return array
   **/

  function compress_image_upload(string $key, string $save_path, ?string $thumb_path = null, ?int $size = 1400, ?bool $constrain_aspect_ratio = true, ?int $thumb_size = 200)
  {
    // dd(public_path(Storage::url($save_path)));

    Storage::makeDirectory('public/' . $save_path, 0777);
    Storage::makeDirectory('public/' . $thumb_path, 0777);

    // if ($thumb_path && !File::isDirectory(Storage::url($thumb_path))) {
    //   File::makeDirectory(Storage::url($thumb_path), 0755);
    // }

    $image = Image::make(request()->file($key)->getRealPath());

    if ($constrain_aspect_ratio) {
      $image->resize($size, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })->save(public_path(Storage::url($save_path)) . request()->file($key)->hashName(), 85);

      $url = Storage::url($save_path) . request()->file($key)->hashName();

      if ($thumb_path) {
        $image->resize(null, $thumb_size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })->save(public_path(Storage::url($thumb_path)) . request()->file($key)->hashName(), 70);

        $thumb_url = Storage::url($thumb_path) . request()->file($key)->hashName();

        return ['img_url' => $url, 'thumb_url' => $thumb_url];
      }
      return ['img_url' => $url];
    } else {
      $image->resize($size)->save(public_path(Storage::url($save_path)) . request()->file($key)->hashName(), 85);
      $url = Storage::url($save_path) . request()->file($key)->hashName();

      if ($thumb_path) {
        $image->resize($thumb_size)->save(public_path(Storage::url($thumb_path)) . request()->file($key)->hashName(), 70);
        $thumb_url = Storage::url($thumb_path) . request()->file($key)->hashName();

        return ['img_url' => $url, 'thumb_url' => $thumb_url];
      }

      return ['img_url' => $url];
    }
  }
}

/**
 * Return the data for the extras defaults
 *
 * @param bool $navSkip
 * @param string $icon
 *
 * @return array
 */
function __e($name, $authorization, $icon = null, $nav_skip = false)
{
  return compact(['name', 'icon', 'nav_skip', 'authorization']);
}
