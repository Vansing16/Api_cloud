<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerInfo extends Model
{
    use HasFactory;

    protected $primaryKey = 'freelancer_id';

    protected $fillable = [
        'freelancer_id',
        'professional_role',
        'skill_detail',
        'bio_detail',
    ];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
