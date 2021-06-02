<?php

namespace Tests;

use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

  public function __construct(?string $name = null, array $data = [], string $dataName = '')
  {
    $this->hotfixSqlite();
    parent::__construct($name, $data, $dataName);
  }

  /**
   * Fix for: BadMethodCallException : SQLite doesn't support dropping foreign keys (you would need to re-create the table).
   */
  public function hotfixSqlite()
  {
    Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
      return new class($connection, $database, $prefix, $config) extends SQLiteConnection
      {
        public function getSchemaBuilder()
        {
          if ($this->schemaGrammar === null) {
            $this->useDefaultSchemaGrammar();
          }
          return new class($this) extends SQLiteBuilder
          {
            protected function createBlueprint($table, \Closure $callback = null)
            {
              return new class($table, $callback) extends Blueprint
              {
                public function dropForeign($index)
                {
                  return new Fluent();
                }
              };
            }
          };
        }
      };
    });
  }

  /**
   * This function recursively converts an array into a standard class object
   *
   * @param array $array
   *
   * @return object
   */
  private function _arrayToObject($array)
  {
    return is_array($array) && !empty($array) ? (object) array_map([__CLASS__, __METHOD__], $array) : (gettype($array) == 'object' && empty((array)$array) ? null : $array);
  }

  protected function getResponseData($rsp)
  {
    return $this->_arrayToObject($rsp->getOriginalContent()->getData()['page']);
  }

  protected function data_to_create_staff(): array
  {
    return [
      'email' => $this->faker->unique()->email,
      'user_name' => $this->faker->unique()->userName,
      'password' => $this->faker->password(12,24),
      'full_name' => $this->faker->unique()->name,
      'phone' => $this->faker->unique()->phoneNumber,
      'gender' => $this->faker->randomElement(['male', 'female']),
      'address' => $this->faker->address,
      'id_url' => $this->faker->imageUrl(),
      'is_active' => false,
      'staff_role_id' => StaffRole::salesRepId(),
    ];
  }
}
