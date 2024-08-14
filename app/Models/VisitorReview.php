<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorReview extends Model
{
    protected $fillable = ['review', 'clean_review', 'sentiment',];  // Pastikan 'review' bisa diisi
    
}


