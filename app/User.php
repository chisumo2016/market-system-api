<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    const  VERIFIED_USER = 1;
    const  UNVERIFIED_USER = 0;

    const   ADMIN_USER   ='true';
    const   REGULAR_USER ='false';

    protected  $table = 'users';
    protected  $dates = ['deleted_at'];

    public $transformer = UserTransformer::class;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];


    public  function  isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public  function  isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public  static  function  genarateVerificationCode()
    {
        return str_random(40);
    }

    //Mutator

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
    }

    //Accessor

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function  setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }
}
