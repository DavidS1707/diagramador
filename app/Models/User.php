<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function diagramasSecuencia()
    {
        return $this->hasMany(DiagramaSecuencia::class, 'user_id');
    }

    public function colaboraciones()
    {
        return $this->belongsToMany(DiagramaSecuencia::class, 'colaborador', 'user_id', 'diagrama_secuencia_id');
    }

    public function invitaciones()
    {
        // Suponiendo que tienes una tabla "invitaciones" que registra las invitaciones
        return $this->hasMany(Invitacion::class);
    }

    public function invitacionesPendientes()
    {
        // Filtra las invitaciones pendientes
        return $this->invitaciones->where('aceptada', false);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
