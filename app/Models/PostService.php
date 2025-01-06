<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbmail_url',
        'rate_value',
        'rate_unit',
        'cost',
        'desciption',
        'freelancer_id',
        'review_status',
        'review_by_admin_id',
    ];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'review_by_admin_id');
    }

    public function contactForms()
    {
        return $this->hasMany(ContactForm::class, 'post_service_id');
    }
}
