<?php


namespace App\Http\Controllers;


use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{

    /**
     * CityController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET CITY BY ID
     * @param $id
     * @return JsonResponse
     * @OA\Get(path="/cities/{id}",
     *  summary="Get city by its ID",
     *  tags={"Cities"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="City",
     *    @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/City"))
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Not found",
     *  )
     * )
     */
    public function getById($id){
        try{
            $city = City::findOrFail($id);
            return response()->json([
                'city' => $city
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'No result'
            ],404);
        }
    }



    /**
     * GET CITY BY ATTRIBUTE (ex ID, NAME, LAT ..)
     * @param $attr
     * @param $arg
     * @return JsonResponse
     * @OA\Get(path="/cities/{attribute}/{value}",
     *  summary="Get city by any attribute",
     *  tags={"Cities"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Cities",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/City")),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="No result",
     *  )
     * )
     */
    public function getByAttr($attr,$arg){
        try{
            $cities = City::where($attr, '=', $arg )->get();
            return response()->json([
                'cities' => $cities
            ],200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'No result'
            ],404);
        }
    }


    /**
     * SEARCH CITIES BY ZIPCODE OR NAME
     * @param $arg
     * @return JsonResponse
     * @OA\Get(path="/cities/search/{value}",
     *  summary="Search city by name or zipcode",
     *  tags={"Cities"},
     *  security={{"JWT":{}}},
     *  @OA\Response(
     *    response=200,
     *    description="Cities",
     *    @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/City")),
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="No result",
     *  )
     * )
     */
    public function search($arg){
        if(strlen($arg)<2){
            return response()->json([
                'message' => 'Search argument must be at least 3 characters long'
            ],404);
        }
        try {
            $cities = City::where('zip_code', 'like', $arg . '%')
                        ->orWhere('name', 'like', '%' . $arg . '%')
                        ->take(20)
                        ->get();
            return response()->json([
                'cities' => $cities
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No result'
            ], 404);
        }
    }

}
