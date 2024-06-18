<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * I campi che sono assegnabili in massa.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'barber_id', 'date', 'time'];
}
