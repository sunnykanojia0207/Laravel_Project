<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Userdetail extends Authenticatable
{
    public $timestamps = false;
    use HasFactory, Notifiable;
    public function userinfo()
    {
        return $this->hasOne(Userinfo::class, 'userid');
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userdetails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'lastname', 'email', 'password','dob',
    ];

    }
