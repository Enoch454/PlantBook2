<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    use HasFactory;
    //campos en el objeto "Planta" habilitados para ser llenados
    protected $fillable = [
        'info',
        'nombre',
        'nCientifico',
        'nAlterno',
        'pathImagen',
    ];
    
}
