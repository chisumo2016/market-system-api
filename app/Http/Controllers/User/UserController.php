<?php

namespace App\Http\Controllers\User;

use App\Mail\UserCreated;
use App\Seller;
use App\Traits\ApiResponser;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function  __construct()
    {
        //parent::__construct();
        $this->middleware('client.credentials:')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
        $this->middleware('can:view, user')->only('show');
        $this->middleware('can:update, user')->only('update');
        $this->middleware('can:delete, user')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return $this->showAll($users);
        //return  response()->json(['data' => $users], 200);  //return $users;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request , $rules);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::genarateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201);
        //return  response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
           return $this->showOne($seller);

        //
        //$user = User:: findOrFail($user); //We cant use anymore as we're using model binding
        //return  response()->json(['data' => $user], 200);  //return $users;


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
    public function update(Request $request, User  $user)
    {
        //
        //$user = User:: findOrFail($id);  //Bse of Model Binding

        $rules = [
            'email' => 'email|unique:users, email' . $user->id,
            'password' => 'min:6|confirmed',
            'admin'  => 'in:' .User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        //Verify
        if ($request->has('name')){
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request ->email){
            $user->verified = User:: UNVERIFIED_USER;
            $user->verification_token = User::genarateVerificationCode();
            $user->email = $request ->email;
        }
        if ($request->has('password')) {
            $user ->password = bcrypt($request->password);
        }

        if ($request->has('admin')){
            if (!$user ->isVerified()) {

                    return $this->errorResponse('Only verified users can modify the admin field ', 409);
                //return response()->json(['error' => 'Only verified users can modify the admin field ' , 'code' => 409], 409 );
            }

            // Assign the value
            $user->admin = $request -> admin;
        }
        // Any changes in the model
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update ', 422);
            //return response()->json(['error' => 'You need to specify a different value to update ' , 'code' => 422], 422 );
        }

        $user->save();

        return $this->showOne($user);

        //return  response()->json(['data' => $user], 200);  //return $users;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        //$user = User::findOrFail($id);
        $user->delete();

        return $this->showOne($user);

        //return  response()->json(['data' => $user], 200);
    }

    public  function  verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        //Modified the status
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified successfully');
    }

    public function  resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This is user is already verified ', 409);
        }


        retry(5, function() use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        },100);

        //Mail::to($user)->send(new UserCreated($user));

        return $this->showMessage('The verification email has been resend');
    }
}
