<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'availlable', 'sector_id',
                'user_id', 'start_time', 'end_time',
                'total_price'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    
    public function sector() {
        return $this->belongs(Sector::class);
    }

    public function user() {
        return $this->belongs(User::class);
    }
}
