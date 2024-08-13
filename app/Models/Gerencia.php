<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{
    use HasFactory;

    protected $table = 'gerencias';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'descripcion',
        'telefono',
        'direccion',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
