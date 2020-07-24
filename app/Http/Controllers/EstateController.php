<?php

namespace App\Http\Controllers;


use App\Models\Estate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * REPRESENTS ESTATE COLUMN IN DATABASE
 *
 * Class EstateController
 * @package App\Http\Controllers
 */
class EstateController extends Controller
{

    /**
     * EstateController constructor.
     * FIELDS CONTAINS FIELD NAMES, VALIDATION RULES AND REQUIRED BOOL
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->fields = [
            ['name' => 'street', 'validation' => 'string', 'required' => true],
            ['name' => 'complement', 'validation' => 'string', 'required' => false],
            ['name' => 'description', 'validation' => 'string', 'required' => false],
            ['name' => 'id_cities', 'validation' => 'integer|exists:cities,id', 'required' => true],
            ['name' => 'id_customers', 'validation' => 'integer|exists:customers,id', 'required' => true],
            ['name' => 'id_build_Dates', 'validation' => 'integer|exists:build_dates,id', 'required' => false],
            ['name' => 'id_outside_conditions', 'validation' => 'integer||exists:outside_conditions,id', 'required' => false],
            ['name' => 'id_heating_types', 'validation' => 'integer|exists:heating_types,id', 'required' => false],
            ['name' => 'id_districts', 'validation' => 'integer|exists:districts,id', 'required' => false],
            ['name' => 'id_expositions', 'validation' => 'integer|exists:expositions,id', 'required' => false],
            ['name' => 'id_estate_types', 'validation' => 'integer||exists:estate_types,id', 'required' => true],
            ['name' => 'price', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'property_tax', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'size', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'carrez_size', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'housing_tax', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'condominium_fees', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'annual_fees', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'gas_emission', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'energy_consumption', 'validation' => 'regex:/^\d+(\.\d{1,2})?$/', 'required' => false],
            ['name' => 'rooms_numbers', 'validation' => 'integer', 'required' => false],
            ['name' => 'bedroom_numbers', 'validation' => 'integer', 'required' => false],
            ['name' => 'floor_number', 'validation' => 'integer', 'required' => false],
            ['name' => 'condominium', 'validation' => 'boolean', 'required' => false],
            ['name' => 'floor', 'validation' => 'boolean', 'required' => false],
            ['name' => 'joint_ownership', 'validation' => 'boolean', 'required' => false],
            ['name' => 'renovation', 'validation' => 'boolean', 'required' => false],
        ];
    }

    /**
     * GET ALL ESTATES
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'estates' => Estate::all()
        ], 200);
    }


    /**
     * GET AN ESTATE BY ITS ID
     * @param $id
     * @return JsonResponse
     */
    public function getOneById($id)
    {
        try{
            $estate = Estate::findOrFail($id);
            return response()->json([
                'estate' => $estate
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'This estate does not exist'
            ],404);
        }
    }

    /**
     * GET AN ESTATE WHERE LIKE IN ATTR
     * @param Request $request
     * @return JsonResponse
     */
    public function getWhere(Request $request)
    {
        try{
            $estate = Estate::where('city', 'like', '%' . $request->input('city') . '%')->get();
            if($request->input('maxPrice')) {
                $estate = Estate::whereBetween('price', [$request->input('minPrice') ?? 0, $request->input('maxPrice')])->get();
            }
            if($request->input('maxSize')) {
                $estate = Estate::whereBetween('size', [$request->input('minSize') ?? 0, $request->input('maxSize')])->get();
            }

            return response()->json([
                'estate' => $estate,
            ],200);
        }
        catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ],404);
        }
    }

    /**
     * CREATE A NEW ESTATE
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request){

        $rules = [];

        foreach($this->fields as $field){
            $required = $field['required'] === true ? '|required' : '' ;
            $rules[$field['name']] =  $field['validation'] . $required;
        }
        $this->validate($request, $rules);

        try{
            $estate = new Estate();
            foreach($this->fields as $field){
                $estate->{$field['name']} = $request->input($field['name']);
            }
            $estate->save();

            return response()->json([
                'message' => 'CREATED',
                'estate' => $estate
            ],201);

        } catch(\Exception $e) {
            return response()->json([
                'message' => $e
//                'message' => 'Estate could not be created'
            ],409);
        }
    }


    /**
     * UPDATE AN ESTATE
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request){

        try{
            $estate = Estate::findOrFail($id);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'This estate does not exist'
            ],404);
        }

        $rules = [];
        foreach($this->fields as $field){
            $rules[$field['name']] =  $field['validation'];
        }
        $this->validate($request, $rules);

        try{

            $estate = new Estate();
            foreach($this->fields as $field){
                $estate->{$field['name']} = $request->input($field['name']) ?? $estate->{$field['name']};
            }
            $estate->save();

            return response()->json([
                'message' => 'UPDATED',
                'estate' => $estate
            ],201);

        }catch(\Exception $e){

            return response()->json([
                'message' => 'Estate could not be updated'
            ],409);
        }


    }

}
