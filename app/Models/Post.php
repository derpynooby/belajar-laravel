<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // c:\xampp\htdocs\Aldin_S\app\Models\Post.php
    // c:\xampp\htdocs\Aldin_S\database\migrations\2024_02_28_125332_create_posts_table.php

    protected $fillable = [
        'image',
        'title',
        'content',
    ];
}
