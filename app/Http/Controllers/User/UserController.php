<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequset;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['store', 'resend', 'verify']);
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('transform.input:' .UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();

        $users = User::all();

        //return response()->json(['data' => $users], 200);

        return $this->showAll($users);
    }

    public function me(Request $request)
    {
        $user = $request->user();



        return $this->showOne($user);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|max:150',
            'email' => 'required|email|max:200|unique:users',
            'password' => 'required|min:5|confirmed'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['password'] = bcrypt($request->input('password'));
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;



        $user = User::create($data);

        return $this->showOne($user, 'Following user added successfully',201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        $this->allowedAdminAction();

        //$user = User::findOrFail($id); route model inding
        //die($user);
        $rules = [
            'email' => 'email|max:200|unique:users,email,' . $user->id,
            'password' => 'min:5|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse(' verified users can change this field');
                /*return response()->json(['error' => 'Only verified users can change this field'
                    , 'code' => 409], 409);*/
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse(' You neet to specify a different value to update', 422);
           /* return response()->json(['error' => 'You neet to specify a different value to update'
                , 'code' => 422], 422);*/
        }

         $user->save();

        return $this->successResponse($user,'Bellow record Updated successfully');

        /*return response()->json([
            'message' => 'Bellow record Updated successfully',
            'data' => $user

        ]);*/


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse($user,'Bellow record deleted successfully');

        /*return response()->json([
            'message' => 'Bellow record deleted successfully',
            'data' => $user

        ]);*/
    }

    public function verify($token) {

        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified successfully');
    }

    public function resend(User $user) {

        if($user->isVerified()) {
           return $this->showMessage('This user is already verified', 409);
        }else {
            Mail::to($user)->send(new UserCreated());
            return $this->showMessage('Verification email has resend please check your mail box', 409);
        }
    }
}
