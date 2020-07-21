<?php

namespace App\Http\Controllers;


use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * REPRESENTS APPOINTMENT COLUMN IN DATABASE
 *
 * Class AppointmentController
 * @package App\Http\Controllers
 */
class AppointmentController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->fields = [
            ['name' => 'id_appointment_types', 'validation' => 'integer|exists:appointment_types,id', 'required' => true],
            ['name' => 'id_customers', 'validation' => 'integer|exists:customers,id', 'required' => true],
            ['name' => 'id_employees', 'validation' => 'integer|exists:employees,id', 'required' => true],
            ['name' => 'date_start', 'validation' => 'date', 'required' => true],
            ['name' => 'note', 'validation' => 'string', 'required' => false],
            ['name' => 'feedback', 'validation' => 'string', 'required' => false],
        ];
    }

    /**
     * GET ALL APPOINTMENT
     * @return JsonResponse
     * @OA\Get(path="/appointments",
     *  summary="Get all appointments",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Appointments",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Appointment")),
     *  ),
     * )
     */
    public function getAll()
    {
        return response()->json([
            'appointments' => Appointment::all()
        ], 200);
    }

    /**
     * GET A APPOINTMENT BY ITS ID
     * @param $id
     * @return JsonResponse
     * @OA\Get(path="/appointements/{id_employees}/{id_customers}/{date_start}",
     *  summary="Get appointment",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Appointment",
     *    @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/Appointment"))
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Appointment not found",
     *  ),
     * )
     */
    public function getOneByIDs($id_employees, $id_customer, $date_start)
    {
        try{
            $appointment = Appointment::where('id_employees', $id_employees)
                                ->where('date_start', $date_start)
                                ->where('id_customers', $id_customer)
                                ->get();
                return response()->json([
                    'appointment' => $appointment
                ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'No match'
            ],404);

        }
    }

    /**
     * GET APPOINTMENTS BY ITS EMPLOYEE
     * @param $id_employees
     * @return JsonResponse
     * @OA\Get(path="/appointments/{id_employees}",
     *  summary="Get employee's appointments",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Appointments",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Appointment")),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="No appointment found",
     *  ),
     * )
     */
    public function getByEmployee($id_employees)
    {
        try {
            $appointments = Appointment::where('id_employees', $id_employees)
                                        ->get();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        }catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ], 404);
        }
    }

    /**
     * GET APPOINTMENTS BY ITS CUSTOMER
     * @param $id_customers
     * @return JsonResponse
     * @OA\Get(path="/appointments/{id_customers}",
     *  summary="Get customer's appointments",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Appointments",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Appointment")),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="No appointment found",
     *  ),
     * )
     */
    public function getByCustomer($id_customers)
    {
        try {
            $appointments = Appointment::where('id_customers', $id_customers)
                ->get();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        }catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ], 404);
        }
    }



    /**
     * GET A APPOINTMENT WHERE LIKE IN NAME OR MAIL
     * @param $arg
     * @return JsonResponse
     */
    public function getWhere($arg)
    {
        try{
            $appointment = Appointment::where('id_employees', 'like', '%' . $arg . '%')
                                ->orWhere('date_start', 'like', '%' . $arg . '%')
                                ->orWhere('id_customers', 'like', '%' . $arg . '%')
                                ->get();
            return response()->json([
                'appointment' => $appointment
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'No match'
            ],404);
        }
    }


    /**
     * CREATE A NEW APPOINTMENT
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @OA\Post(path="/appointments",
     *  summary="Create appointment",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Each field is required",
     *    @OA\JsonContent(
     *       @OA\Property(property="id_appointment_types", type="integer"),
     *       @OA\Property(property="id_customers", type="integer"),
     *       @OA\Property(property="id_employees", type="integer"),
     *       @OA\Property(property="date_start", type="string", format="date"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Appointment created",
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Appointment could not be created",
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

            $appointment = new Appointment();
            foreach($this->fields as $field){
                $appointment->{$field['name']} = $request->input($field['name']);
            }
            $appointment->save();


            return response()->json([
                'message' => 'CREATED',
                'appointment' => $appointment
            ],201);

        }catch(\Exception $e){

            return response()->json([
                'message' => 'Une erreur est survenu à la création'
            ],409);
        }

    }

    /**
     * UPDATE A APPOINTMENT
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @OA\Put(path="/appointements/{id_employees}/{id_customers}/{date_start}",
     *  summary="Update appointment",
     *  tags={"Appointments"},
     *  security={{"JWT":{}}},
     *  @OA\RequestBody(
     *    description="Fields that can be updated",
     *    @OA\JsonContent(
     *       @OA\Property(property="id_appointment_types", type="integer"),
     *       @OA\Property(property="id_customers", type="integer"),
     *       @OA\Property(property="id_employees", type="integer"),
     *       @OA\Property(property="date_start", type="string", format="date"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Appointment updated",
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Appointment not found",
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Appointment cound not be updated",
     *  ),
     * )
     */
    public function update($id_customer, $id_employees, $date_start, Request $request) {

        try{
            $appointment = Appointment::where('id_employees', $id_employees)
                                ->where('date_start', $date_start)
                                ->where('id_customers', $id_customer)
                                ->first();
        }catch(\Exception $e){
            return response()->json([
                'message' => 'No match'
            ],404);

        }

        $rules = [];
        foreach($this->fields as $field){
            $rules[$field['name']] =  $field['validation'];
        }
        $this->validate($request, $rules);

        try{

            foreach($this->fields as $field){
                $appointment->{$field['name']} = $request->input($field['name']) ?? $appointment->{$field['name']} ;
            }
            $appointment->save();

            return response()->json([
                'message' => 'UPDATED',
                'appointment' => $appointment
            ],201);

        }catch(\Exception $e){

            return response()->json([
                'message' => 'Une erreur est survenu à la modification'
            ],409);
        }
    }
}
