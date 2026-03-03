<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // [TAMBAHAN] Tentukan nama tabel secara eksplisit untuk keamanan
    protected $table = 'activity_logs';

    // [TAMBAHKAN INI]
    protected $fillable = ['description'];
}