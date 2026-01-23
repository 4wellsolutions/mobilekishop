<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_name',
        'country_code',
        'icon',
        'currency',
        'iso_currency',
        'currency_name',
        'domain',
        'locale',
        'is_menu',
        'is_active',
        'amazon_url',
        'amazon_tag',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }
    // In your Country model
    public function news()
    {
        return $this->hasMany(News::class, 'country_id');
    }

}
