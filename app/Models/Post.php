<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
     protected $table = 'posts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'email',
        'postcontent',
    ];

    public $timestamps = false;

    public function comments()
{
    return $this->hasMany(Comments::class)->with('user'); // Eager load user with comments
}
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
