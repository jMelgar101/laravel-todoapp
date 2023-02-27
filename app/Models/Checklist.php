<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'is_all_complete',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_all_complete' => 'boolean',
    ];

    /**
     * Get the User that owns the Checklist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Items for the Checklist.
     */
    public function items()
    {
        return $this->hasMany(Item::class)->where('user_id', auth()->id())->whereNull('parent_id');
    }
}
