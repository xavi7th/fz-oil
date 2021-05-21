<?php
namespace App\Modules\SuperAdmin\Database\Seeders;

use Illuminate\Database\Seeder;

class ProductCategoriesTableSeeder extends Seeder
{

  /**
   * Auto generated seed file
   *
   * @return void
   */
  public function run()
  {


    \DB::table('product_categories')->delete();

    \DB::table('product_categories')->insert(array(
      0 =>
      array(
        'name' => 'desktops',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      1 =>
      array(
        'name' => 'phones',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      2 =>
      array(
        'name' => 'laptops',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      3 =>
      array(
        'name' => 'tablets',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      4 =>
      array(
        'name' => 'watches',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      5 =>
      array(
        'name' => 'consoles',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
      6 =>
      array(
        'name' => 'accessories',
        'img_url' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => NULL,
      ),
    ));
  }
}
