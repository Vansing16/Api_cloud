<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "message"; // Remove this if your table is named 'messages'

    protected $fillable = [
        'freelancer_id',
        'name',
        'email',
        'message',
        'created_at',
        'updated_at'
    ];    

    // Define relationship with User (assuming freelancer_id refers to users table)
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    // Disable timestamps if not present in the table
    public $timestamps = false;
}
