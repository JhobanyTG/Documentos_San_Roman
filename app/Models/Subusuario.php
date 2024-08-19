<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subusuario extends Model
{
    use HasFactory;

    protected $table = 'subusuarios';

    protected $fillable = [
        'sub_gerencia_id',
        'usuario_id',
        'cargo',
    ];

    // public function subgerencia()
    // {
    //     return $this->belongsTo(Subgerencia::class, 'sub_gerencia_id');
    // }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'usuario_id');
    // }
    public function subgerencia()
    {
        return $this->belongsTo(Subgerencia::class, 'sub_gerencia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
