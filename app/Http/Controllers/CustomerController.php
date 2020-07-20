<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * REPRESENTS CUSTOMER COLUMN IN DATABASE
 *
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{

    /**
     * CustomerController constructor.
     * FIELDS CONTAINS FIELD NAMES, VALIDATION RULES AND REQUIRED BOOL
     */
    public function __construct()
    {

        $this->middleware('auth');
        $this->fields = [
            ['name' => 'firstname', 'validation' => 'string', 'required' => true],
            ['name' => 'lastname', 'validation' => 'string', 'required' => true],
            ['name' => 'street', 'validation' => 'string', 'required' => true],
            ['name' => 'complement', 'validation' => 'string', 'required' => false],
            ['name' => 'phone', 'validation' => 'string', 'required' => true],
            ['name' => 'mail', 'validation' => 'email', 'required' => true],
            ['name' => 'id_marital_status', 'validation' => 'integer|exists:marital_status,id', 'required' => true],
            ['name' => 'id_cities', 'validation' => 'integer|exists:cities,id', 'required' => true],
            ['name' => 'civility', 'validation' => 'boolean', 'required' => true],
            ['name' => 'birthdate', 'validation' => 'date', 'required' => true],
        ];
    }

    /**
     * GET ALL CUSTOMERS
     * @return JsonResponse
     * @OA\Get(path="/customers",
     *  summary="Get all customers",
     *  tags={"Customers"},
     *  @OA\Response(
     *    response=200,
     *    description="Customers",
     *  )
     * )
     */
    public function getAll()
    {
        try {
            return response()->json([
                'customers' => Customer::all()
            ], 200);
        }catch (\Exception $e) {
            var_dump($e);
            die;
        }
    }

    /**
     * GET A CUSTOMER BY ITS ID
     * @param $id
     * @return JsonResponse
     * @OA\Get(path="/customers/{id}",
     *  summary="Get a customer",
     *  tags={"Customers"},
     *  @OA\Response(
     *    response=404,
     *    description="Customer does not exist",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Customer",
     *  )
     * )
     */
    public function getOneById($id)
    {
        try{
            $customer = Customer::findOrFail($id);
            return response()->json([
                'customer' => $customer
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'This user does not exist'
            ],404);
        }
    }

    /**
     * GET A CUSTOMER WHERE LIKE IN NAME OR MAIL
     * @param $arg
     * @return JsonResponse
     * @OA\Get(path="/customers/find/{argument}",
     *  summary="Get a customer where argument can be found in name or mail",
     *  tags={"Customers"},
     *  @OA\Response(
     *    response=404,
     *    description="No customer found",
     *    ),
     *  @OA\Response(
     *    response=200,
     *    description="Matching customer(s)",
     *  )
     * )
     */
    public function getWhere($arg)
    {
        try{
            $customer = Customer::where('firstname', 'like', '%' . $arg . '%')
                                ->orWhere('lastname', 'like', '%' . $arg . '%')
                                ->orWhere('mail', 'like', '%' . $arg . '%')
                                ->get();
            return response()->json([
                'customer' => $customer
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'No match'
            ],404);
        }
    }


    /**
     * CREATE A NEW CUSTOMER
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @OA\Post(path="/customers",
     *  summary="Create a new customer",
     *  tags={"Customers"},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="persistent", type="boolean", example="true"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Customer could not be created",
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Customer created",
     *  )
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


            $customer = new Customer();
            foreach($this->fields as $field){
                $customer->{$field['name']} = $request->input($field['name']);
            }
            $customer->save();

            return response()->json([
                'message' => 'CREATED',
                'customer' => $customer
            ],201);

        }catch(\Exception $e){

            return response()->json([
                'message' => 'User could not be created'
            ],409);
        }

    }


    /**
     * UPDATE A CUSTOMER
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @OA\Put(path="/customers/{id}",
     *  summary="Update a customer",
     *  tags={"Customers"},
     *  @OA\Response(
     *    response=404,
     *    description="Customer does not exist",
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Customer could not be updated",
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Customer updated",
     *  )
     * )
     */
    public function update($id, Request $request){

        try{
            $customer = Customer::findOrFail($id);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'This user does not exist'
            ],404);
        }

        $rules = [];
        foreach($this->fields as $field){
            $rules[$field['name']] =  $field['validation'];
        }
        $this->validate($request, $rules);

        try{

            foreach($this->fields as $field){
                $customer->{$field['name']} = $request->input($field['name']) ?? $customer->{$field['name']};
            }
            $customer->save();

            return response()->json([
                'message' => 'UPDATED',
                'customer' => $customer
            ],201);

        }catch(\Exception $e){

            return response()->json([
                'message' => 'User could not be updated'
            ],409);
        }


    }

}
