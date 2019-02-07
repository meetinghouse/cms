<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public $users;

    public function __construct(User $users = null)
    {
        parent::__construct();
        $this->users    = ($users) ? $users : new User();
        $this->beforeFilter("auth", ['except' => ['login', 'getLogout', 'authenticate']]);
        $this->beforeFilter('csrf', ['on'=>'post']);
    }


    public function index()
    {
        parent::show();
        $users = $this->users->all();
        $banner = $this->banner;
        return $this->respond($users, 'users.index', compact('users', 'banner'));
    }

    public function login()
    {
        parent::show();		
        return view('sessions.login');
    }

    public function show($id = null)
    {
        parent::show();
        $user = $this->users->find($id);
        return ")]}',\n" . $user;
    }

    public function edit($id = null)
    {
        parent::show();
        $user = $this->users->find($id);
        return view('users.edit', compact('user'));
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect('login')->with('message', 'Your are now logged out!');
    }


    public function updatePassword()
    {

        $validator = Validator::make(Input::all(), ['password' => 'required|confirmed', 'email' => 'required', 'password_confirmation' => 'required']);

        if ($validator->passes()) {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return $user;
        }
    }

    public function update($id)
    {
        $user_update = Input::all();
        $password = false;
        $user = User::find($id);

        if (isset($user_update['reset']) && $user_update['reset'] == 'on') {
            $validator = Validator::make(Input::all(), ['email' => 'required|email', 'password' => 'confirmed|min:8']);
            $password  = Hash::make($user_update['password']);
        } else {
            $validator = Validator::make(Input::all(), ['email' => 'required|email']);
        }
        $banner = $this->banner;
        if ($validator->passes()) {
            if ($user_update['email'] != $user->email) {
                if (User::where("email", 'LIKE', $user_update['email'])) {
                    return Redirect::back()->withErrors(['email' => ["Email is already in the system"]])->withInput();
                }
            }
            $user->email            = $user_update['email'];
            $user->firstname        = (isset($user_update['firstname'])) ? $user_update['firstname'] : '';
            $user->lastname         = (isset($user_update['lastname'])) ? $user_update['lastname'] : '';
            $user->admin            = (isset($user_update['admin'])) ? $user_update['admin'] : 0;
            $user->active           = (isset($user_update['active'])) ? $user_update['active'] : 0;
            $user = $this->keepUserOneAsAdmin($user);
            $user->password         = ($password) ? $password : $user->password;
            $user->save();
            return redirect("users")->withMessage("User Updated");
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }


    public function create()
    {
        parent::show();
        $user = new User();
        return view('users.create', compact('user'))->withMessage("Create User");
    }

    public function store()
    {
        $banner = $this->banner;
        $validator = Validator::make($data = Input::all(), User::$rules);
        $user = new User;
        if ($validator->passes()) {
            $user->email        = $data['email'];
            $user->firstname    = (isset($data['firstname'])) ? $data['firstname'] : '';
            $user->lastname     = (isset($data['lastname'])) ? $data['lastname'] : '';
            $user->admin        = (isset($data['admin'])) ? 1 : 0;
            $user->active       = (isset($data['active'])) ? 1 : 0;
            $user->password     = Hash::make($data['password']);
            $user->save();
            return redirect("users")->withMessage("User Created");
        } else {
            return redirect('users/create')->withErrors($validator)
            ->withMessage("Error creating user")
            ->withInput(Input::except('password'));
        }
    }

    protected function keepUserOneAsAdmin($user)
    {
        if ($user->id == 1) {
            $user->admin = 1;
        }
        return $user;
    }

    public function authenticate()
    {
        //Auth::loginUsingId(1);return redirect('/admin')->with('message', 'You are now logged in!');
        if (Auth::attempt(['email'=>Input::get('email'), 'password'=>Input::get('password')])) {
            return redirect('/admin')->with('message', 'You are now logged in!');
        } else {
            Session::set('type', 'danger');
            return redirect('login')
            ->with('message', 'Your username/password combination was incorrect')
            ->withInput();
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->admin && $id > 2) {
            User::destroy($id);
            return redirect('users');
        } else {
            return Redirect::back()->withMessage("You can not delete an admin user");
        }
    }
}
