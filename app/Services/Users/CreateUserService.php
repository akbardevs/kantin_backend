<?php

namespace App\Services\Users;
use App\Models\User;
use App\Services\Service;
use App\Models\VerifyMedia;
use Illuminate\Http\Request;
use App\Models\UserActivationStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InternalErrorException;
use App\Services\Exceptions\InvalidParameterException;
use App\Services\Exceptions\NotAuthenticatedException;

class CreateUserService extends Service
{
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    

    public function execute(): User 
    {
        $realPass = $this->params['password'];
        $this->params['password'] = Hash::make($this->params['password']);
        $user = User::firstOrNew($this->params);
        $check=User::where('email',$user->email)->first();
        if($check){
            throw new NotAuthenticatedException("Email Anda Sudah Terdaftar. Harap Login!");
        } 
        $user->save();
        $authParams = [
            'email' => $user->email, 
            'password' => $realPass
        ];

        if (!Auth::attempt($authParams)) {
            throw new NotAuthenticatedException("Invalid email or password!");
        }

        $user =  Auth::user();
        if ($user->activation_status !== UserActivationStatus::ACTIVE) {
            throw new NotAuthenticatedException("User is not active");
        }

        if (!$user) {
            throw new InternalErrorException('Failed creating user');
        }

        return $user;
    }
    
    public function update(): User 
    {

        $realPass = $this->params['password'];
        $this->params['password'] = bcrypt($this->params['password']);
        $user = User::firstOrNew($this->params);
        $user->save();
        $check=User::where('email',$user->email)->orWhere('username',$user->email)->first();
        if(!$check){
            throw new NotAuthenticatedException("Invalid email or password");
        } 
        $authParams = [
            'email' => $user->email, 
            'password' => $realPass
        ];

        if (!Auth::attempt($authParams)) {
            throw new NotAuthenticatedException("Invalid email or password");
        }

        $user =  Auth::user();
        if ($user->activation_status !== UserActivationStatus::ACTIVE) {
            throw new NotAuthenticatedException("User is not active");
        }

        if (!$user) {
            throw new InternalErrorException('Failed creating user');
        }

        return $user;
    }
}
