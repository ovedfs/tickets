<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'open',
        'arrendador_id',
        'abogado_id',
    ];

    public function arrendador()
    {
        return $this->belongsTo(User::class, 'arrendador_id');
    }

    public function abogado()
    {
        return $this->belongsTo(User::class, 'abogado_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function ticketResponses()
    {
        return $this->hasMany(TicketResponse::class);
    }
}
