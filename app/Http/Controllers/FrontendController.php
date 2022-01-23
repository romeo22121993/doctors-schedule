<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Time;
use App\Models\User;
use App\Models\Booking;
//use App\Models\Prescription;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{

    /**
     * Function for front page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
    	date_default_timezone_set('Australia/Melbourne');

        if ( request('date') ){
            $doctors = $this->findDoctorsBasedOnDate( request('date') );
            return view('welcome',compact('doctors'));
        }

        $doctors = Appointment::where('date', date('Y-m-d'))->get();

    	return view('welcome', compact('doctors'));
    }

    /**
     * Function showing appointment
     *
     * @param $doctorId
     * @param $date
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show( $doctorId, $date ) {

        $appointment = Appointment::where('user_id',$doctorId)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $user  = User::where('id', $doctorId)->first();
        $doctor_id = $doctorId;

        return view('appointment', compact('times','date', 'user', 'doctor_id', 'appointment'));
    }

    /**
     * Function searching doctors by date
     *
     * @param $date
     * @return mixed
     */
    public function findDoctorsBasedOnDate($date)
    {
        $doctors = Appointment::where('date',$date)->get();

        return $doctors;

    }

    /**
     * Function saving booking
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        date_default_timezone_set('Australia/Melbourne');

        $request->validate(['time'=>'required']);
        $check = $this->checkBookingTimeInterval( $request->date );

        if ( $check ) {
            return redirect()->back()->with('message', 'You have already booked an appointment for this day. Please wait to make next appointment.');
        }

        Booking::create([
            'user_id'   => $user->id,
            'doctor_id' => $request->doctorId,
            'time'      => $request->time,
            'date'      => $request->date,
            'status'    => 0
        ]);

        Time::where('appointment_id', $request->appointmentId)
            ->where('time', $request->time)
            ->update(['status'=>1]);

        //send email notification
        $doctorName = User::where('id', $request->doctorId)->first();

        $mailData = [
            'name'       => $user->name,
            'time'       => $request->time,
            'date'       => $request->date,
            'doctorName' => $doctorName->name
        ];

        try{
//            \Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));
            /*
            $toName  = $user->name;
            $toEmail = $user->email;

            Mail::send( 'email.appointment', $mailData, function($message) use ( $toName, $toEmail) {
                $message->to($toEmail, $toName)
                    ->subject( 'Appointment Test Message');
                $message->from( env('MAIL_FROM_ADDRESS'),  'Roman Bolshevskyi');
            });
            */
        }catch(\Exception $e){
            return $e;
        }

        return redirect()->back()->with('message', 'Your appointment was booked.');

    }

    /**
     * Function checking booking time
     *
     * @return mixed
     */
    public function checkBookingTimeInterval( $date )
    {
        return Booking::orderby('id','desc')
            ->where('user_id', auth()->user()->id)
            ->where('date', $date)
            ->exists();
    }

    /**
     * Function for my bookings
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myBookings()
    {
        $appointments = Booking::latest()->where('user_id',auth()->user()->id)->get();
        return view('booking.index',compact('appointments'));
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id',auth()->user()->id)->get();
        return view('my-prescription',compact('prescriptions'));
    }

    /**
     * Function api for getting doctors today
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function doctorToday(Request $request)
    {
        $doctors = Appointment::with('doctor')->whereDate('date',date('Y-m-d'))->get();
        return $doctors;
    }

    /**
     * Function API or getting all doctors
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findDoctors( Request $request )
    {
        $doctors = Appointment::with('doctor')->whereDate('date', $request->date)->get();

        return $doctors;
    }

}
