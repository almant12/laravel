<?php

namespace App\Http\Controllers;
use App\Models\ConfirmationToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\RegisterMail;
use function PHPUnit\Framework\isFalse;
use function Symfony\Component\String\u;

class UserController extends Controller
{


    public function register(){
        return view('users.register');
    }

    public function login(){
        return view('users.login');
    }

    public function store(Request $request){

            $formFields = $request->validate([
                'name' => ['required', 'min:3'],
                'lastname' => ['required', 'min:3'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|confirmed|min:6'
            ]);


            $formFields['password'] = bcrypt($formFields['password']);
            $user = User::create($formFields);
            $token = \Illuminate\Support\Str::random(20);
            $confirmToken = new ConfirmationToken();
            $confirmToken->token = $token;
            $confirmToken->create_time = Carbon::now();
            $confirmToken->expired_time = Carbon::now()->addMinutes(5);
            $confirmToken->user_id = $user->id;
            $confirmToken->save();

            $ip = env('my_ip');

            $link = 'http://' . $ip . '/user/confirmEmail?token=' . $token;


            Mail::to($user->email)->send(new RegisterMail($link));

            return redirect(route('user.login'))->with('message', 'Check your email to confirm the account');

    }

    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');

    }

    public function authenticate(Request $request){

        $formFields = $request->validate([
            'email'=>['required','email'],
            'password'=>['required','min:6']
        ]);



        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now logged in');
        }

        return back()->withErrors(['email'=>'Invalid Credentials']);
    }


    public function edit(User $user){
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request,User $user)
    {
        if ($user->id!=auth()->id()){
            abort(403,'Unauthorized Action');
        }
        $formUser = $request->validate([
            'name' => 'required',
            'lastname' => 'required'
        ]);

        $currentUser = User::find($user->id);

        if ($request->hasFile('logo')) {
            $formUser['logo'] = $request->file('logo')->store('logos', 'public');
        }


        $changes = array_diff_assoc($formUser,$currentUser->toArray());


        if (!empty($changes)) {
            $user->update($formUser);
            return redirect('/')->with('message', 'User updated successfully');
        }else{
            return redirect('/');
        }
    }



//     public function sendEmail(Request $request){
//
//        $email = $request->validate([
//            'email'=>['email','required']
//        ]);
//
//        $user = User::where('email',$email)->first();
//        if (isset($user)) {
//            $token = \Illuminate\Support\Str::random(20);
//            $confirmToken = ([
//                'token'=>$token,
//                'create_time'=>Carbon::now(),
//                'expired_time'=>Carbon::now()->addMinutes(5),
//                'user_id'=>$user->id
//            ]);
//            ConfirmationToken::create($confirmToken);
//            $link = 'localhost:8000/verify-token?token=' . $token;
//
//            Mail::to($email)->send(new RegisterMail($link));
//            return redirect()->back()->with('message','check the email to reset your password');
//        }else{
//            return redirect()->back()->with('message','email not found');
//        }
//     }

     public function verifyToken(Request $request){

        $token = $request->query('token');

        $confirmToken = ConfirmationToken::where('token',$token)->first();

        if (is_null($confirmToken)){
            return response()->json('token not founded');
        }

        if (!is_null($confirmToken->confirmed)){
            return response()->json('email already confirmed');
        }

         $expiredAt = Carbon::parse($confirmToken->expired_time);

        if (now()->gt($expiredAt)){
            return response()->json('toke has expired');
        }

        $confirmToken->update([
            'confirmed'=>now()
        ]);
        $confirmToken->user()->update([
            'verified'=>true
        ]);

        return response()->json('confirmed');
     }


}
