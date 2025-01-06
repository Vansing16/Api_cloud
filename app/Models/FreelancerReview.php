<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerReview  extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_by_admin_id',
        'freelancer_id',
        'comment',
        'rating',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'review_by_admin_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
