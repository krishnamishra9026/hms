<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Repositories\PatientRepository;
use DB;

class PatientController extends Controller
{

    private $patientRepository;

    public function __construct(PatientRepository $patientRepo)
    {
        $this->patientRepository = $patientRepo;
    }

    public function index()
    {
        $patients = Patient::all();
        return response()->json([
            'patients' => $patients
        ]);
    }

    public function store(Request $request)
    {
    if(env('ENABLE_CLAIMSTACK') == true):

        $input = $request->all();
        $input['patient_name'] = $request->name;
        $input['prefix_code'] = '91';
        $input['username'] = $request->name;
        $this->patientRepository->store($input);

        $patient = Patient::create($request->all());

        Patient::where('id',$patient->id)->update(['uid' => 'HPS'.$patient->id]);

        return response()->json([
            'message' => "Patient saved successfully!",
            'patient' => $patient
        ], 200);

    else:
        return response()->json([
            'message' => "Remote create permission disabled!",
        ], 200);


    endif;
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());

        return response()->json([
            'message' => "Patient updated successfully!",
            'patient' => $patient
        ], 200);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->json([
            'message' => "Patient deleted successfully!",
        ], 200);
    }

}
