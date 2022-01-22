<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;

class DashboardController extends Controller
{
    /**
     * Function construct
     *
     */
	public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Function of main page of dashboard
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {

        $user  = Auth::user();
        $users = User::where('role_id', 3);
        $doctors = User::where('role_id',1);
        $role              = Role::count();
        $department_count  = Department::count();

    	if ( $user->role->name=='patient' ){
    		return view('home');
    	}

    	return view('dashboard', compact('user', 'users', 'doctors', 'role', 'department_count'));

    }

}
