<?php

namespace App\Models; //menunjukkan file ini berada di App/Models

use Illuminate\Database\Eloquent\Factories\HasFactory; //import hasfactory
use Illuminate\Database\Eloquent\Model; //import model

class Item extends Model // Class Item mewarisi dari class Model
{
    use HasFactory;
    protected $fillable = [ //atribut yang bisa diisi
        'name',
        'description',
    ];
}
