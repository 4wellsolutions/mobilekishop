<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertRating extends Model
{
    protected $fillable = [
        'product_id',
        'design',
        'display',
        'performance',
        'camera',
        'battery',
        'value_for_money',
        'overall',
        'verdict',
        'rated_by',
    ];

    protected $casts = [
        'design' => 'float',
        'display' => 'float',
        'performance' => 'float',
        'camera' => 'float',
        'battery' => 'float',
        'value_for_money' => 'float',
        'overall' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate overall score as the average of all 6 criteria.
     */
    public function calculateOverall(): float
    {
        $scores = [
            $this->design,
            $this->display,
            $this->performance,
            $this->camera,
            $this->battery,
            $this->value_for_money,
        ];

        return round(array_sum($scores) / count($scores), 1);
    }

    /**
     * Get the rating label based on overall score.
     */
    public function getLabel(): string
    {
        if ($this->overall >= 9)
            return 'Exceptional';
        if ($this->overall >= 8)
            return 'Excellent';
        if ($this->overall >= 7)
            return 'Very Good';
        if ($this->overall >= 6)
            return 'Good';
        if ($this->overall >= 5)
            return 'Average';
        return 'Below Average';
    }

    /**
     * Get the color class based on a score value.
     */
    public static function getColorClass(float $score): string
    {
        if ($score >= 8)
            return 'bg-emerald-500';
        if ($score >= 6)
            return 'bg-blue-500';
        if ($score >= 4)
            return 'bg-yellow-500';
        return 'bg-red-500';
    }
}
