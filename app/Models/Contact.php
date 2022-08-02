<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\contact as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class Contact extends Model
{
    use HasFactory;
    protected $table='contacts';

    protected $fillable = [
        'name',
        'email',
        'address',
        'city',
        'country',
         ];
    }
