<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Time;
//use App\Models\Prescription;
use Auth;

class AppointmentController extends Controller
{

    protected $arrayAM;
    protected $arrayPM;

    public function __construct( ) {
        $this->arrayAM = [
            "6am", "6.20am", "6.40am",
            "7am", "7.20am", "7.40am",
            "8am", "8.20am", "8.40am",
            "9am", "9.20am", "9.40am",
            "10am", "10.20am", "10.40am",
            "11am", "11.20am", "11.40am",
            "12am", "12.20am", "12.40am",
        ];

        $this->arrayPM = [
            "12pm", "12.20pm", "12.40pm",
            "1pm", "1.20pm", "1.40pm",
            "2pm", "2.20pm", "2.40pm",
            "3pm", "3.20pm", "3.40pm",
            "4pm", "4.20pm", "4.40pm",
            "5pm", "5.20pm", "5.40pm",
            "6pm", "6.20pm", "6.40pm",
            "7pm", "7.20pm", "7.40pm",
            "8pm", "8.20pm", "8.40pm",
            "9pm", "9.20pm", "9.40pm",
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user    = Auth::user();
        $users   = User::where('role_id', 3);
        $doctors = User::where('role_id',1);
        $role    = Role::count();
        $department_count  = Department::count();
        $myappointments = Appointment::latest()->where('user_id', $user->id)->get();

        return view('admin.appointment.index', compact('myappointments', 'user'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $amHtml = $this->generating_appointment_html( 3, $this->arrayAM );
        $pmHtml = $this->generating_appointment_html( 3, $this->arrayPM );

        $user    = Auth::user();
        $users   = User::where('role_id', 3);
        $doctors = User::where('role_id',1);
        $role    = Role::count();
        $department_count  = Department::count();

        return view('admin.appointment.create', compact('users', 'user', 'amHtml', 'pmHtml' ) );
    }


    /**
     * Function generating html for appointments settings
     *
     * @param int $count
     * @param array $array
     * @param array $times
     *
     * @return string
     */
    public function generating_appointment_html( $count = 3, $array = [], $times = [] ) {

        $arrayDivided = array_chunk( $array, $count );
        $html = '';

        foreach ( $arrayDivided as $k => $v ) {

            $html .= '<tr>';
            $html .= '<th scope="row">' .  ($k+1) . '</th>';
            foreach ( $v as $k1 => $time ) {
                $checked = ( !empty( $times ) && $times->contains('time', $time) ) ? 'checked' : '';

                $html .=  '<td><input type="checkbox" class="time_input" name="time[]" id="'. $time . '" value="'. $time . '" ' . $checked . '><label for="'. $time . '" >'. $time . '</label></td>';
            }
            $html .= '</tr>';
        }

        return $html;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'date' => 'required|unique:appointments,date,NULL,id,user_id,'.\Auth::id(),
            'time' => 'required'
        ]);

        $appointment = Appointment::create([
            'user_id' => auth()->user()->id,
            'date'    => $request->date
        ]);

        foreach( $request->time as $time ){
            Time::create([
                'appointment_id' => $appointment->id,
                'time'           => $time,
                //'status' => 0
            ]);
        }


        return redirect()->back()->with('message', 'Appointment was created for '. $request->date);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Function checking
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function check( Request $request ){

        $user = Auth::user();
        $date = $request->date;

        $appointment = Appointment::where('date', $date )->where('user_id',  $user->id)->first();

        if ( !$appointment ) {
            return redirect()->to('/appointment')->with('errmessage','Appointment time not available for this date');
        }

        $appointmentId = $appointment->id;
        $times = Time::where('appointment_id',$appointmentId)->get();

        $amHtml = $this->generating_appointment_html( 3, $this->arrayAM, $times );
        $pmHtml = $this->generating_appointment_html( 3, $this->arrayPM, $times );

        return view('admin.appointment.index',compact('times','appointmentId', 'date', 'user', 'pmHtml', 'amHtml'));
    }

    /**
     * Function updating time
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTime( Request $request ){

        $appointmentId = $request->appoinmentId;
        $appointment   = Time::where('appointment_id', $appointmentId)->delete();

        foreach ( $request->time as $time ) {
            Time::create([
                'appointment_id' => $appointmentId,
                'time'           => $time,
                'status'         => 0
            ]);
        }

        return redirect()->route('appointment.index')->with('message', 'Appointment time is updated!!');
    }

}
