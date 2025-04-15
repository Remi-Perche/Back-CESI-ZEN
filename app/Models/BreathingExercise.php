<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreathingExercise extends Model
{
    use HasFactory;

    protected $table = 'breathingExercise';

    protected $fillable = ['title', 'inspirationDuration', 'apneaDuration', 'expirationDuration'];

    public $timestamps = true;
}
