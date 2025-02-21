<?php

namespace App\Models;

use App\Exceptions\InvalidLoginException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'Cashiers';
    protected $primaryKey = 'Cashier_ID';
    //protected $connection = 'enablerDb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Cashier_Number',
        'Cashier_Psw',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
        'Cashier_Psw'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'Cashier_Name',
        // 'test_del',
        // 'bof',
    ];

    /**
     * Accessors
     */
    public function getCashierNameAttribute(){
        return trim($this->attributes['Cashier_Name']);
    }
    // public function getTestDelAttribute(){
    //     return trim($this->attributes['Cashier_Test_Del']);
    // }
    // public function getBofAttribute(){
    //     return trim($this->attributes['Cashier_BOF']);
    // }

    /**
     * Logic
     */
    public static function login($number, $password){
        $user =  static::where('Cashier_Number', $number)
            ->where('Cashier_Psw', $password)
            ->first();

        if(!$user){
            throw new InvalidLoginException('Invalid Login');
        }

        return $user;
    }

    /**
     * Scopes
     */
}
