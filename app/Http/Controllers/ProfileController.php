<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{

    /**
     * Function showing profile page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

    	return view('profile.index', compact('user') );
    }

    /**
     * Function updating patient profile
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
    	$this->validate($request,[
    		'name'   => 'required',
    		'gender' => 'required'
    	]);

    	User::where('id', auth()->user()->id)
    		->update( $request->except('_token') );

    	return redirect()->back()->with('message', 'Profile is updated.');

    }

    /**
     * Profile picture upodating function
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function profilePic(Request $request)
    {
    	$this->validate( $request, ['file'=>'required|image|mimes:jpeg,jpg,png'] );

    	if ( $request->hasFile('file') ) {
    		$image = $request->file('file');
//    		$name = time().'.'.$image->getClientOriginalExtension();
    		$name = auth::user()->id .'.'.$image->getClientOriginalExtension();
    		$destination = public_path('/images');
    		$image->move($destination,$name);

    		$user = User::where('id',auth()->user()->id)->update(['image'=>$name]);

    		return redirect()->back()->with('message', 'Profile picture is updated');
    	}
    }


}
