<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    use HasFactory;
    protected $table = 'invitaciones';

    protected $fillable = ['usuario_envia_id', 'usuario_recibe_id', 'diagrama_id', 'aceptada'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'usuario_envia_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'usuario_recibe_id');
    }

    public function sequenceDiagram()
    {
        return $this->belongsTo(DiagramaSecuencia::class, 'diagrama_id');
    }
}
