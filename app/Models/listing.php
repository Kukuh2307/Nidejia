<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "title", "slug", "description", "address", "sqft", "wifi_speed", "max_person", "price_per_day", "price_per_week", "price_per_month", "price_per_year", "attachments", "full_support_available", "gym_area_available", "mini_cafe_available", "cinema_available"
    ];

    protected $casts = [
        "attachments" => "array"
    ];

    // FUNGSI MENDAPATKAN ROUTE
    public function getRouteKeyName()
    {
        return "slug";
    }

    // FUNGSI MENGUBAH JUDUL MENJADI SLUG
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
