<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * DEFAULT FACTORY
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

/**
 * EMPLOYEES FACTORY
 */
$factory->define(\App\Models\Employee::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->name,
        'lastname' => $faker->name,
        'phone' => '0612345678',
        'mail' => $faker->email,
        'password' => $faker->password,
        'id_roles' => 2,
        'first_connection' => 0,
        'active' => 1,
    ];
});

/**
 * CUSTOMERS FACTORY
 */
$factory->define(\App\Models\Customer::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'street' => $faker->streetAddress,
        'complement' => $faker->sentence(4),
        'phone' => '0612345678',
        'mail' => $faker->email,
        'id_marital_status' => $faker->numberBetween(1,6),
        'id_cities'=> $faker->numberBetween(1, 35853),
        'civility' => $faker->numberBetween(0, 1),
        'birthdate' => $faker->date('Y-m-d'),
        'date_register' => $faker->date('Y-m-d','now'),
    ];
});

/**
 * APPOINTMENTS FACTORY
 */
$factory->define(\App\Models\Appointment::class, function (Faker\Generator $faker) {
    return [
        'date_start' => '2099-10-02 12:30:00',
        'note' => $faker->paragraph(),
        'feedback' => $faker->paragraph(),
        'id_appointment_types' => $faker->numberBetween(1, 3),
        'id_customers' => 5,
        'id_employees' => 37,
    ];
});

/**
 * ESTATES FACTORY
 */
$factory->define(\App\Models\Estate::class, function (Faker\Generator $faker) {
    return [
        'street' => $faker->streetAddress,
        'complement' => $faker->sentence(4),
        'description' => $faker->sentence,
        'rooms_numbers' => $faker->numberBetween(1,10),
        'bedroom_numbers' => $faker->numberBetween(1,3),
        'joint_ownership' => $faker->numberBetween(0,1),
        'annual_fees'=> $faker->randomFloat(2,500,2000),
        'price' => $faker->numberBetween(50000, 500000),
        'condominium' => $faker->numberBetween(0,1),
        'condominium_fees' => $faker->numberBetween(200, 1200),
        'property_tax' => $faker->numberBetween(200, 1200),
        'housing_tax' => $faker->numberBetween(200, 1200),
        'energy_consumption' => $faker->numberBetween(50, 150),
        'gas_emission' => $faker->numberBetween(15, 50),
        'size' => $faker->numberBetween(20, 120),
        'carrez_size' => $faker->numberBetween(20, 120),
        'floor' => $faker->numberBetween(0, 1),
        'floor_number' => $faker->numberBetween(0, 4),
        'renovation' => $faker->numberBetween(0, 1),
        'id_customers' => 1,
        'id_build_Dates' => $faker->numberBetween(1, 7),
        'id_outside_conditions' => $faker->numberBetween(1, 4),
        'id_heating_types' => $faker->numberBetween(1, 9),
        'id_districts' => $faker->numberBetween(1, 18),
        'id_expositions' => $faker->numberBetween(1, 8),
        'id_cities' => $faker->numberBetween(1, 35853),
        'id_estate_types' => $faker->numberBetween(1, 10),
        'rooms' => '[{"id":0,"room_types":"Entrée","room_size":15,"room_carrezSize":13.7,"windows_types":"Pas de fenètre","wall_coverings":"Papier peint","ground_coverings":"Carrelage"},{"id":1,"room_types":"Chambre","room_size":22.4,"room_carrezSize":22.4,"windows_types":"Double vitrage","wall_coverings":"Peinture","ground_coverings":"Moquette"}]',
        'facilities' => 'École,Commerces,Gare',
    ];
});
