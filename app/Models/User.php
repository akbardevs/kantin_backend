<?php

namespace App\Models;

use App\Services\Base64Image;
use Carbon\Carbon;
use Exception;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $attributes = [
        'activation_status' => UserActivationStatus::INVITED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'name',
        'email',
        'password',
        'username',
        'phone',
        'role',
        'activation_status',
        'village_id',
        'jkel',
        
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleted(function($model1)
        {
            $model1->verify_media->each->delete();
            $model1->qa->each->delete();
            $model1->information->each->delete();
            $model1->promotion->each->delete();
        });
    }

    public function verify_media()
    {
        return $this->hasMany(VerifyMedia::class);
    }

    public function qa()
    {
        return $this->hasMany(Qa::class);
    }

    public function information()
    {
        return $this->hasMany(Information::class);
    }

    public function promotion()
    {
        return $this->hasMany(Promotion::class);
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function getImageAttribute($string)
    {
        if($string && $string!=null){
            $pos = strpos($string, 'http');
            if ($pos === false) {
                $image = new Base64Image;
                $image->compress($string);
                $checkFile = $image->check($string);
                if($checkFile)return '/storage/reduce/default.png';
                return "/imagefly/w100-h100/storage/".$string;
            } else {
                return json_decode($string,true);
            }
        } else {
            return "/storage/reduce/default.png";
        }
    }
    
    
    public function getUpdatedAtAttribute($string)
    {
        $res = Carbon::parse($string)->diffForHumans();
        return $res;
    }
    public function getCreatedAtAttribute($string)
    {
        $res = Carbon::parse($string)->diffForHumans();
        $start = Carbon::parse($string);
        $end = Carbon::now();
        $duration = $end->diffInHours($start);
        if($duration>=24){
            return Carbon::parse($string)->format('d M Y');
        } else {
            return $res ;
        }
    }

}
