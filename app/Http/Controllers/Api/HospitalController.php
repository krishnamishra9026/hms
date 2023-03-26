<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Repositories\HospitalRepository;
use DB;

class HospitalController extends Controller
{

    private $hospitalRepository;

    public function __construct(HospitalRepository $hospitalRepo)
    {
        $this->hospitalRepository = $hospitalRepo;
    }

    public function index()
    {
        $hospitals = Hospital::all();
        return response()->json([
            'hospitals' => $hospitals
        ]);
    }

    public function store(Request $request)
    {
    if(env('ENABLE_CLAIMSTACK') == true):

        $input = $request->all();
        $input['hospital_name'] = $request->name;
        $input['prefix_code'] = '91';
        $input['username'] = $request->name;
        $this->hospitalRepository->store($input);

        $hospital = Hospital::create($request->all());

        Hospital::where('id',$hospital->id)->update(['uid' => 'HPS'.$hospital->id]);

        return response()->json([
            'message' => "Hospital saved successfully!",
            'hospital' => $hospital
        ], 200);

    else:
        return response()->json([
            'message' => "Remote create permission disabled!",
        ], 200);


    endif;
    }

    public function update(Request $request, Hospital $hospital)
    {
        $hospital->update($request->all());

        return response()->json([
            'message' => "Hospital updated successfully!",
            'hospital' => $hospital
        ], 200);
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();

        return response()->json([
            'message' => "Hospital deleted successfully!",
        ], 200);
    }

}
