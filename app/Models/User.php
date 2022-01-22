<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password','role_id','address','phone_number','department','image','education','description','gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Function connecting to role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role(){
        return $this->hasOne('App\Models\Role','id','role_id');
    }

    /**
     * Function connecting to avatar
     *
     * @param $request
     * @return mixed
     */
    public function userAvatar( $request, $userId ){
        $image = $request->file('image');
        $extension = $image->extension();
        $name  = $image->hashName();
        $name  = "$userId.$extension";

        $destination = public_path('/images');
        $image->move( $destination, $name );

        return $name;
    }

}
