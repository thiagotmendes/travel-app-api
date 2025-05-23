<?php

namespace App\Models;

use App\Domain\TravelRequest\Enums\TravelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;

    protected $table = 'travel_requests';
    protected $fillable = [
        'destination',
        'departure_date',
        'return_date',
        'status',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'status' => TravelStatus::class,
    ];
}
