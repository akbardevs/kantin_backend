<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Validator;
use App\Services\Exceptions\InvalidParameterException;
use App\Services\Exceptions\NotAuthenticatedException;
use App\Models\User;
use App\Models\UserActivationStatus;
use App\Models\VerifyMedia;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserService extends Service
{
    const VALIDATION_RULES = [
        'email' => 'required|max:64',
        'password' => 'max:64',
    ];

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function execute(): User 
    {
        $authParams = [
            'email' => $this->email, 
            'password' => $this->password
        ];
        $check=User::where('email',$this->email)->orWhere('username',$this->email)->first();
        if(!$check){
            throw new NotAuthenticatedException("Invalid email or password");
        } else {
            $authParams['email'] = $check->email;
        }

        $validator = Validator::make($authParams, self::VALIDATION_RULES);
        if (!$validator->passes()) {
            throw new InvalidParameterException('Invalid param for sign in', $validator->errors()->messages());
        }

        if (!Auth::attempt($authParams)) {
            throw new NotAuthenticatedException("Invalid email or password");
        }

        $user =  Auth::user();
        if ($user->activation_status !== UserActivationStatus::ACTIVE) {
            throw new NotAuthenticatedException("User is not active");
        }

        return $user;
    }

    public function verifyMedia($idMedia,$type){
        $data = [
            'id_media' => $idMedia, 
            'type' => $type,
            'user_id' => null
        ];
        $authParams = [
            'email' => '', 
            'password' => '11111111'
        ];
        $check=VerifyMedia::where('id_media',$data['id_media'])->first();
        if(!$check || !$check->user_id){
            if(!$check){
                VerifyMedia::create($data);
            } 
            return array('status'=>false,'data'=>$data);
        } else {
            $check=User::find($check['user_id']);
            $authParams['email'] = $check['email'];
            $authParams['password'] = $data['id_media'];
        }

        if(!$check){
            throw new NotAuthenticatedException("Invalid email or passwordss");
        } else {
            $authParams['email'] = $check->email;
        }

        $validator = Validator::make($authParams, self::VALIDATION_RULES);
        if (!$validator->passes()) {
            throw new InvalidParameterException('Invalid param for sign in', $validator->errors()->messages());
        }

        if (!Auth::attempt($authParams)) {
            throw new NotAuthenticatedException("Invalid email or passwordss");
        }

        $user =  Auth::user();
        if ($user->activation_status !== UserActivationStatus::ACTIVE) {
            throw new NotAuthenticatedException("User is not active");
        }
        return array('user'=>$user,'status'=>true);
    }
}
