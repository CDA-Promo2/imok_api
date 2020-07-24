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
     */
    public function getAll()
    {
        return response()->json([
            'appointments' => Appointment::all()
        ], 200);
    }

    /**
     * GET A APPOINTMENT BY ITS ID
     * @param $id_employees
     * @param $id_customer
     * @param $date_start
     *
     * @return JsonResponse
     */
    public function getOneByIDs($id_employees, $id_customer, $date_start)
    {
        try {
            $appointment = Appointment::where('id_employees', $id_employees)
                                ->where('date_start', $date_start)
                                ->where('id_customers', $id_customer)
                                ->get();
                return response()->json([
                    'appointment' => $appointment
                ],200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ],404);

        }
    }

    /**
     * GET APPOINTMENTS BY ITS EMPLOYEE
     * @param $id_employees
     * @return JsonResponse
     */
    public function getByEmployee($id_employees)
    {
        try {
            $appointments = Appointment::where('id_employees', $id_employees)
                                        ->get();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ], 404);
        }
    }

    /**
     * GET APPOINTMENTS BY ITS CUSTOMER
     * @param $id_customers
     * @return JsonResponse
     */
    public function getByCustomer($id_customers)
    {
        try {
            $appointments = Appointment::where('id_customers', $id_customers)
                ->get();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        } catch(\Exception $e) {
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
        try {
            $appointment = Appointment::where('id_employees', 'like', '%' . $arg . '%')
                                ->orWhere('date_start', 'like', '%' . $arg . '%')
                                ->orWhere('id_customers', 'like', '%' . $arg . '%')
                                ->get();
            return response()->json([
                'appointment' => $appointment
            ],200);

        } catch(\Exception $e) {
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
     */
    public function create(Request $request)
    {
        $rules = [];
        foreach($this->fields as $field){
            $required = $field['required'] === true ? '|required' : '' ;
            $rules[$field['name']] =  $field['validation'] . $required;
        }
        $this->validate($request, $rules);
        try {
            $appointment = new Appointment();
            foreach($this->fields as $field){
                $appointment->{$field['name']} = $request->input($field['name']);
            }
            $appointment->save();
            return response()->json([
                'message' => 'CREATED',
                'appointment' => $appointment
            ],201);
        } catch(\Exception $e) {
            return response()->json([
//                'message' => 'Une erreur est survenu à la création'
                'message' => $e
            ],409);
        }

    }

    /**
     * UPDATE A APPOINTMENT
     *
     * @param $id_customer
     * @param $id_employees
     * @param $date_start
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id_customer, $id_employees, $date_start, Request $request)
    {
        try {
            $appointment = Appointment::where('id_employees', $id_employees)
                                ->where('date_start', $date_start)
                                ->where('id_customers', $id_customer)
                                ->first();
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'No match'
            ],404);
        }
        $rules = [];
        foreach($this->fields as $field) {
            $rules[$field['name']] =  $field['validation'];
        }
        $this->validate($request, $rules);
        try {
            foreach($this->fields as $field){
                $appointment->{$field['name']} = $request->input($field['name']) ?? $appointment->{$field['name']} ;
            }
            $appointment->save();
            return response()->json([
                'message' => 'UPDATED',
                'appointment' => $appointment
            ],201);
        } catch(\Exception $e) {

            return response()->json([
                'message' => 'Une erreur est survenu à la modification'
            ],409);
        }
    }
}
