<?php

namespace App\Observers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\UserInvitedEmail;
use App\Mail\UserApprovedEmail;
use App\Models\UserActivationStatus;
use Illuminate\Support\Facades\Mail;
use App\Services\Users\RequestResetPasswordService;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        if (!$user->password) {
            $user->password = Str::orderedUuid();
        }
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
    }

    /**
     * Handle the User "saving" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function saving(User $user)
    {
        $toStatus = $user->activation_status;

        
        // Status transition to INVITED will send invitation to user.
        // if (($toStatus === UserActivationStatus::INVITED)) {
        //     $user['activation_status']=UserActivationStatus::ACTIVE;
        //     $user->save();
        //     $invitationUrl = $invitationUrl=env("APP_URL", "https://penatani.id")."/reset/sandi/".base64_encode($user->email);
        //     $url = env("APP_URL", "https://penatani.id");
        //     if($user['role']=='ADMIN'){
        //         $invitationUrl=env("APP_URL", "https://penatani.id/nova")."/password/reset/".base64_encode($user->email);
        //         $url = env("APP_URL", "https://penatani.id/nova");
        //     }
        //     try{return Mail::to($user->email)->send(new UserInvitedEmail(['action_url' => $invitationUrl,"email"=>$user->email,'url'=>$url]));}catch(Exception $e){}
            
        // }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
