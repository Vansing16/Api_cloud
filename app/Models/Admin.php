<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function postServices()
    {
        return $this->hasMany(PostService::class, 'review_by_admin_id');
    }

    public function freelancerReviews()
    {
        return $this->hasMany(FreelancerReview::class, 'review_by_admin_id');
    }
}
