<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class City
 * @package App\Models
 * @OA\Schema(
 *     schema="City",
 *     type="object",
 *     title="City",
 *     @OA\Property(property="department_code", type="string"),
 *     @OA\Property(property="insee_code", type="string"),
 *     @OA\Property(property="zip_code", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="gps_lat", type="number", format="float"),
 *     @OA\Property(property="gps_lng", type="number", format="float"),
 * )
 */
class City extends Model
{

}
