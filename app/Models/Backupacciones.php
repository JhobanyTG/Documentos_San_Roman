<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupAccion extends Model
{
    use HasFactory;

    protected $table = 'backupacciones';

    protected $fillable = [
        'user_id',
        'titulo_documento',
        'accion',
    ];
}
