<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class Customer
 * @package App\Models
 * @OA\Schema(
 *     schema="Customer",
 *     type="object",
 *     title="Customer",
 *     @OA\Property(property="firstname", type="string"),
 *     @OA\Property(property="lastname", type="string"),
 *     @OA\Property(property="street", type="string"),
 *     @OA\Property(property="complement", type="string"),
 *     @OA\Property(property="phone", type="string"),
 *     @OA\Property(property="mail", type="email"),
 *     @OA\Property(property="id_marital_status", type="integer"),
 *     @OA\Property(property="id_cities", type="integer"),
 *     @OA\Property(property="civility", type="integer"),
 *     @OA\Property(property="birthdate", type="string", format="date"),
 *     @OA\Property(property="date_register", type="string", format="date"),
 *     @OA\Property(property="zip_code", type="string"),
 *     @OA\Property(property="city", type="string"),
 *     @OA\Property(property="marital_status", type="string"),
 * )
 */
class Customer extends Model
{
        public $timestamps = false;
        protected $table = 'customer_view';
}
