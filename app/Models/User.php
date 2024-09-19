<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nombre_usuario',
        'email',
        'password',
        'estado',
        'rol_id',
        'persona_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->persona()->delete();
        });
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function subusuarios()
    {
        return $this->hasMany(Subusuario::class);
    }

    public function gerencia()
    {
        return $this->hasOne(Gerencia::class, 'usuario_id');
    }

    public function subgerencias()
    {
        return $this->hasManyThrough(Subgerencia::class, Gerencia::class, 'usuario_id', 'gerencia_id', 'id', 'id');
    }


}
