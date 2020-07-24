<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Estate
 * @package App\Models
 * @OA\Schema(
 *     schema="Estate",
 *     type="object",
 *     title="Estate",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="rooms", type="integer"),
 *     @OA\Property(property="facilities", type="object"),
 *     @OA\Property(property="street", type="string"),
 *     @OA\Property(property="complement", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="room_numbers", type="integer"),
 *     @OA\Property(property="bedroom_numbers", type="string"),
 *     @OA\Property(property="joint-ownership", type="boolean"),
 *     @OA\Property(property="annual_fees", type="number", format="double"),
 *     @OA\Property(property="price", type="number", format="double"),
 *     @OA\Property(property="rent", type="number", format="double"),
 *     @OA\Property(property="condominium", type="boolean"),
 *     @OA\Property(property="condominium_fees", type="number", format="double"),
 *     @OA\Property(property="property_tax", type="number", format="double"),
 *     @OA\Property(property="housing_tax", type="number", format="double"),
 *     @OA\Property(property="energy_consumption", type="number", format="double"),
 *     @OA\Property(property="gas_emission", type="number", format="double"),
 *     @OA\Property(property="size", type="number", format="double"),
 *     @OA\Property(property="carrez_size", type="number", format="double"),
 *     @OA\Property(property="floor", type="integer"),
 *     @OA\Property(property="floor_number", type="integer"),
 *     @OA\Property(property="renovation", type="boolean"),
 *     @OA\Property(property="id_customers", type="integer"),
 *     @OA\Property(property="id_build_Dates", type="integer"),
 *     @OA\Property(property="id_outside_conditions", type="integer"),
 *     @OA\Property(property="id_heating_types", type="integer"),
 *     @OA\Property(property="id_districts", type="integer"),
 *     @OA\Property(property="id_expositions", type="integer"),
 *     @OA\Property(property="id_cities", type="integer"),
 *     @OA\Property(property="id_estate_types", type="integer"),
 *     @OA\Property(property="period", type="string"),
 *     @OA\Property(property="zip_code", type="string"),
 *     @OA\Property(property="city", type="string"),
 *     @OA\Property(property="city_lat", type="number", format="float"),
 *     @OA\Property(property="cities_lng", type="number", format="float"),
 *     @OA\Property(property="owner_firsname", type="string"),
 *     @OA\Property(property="owner_lastname", type="string"),
 *     @OA\Property(property="exposition", type="string"),
 *     @OA\Property(property="heating_type", type="string"),
 *     @OA\Property(property="notes", type="string"),
 *     @OA\Property(property="outside_condition", type="string"),
 *     @OA\Property(property="estate_type", type="string"),
 * )
 */
class Estate extends Model
{
    public $timestamps = false;

    protected $table = 'estate_view';
}
