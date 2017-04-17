<?php

namespace App\Http\Controllers;

use App\User;
use Dotenv\Validator;
use Faker\Provider\Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\MailHandler;
use Naux\Mail\ SendCloudServiceProvider;

class UserController extends Controller
{
    public function register()
    {
        return view('user.register');
    }

    public function store(Requests\UserRegisterRequest $request)
    {
        $data=[
            'confirm_code'=>str_random(48),
            'avatar'=>'/image/default-avatar.png',
        ];
       $user=User::create(array_merge($request->all(),$data));
       $subject='Confirm Your Email';
       $view='email.register';

       $this->sendTo($user,$subject,$view,$data);
       return redirect('/');
    }

    public function confirmEmail($confirm_code)
    {
        $user=User::where('confirm_code',$confirm_code)->first();
        if (is_null($user)){
            return redirect('/');
        }
        $user->is_comfirmed=1;
        $user->confirm_code = str_random(48);
        $user->save();

        return redirect('user/login');

    }

    private function sendTo($user,$subject,$view,$data=[])
    {
        Mail::queue($view,$data,function ($message) use ($user,$subject){
            $message->to($user->email)->subject($subject);
        });
    }

    public function login()
    {
        return view('user.login');
    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

    public function signin(Requests\UserLoginRequest $request){
        if (\Auth::attempt([
            'email'=>$request->get('email'),
            'password'=>$request->get('password'),
            'is_comfirmed'=>1
        ])){
            return redirect('/');
        }
        \Session::flash('user_login_failed','密码不正确或邮箱未验证');
         return redirect('/user/login')->withInput();
    }

    public function avatar()
    {
       return view('user.avatar');
    }

    public function changeAvatar(Request $request)
    {
        $file=$request->file('avatar');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = \Validator::make($input,$rules);
        if ( $validator->fails() ) {
            return \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);

        }
        $destinationPath = 'uploads/';
        $filename=\Auth::user()->id.'_'.time().$file->getClientOriginalName();
        $file->move($destinationPath,$filename);
        \Image::make($destinationPath.$filename)->fit(200)->save();


    return \Response::json([
        'success'=>true,
        'avatar'=>'/'.$destinationPath.$filename,
    ]);
    }

    public function cropAvatar(Request $request)
    {
         $photo=mb_substr($request->get('photo'),1);
         $width=(int) $request->get('w');
        $height=(int) $request->get('h');
        $xAlign=(int) $request->get('x');
        $yAlign=(int) $request->get('y');

        \Image::make($photo)->crop($width,$height,$xAlign,$yAlign)->save();
        $user=\Auth::user();
        $user->avatar=$request->get('photo');
        $user->save();
        return redirect('/user/avatar');
}






}
