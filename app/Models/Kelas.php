<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {
    protected $fillable = ['nama_kelas', 'wali_guru_id'];

    public function siswas() {
        return $this->hasMany(Siswa::class);
    }
}