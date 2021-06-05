<?php

namespace Tests;

use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\FzCustomer\Models\FzCustomer;
use Illuminate\Database\Schema\SQLiteBuilder;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\SalesRep\Models\SalesRep;

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

  protected function data_to_create_customer(): array
  {
    return [
      'email' => $this->faker->unique()->email,
      'full_name' => $this->faker->unique()->name,
      'phone' => $this->faker->unique()->phoneNumber,
      'gender' => $this->faker->randomElement(['male', 'female']),
      'address' => $this->faker->address,
    ];
  }

  protected function data_to_create_customer_purchase_order(): array
  {
    return [
      'fz_customer_id' => optional(FzCustomer::first())->id ?? FzCustomer::factory()->create()->id,
      'is_swap_purchase' => false,
      'swap_quantity' => $this->faker->randomDigit,
      'total_selling_price' => $this->faker->randomFloat,
      'total_amount_paid' => $this->faker->randomFloat,
      'company_bank_account_id' => CompanyBankAccount::factory()->create()->id,
      'fz_product_type_id' => optional(FzStock::oil()->first())->fz_product_type_id ?? FzStock::factory()->oil()->create()->fz_product_type_id,
      'fz_price_batch_id' => optional(FzStock::oil()->first())->fz_price_batch_id ?? FzStock::factory()->oil()->create()->fz_price_batch_id,
      'swap_product_type_id' => optional(FzStock::gallon()->first())->fz_product_type_id ?? FzStock::factory()->gallon()->create()->fz_product_type_id,
      'purchased_quantity' => 10,
      'payment_type' => 'cash',
    ];
  }

  protected function data_to_create_expense()
  {
    return [
      'amount' => $this->faker->randomFloat(),
      'payment_type' => 'transfer',
      'description' => $this->faker->sentence,
      'expense_date' => $this->faker->dateTimeThisMonth,
    ];
  }

  protected function data_to_create_credit_repayment()
  {
    return [
      'recorded_by' => SalesRep::factory()->create()->id,
      'trans_type' => $this->faker->randomElement(['repayment', 'purchase', 'purchase', 'purchase']),
      'amount' => $this->faker->randomFloat(),
      'trans_date' => $this->faker->dateTimeThisYear,
      'payment_type' => $this->faker->randomElement(['cash', 'bank']),
      'company_bank_account_id' => CompanyBankAccount::factory()->create()->id,
    ];
  }

  protected function data_to_create_direct_swap()
  {
    return [
      'fz_product_type_id' => optional(FzStock::gallon()->first())->fz_product_type_id ?? FzStock::factory()->gallon()->create()->fz_product_type_id,
      'quantity' => 10,
      'customer_paid_via' => $this->faker->randomElement(['cash', 'bank']),
      'amount' => $this->faker->randomFloat(),
      'company_bank_account_id' => CompanyBankAccount::factory()->create()->id,
    ];
  }
}
