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
            ['name' => 'price', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'property_tax', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'size', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'carrez_size', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'housing_tax', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'condominium_fees', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'annual_fees', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'gas_emission', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
            ['name' => 'energy_consumption', 'validation' => 'regex:/^(?:d*.d{1,2}|d+)$/', 'required' => false],
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
     * @OA\Get(path="/estates",
     *  summary="Get all estate",
     *  tags={"Estates"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Estates",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Estate")),
     *  )
     * )
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
     * @OA\Get(path="/estates/{id}",
     *  summary="Get estate",
     *  tags={"Estates"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Estate",
     *    @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/Estate"))
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Estate not found",
     *  ),
     * )
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
     * @param $city
     * @param string $minBudget
     * @param string $maxBudget
     * @param string $minSize
     * @param string $maxSize
     * @return JsonResponse
     * @OA\Post(path="/estates/search",
     *  summary="Search estate with matching argument",
     *  tags={"Estates"},
     *  security={{"JWT":{}}},
     *  @OA\RequestBody(
     *    description="Pass any of these arguments",
     *    @OA\JsonContent(
     *       @OA\Property(property="city", type="string", example="amiens"),
     *       @OA\Property(property="minBudget", type="string", example="10000"),
     *       @OA\Property(property="maxBudget", type="string", example="999999"),
     *       @OA\Property(property="minSize", type="string", example="30"),
     *       @OA\Property(property="maxSize", type="string", example="300"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Matching Estates",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Estate")),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="No matching Estate found",
     *  ),
     * )
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
     * @OA\Post(path="/estates",
     *  summary="Create estate",
     *  tags={"Estates"},
     *  security={{"JWT":{}}},
     *  @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"street", "id_cities", "id_customers", "id_estate_types"},
     *       @OA\Property(property="street", type="string"),
     *       @OA\Property(property="id_cities", type="integer"),
     *       @OA\Property(property="id_customers", type="integer"),
     *       @OA\Property(property="id_estate_types", type="integer"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Estate created",
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Estate could not be created",
     *  ),
     * )
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

        }catch(\Exception $e){

            return response()->json([
                'message' => 'Estate could not be created'
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
     * @OA\Put(path="/estates/{id}",
     *  summary="Update an estate",
     *  tags={"Estates"},
     *  security={{"JWT":{}}},
     *  @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="street", type="string"),
     *       @OA\Property(property="id_cities", type="integer"),
     *       @OA\Property(property="id_customers", type="integer"),
     *       @OA\Property(property="id_estate_types", type="integer"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Estate updated",
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Estate not found",
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Estate could not be updated",
     *  ),
     * )
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
