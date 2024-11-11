<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
        'gerencia_id',
        'subgerencia_id',
        'access_token',
        'token_expires_at'
    ];

    protected $dates = [
        'token_expires_at'
    ];

    public function generateAccessToken()
    {
        $this->access_token = Str::random(64);
        $this->token_expires_at = now()->addMinutes(30); // Token válido por 30 minutos
        $this->save();

        return $this->access_token;
    }

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
        return $this->belongsTo(Gerencia::class, 'gerencia_id');
    }

    public function subgerencia()
    {
        return $this->belongsTo(Subgerencia::class, 'subgerencia_id');
    }
}
