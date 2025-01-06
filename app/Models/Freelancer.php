<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Freelancer extends Authenticatable
{
    use HasFactory;

    protected $primary_key = 'id';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'professional_role', 'work_type', 'bio', 'profile_image_path', 'resume_path',
    ];
}
