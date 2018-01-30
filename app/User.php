<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const  VERIFIED_USER = 1;
    const  UNVERIFIED_USER = 0;


    const   ADMIN_USER ='true';
    const   REGULAR_USER ='false';

    protected  $table = 'users';



    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified','verification_token', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];


    public  function  isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public  function  unVerified()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public  static  function  genarateVerificationCode()
    {
        return str_random(40);
    }
}
