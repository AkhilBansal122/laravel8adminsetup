<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
class UserController extends Controller
{
    //

    public function Login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $remember_me = $request->has('remember') ? true : false; 
     
        
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember_me))
        {
            
            $user = auth()->user();   
            $request->session()->put('id',$user->id);
            $request->session()->put('name',$user->name);
            $request->session()->put('email',$user->email);
            $request->session()->put('user_id',$user->id);
            // $request->session()->put('role',$user->role);
            // $request->session()->put('use_image',$user->use_image);
            // $request->session()->put('phone',$user->phone);
            // $request->session()->put('image',$user->image);
            $user = Auth::getProvider()->retrieveByCredentials(['email' => $request->input('email'), 'password' => $request->input('password')]);

            
            if($remember_me==true)
            {
                $minutes = 14400;
                $response = new Response();
                $cooky=(cookie('remember_me', $user->remember_token, $minutes));
                return redirect()->to('/dashboard') ->withSuccess('Signed in')->withCookie($cooky);
            }else{
                $minutes = 0;
                return redirect()->to('/dashboard') ->withSuccess('Signed in')->withCookie(cookie('remember_me','', $minutes));
            }
          
          
        }else{
            return redirect('login')->with('msg', 'Please enter valid login credentials.');  
        }
    
    }


    public function Logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login'); //redirect back to admin
    }

}
