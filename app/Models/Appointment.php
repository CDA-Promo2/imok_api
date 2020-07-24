<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Schema(
 *     schema="Appointment",
 *     type="object",
 *     title="Appointment",
 *     @OA\Property(property="date_start", type="string", format="date"),
 *     @OA\Property(property="note", type="string"),
 *     @OA\Property(property="feedback", type="string"),
 *     @OA\Property(property="id_appointment_types", type="integer"),
 *     @OA\Property(property="id_customers", type="integer"),
 *     @OA\Property(property="id_employees", type="integer"),
 *     @OA\Property(property="customer_firstname", type="string"),
 *     @OA\Property(property="customer_lastname", type="string"),
 *     @OA\Property(property="employee_firstname", type="string"),
 *     @OA\Property(property="employee_lastname", type="string"),
 * )
 */
class Appointment extends Model
{

        public $timestamps = false;

        public $incrementing = false;

        protected $table = 'appointment_view';

        protected $primaryKey = [
                'id_customers',
                'id_employees',
                'date_start'
        ];

        /**
         * Set the keys for a save update query.
         *
         * @param  \Illuminate\Database\Eloquent\Builder  $query
         * @return \Illuminate\Database\Eloquent\Builder
         */
        protected function setKeysForSaveQuery(Builder $query)
        {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
                return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
                $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
        }

        /**
         * Get the primary key value for a save query.
         *
         * @param mixed $keyName
         * @return mixed
         */
        protected function getKeyForSaveQuery($keyName = null)
        {
        if(is_null($keyName)){
                $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
                return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
        }

}
