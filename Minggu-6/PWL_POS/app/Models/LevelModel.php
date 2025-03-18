<?php

namespace App\Models; // Pastikan namespace ini benar

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Pastikan tabel benar
    protected $primaryKey = 'level_id';

    protected $fillable = ['level_id', 'nama_level'];
}