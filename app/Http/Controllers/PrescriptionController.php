<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Prescription;
use Auth;

class PrescriptionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {

        $user = Auth::user();
//    	date_default_timezone_set('Australia/Melbourne');
		$bookings =  Booking::where('date', date('Y-m-d'))->where('status',1)->where('doctor_id', $user->id)->get();

		return view('prescription.index', compact('bookings', 'user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {

    	$data  = $request->all();
    	$data['medicine'] = implode(',',$request->medicine);
    	Prescription::create($data);

    	return redirect()->back()->with('message', 'Prescription created');
    }

    /**
     * @param $userId
     * @param $date
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($userId,$date) {

        $user = Auth::user();
        $prescription = Prescription::where('user_id',$userId)->where('date',$date)->first();

        return view('prescription.show', compact('prescription', 'user'));
    }

    //get all patients from prescription table

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientsFromPrescription() {

        $user = Auth::user();
        $patients = Prescription::get();

        return view('prescription.all', compact('patients', 'user'));
    }

}
