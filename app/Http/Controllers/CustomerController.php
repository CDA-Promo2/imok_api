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
            ['name' => 'civility', 'validation' => 'integer', 'required' => true],
            ['name' => 'birthdate', 'validation' => 'date', 'required' => true],
        ];
    }

    /**
     * GET ALL CUSTOMERS
     * @return JsonResponse
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
