<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
//use Illuminate\Support\Facades\Auth;
use Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users  = User::where('role_id','!=',3)->get();
        $user   = Auth::user();
        return view('admin.doctor.index', compact('users', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name','!=','patient')->get();
        $user  = Auth::user();
        $departments = Department::all();

        return view('admin.doctor.create', compact('roles', 'user', 'departments' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request ) {

        $user_obj = new User();
        $this->validateStore( $request );
        $data = $request->all();

        $data['image'] = 'id';
        $data['password'] = bcrypt( $request->password );
        $id = User::create( $data )->id;
        $name = $user_obj->userAvatar( $request, $id );

        User::where('id', $id)->update(array('image' => $name));

        return redirect()->back()->with( 'message', 'Doctor is added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.doctor.delete',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.doctor.edit',compact('user'));
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
        $this->validateUpdate($request,$id);
        $data = $request->all();
        $user = User::find($id);
        $imageName = $user->image;
        $userPassword = $user->password;
        if($request->hasFile('image')){
            $imageName =(new User)->userAvatar($request);
            unlink(public_path('images/'.$user->image));
        }
        $data['image'] = $imageName;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }else{
            $data['password'] = $userPassword;
        }
         $user->update($data);
        return redirect()->route('doctor.index')->with('message','Doctor updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       if(auth()->user()->id == $id){
            abort(401);
       }
       $user = User::find($id);
       $userDelete = $user->delete();
       if($userDelete){
        unlink(public_path('images/'.$user->image));
       }
        return redirect()->route('doctor.index')->with('message','Doctor deleted successfully');

    }

    /**
     * Function validation of creating new doctor or admin
     *
     * @param $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateStore($request){
        return  $this->validate($request,[
            'name'         =>  'required',
            'email'        =>  'required|unique:users',
            'password'     =>  'required|min:6|max:25',
            'gender'       =>  'required',
            'education'    =>  'required',
            'address'      =>  'required',
            'department'   =>  'required',
            'phone_number' => 'required|numeric',
            'image'        => 'required|mimes:jpeg,jpg,png',
            'role_id'      =>'required',
            'description'  =>'required'
       ]);
    }


    public function validateUpdate($request,$id){
        return  $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,

            'gender'=>'required',
            'education'=>'required',
            'address'=>'required',
            'department'=>'required',
            'phone_number'=>'required|numeric',
            'image'=>'mimes:jpeg,jpg,png',
            'role_id'=>'required',
            'description'=>'required'

       ]);
    }




}
