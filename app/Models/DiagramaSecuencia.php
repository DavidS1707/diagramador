<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class DiagramaSecuencia extends Model
{
    use HasFactory;

    public function colaboradores()
    {
        return $this->belongsToMany(User::class, 'colaborador', 'diagrama_secuencia_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    protected $table = 'diagrama_secuencias';

    protected $fillable = [
        'nombre', 'descripcion', 'contenido', 'user_id'
    ];
}
