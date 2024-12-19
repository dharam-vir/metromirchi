<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $table="listing";
    protected $fillable = [];

    public function photos()
    {
        return $this->hasMany(Gallery::class);  // A business can have many photos
    }
}
