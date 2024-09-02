<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla
    protected $table = 'tipodocumento';

    // Especifica el nombre de la clave primaria
    protected $primaryKey = 'id_tipodocumento';

    // Permite la asignación masiva en estos campos
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

}
