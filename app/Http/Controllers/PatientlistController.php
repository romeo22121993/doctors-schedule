<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Auth;

class PatientlistController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
//    	date_default_timezone_set('Australia/Melbourne');
    	$user = Auth::user();

    	if ( $request->date ) {
    		$bookings = Booking::latest()->where('date', $request->date)->get();
    		return view('admin.patientlist.index', compact('bookings', 'user'));
    	}

    	$bookings = Booking::latest()->where('date', date('Y-m-d'))->get();

    	return view('admin.patientlist.index', compact('bookings', 'user'));
    }

    /**
     * Function changing status
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        $booking  = Booking::find($id);
        $booking->status =! $booking->status;
        $booking->save();
        return redirect()->back();

    }

    /**
     * Function for getting all appointments
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function allTimeAppointment()
    {
        $user = Auth::user();
        $bookings = Booking::latest()->paginate(20);

        return view('admin.patientlist.index',compact('bookings', 'user'));
    }

}
