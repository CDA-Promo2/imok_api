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
     * @param $id
     * @return JsonResponse
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
     */
    public function create(Request $request){


        $this->validate($request,[
            'note' => 'string|required',
            'feedback' => 'string|required',
            'id_appointment_types' => 'string|required',
            'id_customers' => 'string|required',
            'id_employees' => 'string|required',
            'date_start' => 'date|required',
        ]);

        try{

            $appointment = new Appointment();

            $appointment->note = $request->input('note');
            $appointment->feedback = $request->input('feedback');
            $appointment->id_appointment_types = $request->input('id_appointment_types');
            $appointment->id_customers = $request->input('id_customers');
            $appointment->id_employees = $request->input('id_employees');
            $appointment->date_start = $request->input('date_start');
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
        

        $this->validate($request,[
            'note' => 'string',
            'feedback' => 'string',
            'id_appointment_types' => 'integer',
            'id_customers' => 'integer',
            'id_employees' => 'integer',
            'date_start' => 'date',
        ]);

        try{




            $appointment->note = $request->input('note') ?? $appointment->note;
            $appointment->feedback = $request->input('feedback') ?? $appointment->feedback;
            $appointment->id_appointment_types = $request->input('id_appointment_types') ?? $appointment->id_appointment_types;
            $appointment->id_customers = $request->input('id_customers') ?? $appointment->id_customers;
            $appointment->id_employees = $request->input('id_employees') ?? $appointment->id_employees;
            $appointment->date_start = $request->input('date_start') ?? $appointment->date_start;
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
