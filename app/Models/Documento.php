<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'user_id',
        'sub_usuarios_id',
        'tipodocumento_id',
        'titulo',
        'descripcion',
        'archivo',
        'estado',
    ];

    public function subusuario()
    {
        return $this->belongsTo(Subusuario::class, 'sub_usuarios_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipodocumento_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function subgerencia()
    {
        return $this->belongsTo(Subgerencia::class);
    }
}
