<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo', 'description'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
