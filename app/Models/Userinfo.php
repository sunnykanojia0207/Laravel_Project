<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Userinfo extends Authenticatable
{
    public $timestamps = false;
    use HasFactory, Notifiable;
    


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userinfo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phoneno',
        'gender',
        'city',
        'state',
        'country',
        'image',
        'userid'
    ];

    }
