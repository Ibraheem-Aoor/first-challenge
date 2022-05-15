<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Requests\Api\Auth\RegisterNewUserRequest;
use App\Models\User;
use App\Traits\GeneralApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class AuthUserController extends Controller
{

    use GeneralApiTrait;

    /**
     *  signup new user
     * @return \Illuminate\Http\Response
     */
    public function signUp(RegisterNewUserRequest $request)
    {
        $user = $this->registerUser($request);
        $token = $user->createToken($user->name , ['*']);
        $msg = [
            'Note' => 'Use the below token',
            'token' => $token,
            'user' => $user,
        ];
        // return $this->returnSuccessMessage($msg , 200);
        // $factory = (new Factory())
        //     ->withProjectId('first-challnege')
        //     ->withDatabaseUri('https://first-challnege-default-rtdb.firebaseio.com/');
            $database = app('firebase.database');
            $reference = $database->getReference('test/');
            $snapshot = $reference->getSnapshot();
        return $snapshot;
    }

    /**
     * Register The user after validate the request
     * @return \Illuminate\Http\Response
     */
    public function registerUser($request)
    {
        return User::create(
            [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]
        );
    }


    /**
     * login the pre registerd user and generate a new token for him to use
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email' , $request->email)->first()->makeVisible(['password']);
        if(Hash::check($request->get('password') , $user->password ))
            return $this->authorizeUser($user);
        else
            return $this->returnError(403 , 'Password Is Not Correct');
    }

    /**
     * authorize the user after successfully matched password
     *  @return \Illuminate\Http\Response
     */
    public function authorizeUser($user)
    {
        $token = $user->createToken($user->name);
        $user->makeHidden(['password']);
        $msg = [
            'Note' => 'Logged In Successfully',
            'token' => $token,
            'user' => $user,
        ];
        return $this->returnSuccessMessage($msg , 200);
    }

    /**
     * logout
     * @return \Illuminate\Http\Response
    */
    public function logout()
    {
        $user = Auth::guard('sanctum')->user();
        $token = $user->currentAccessToken();
        $token->delete();
        $msg = [
            'message' => 'You\'re Logged Out successfully',
        ];
        return $this->returnSuccessMessage($msg , 200);
    }



}
