<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GeneralApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use GeneralApiTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::simplePaginate(20);
        $msg = ['users' => $users];
        return $this->returnSuccessMessage( $msg , 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $msg = ['user' => $user];
        return $this->returnSuccessMessage($msg , 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->email = $request->get('email') ?? $user->email;
        $user->name = $request->get('name') ?? $user->name;
        $user->password = Hash::make($request->get('password')) ?? $user->password;
        $user->save();
        $msg = ['profile updated successfully' , 'profile' => $user];
        return $this->returnSuccessMessage($msg , 200);
    }


}
