<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory;

    protected $table = 'informations';

    protected $fillable = ['title', 'description', 'menu_id'];

    public $timestamps = true;

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
