<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordRequest;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Models\User;
use App\Traits\GeneralApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    use GeneralApiTrait;
    public function update(UpdatePasswordRequest $request)
    {
        $user = User::where('email' , $request->get('email'))->first();
        if(!$user)
            return $this->returnError(404 , 'User Not Found');
        $user->password = Hash::make($request->get('password'));
        $user->save();
        $msg = 'password updated successfully';
        return $this->returnSuccessMessage($msg , 200);
    }
}
