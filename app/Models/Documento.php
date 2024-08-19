<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'sub_usuarios_id',
        'titulo',
        'descripcion',
        'archivo',
        'estado',
    ];

    public function subusuario()
    {
        return $this->belongsTo(Subusuario::class, 'sub_usuarios_id');
    }
}
