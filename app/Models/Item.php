<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_complete',
        'to_complete_by_date',
        'to_complete_by_time',
        'completed_at',
        'user_id',
        'checklist_id',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_complete' => 'boolean',
        'to_complete_by_date' => 'datetime',
        'to_complete_by_time' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'checklist',
    ];

    /**
     * Get the User that owns the Item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Checklist that owns the Item.
     */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Get nested Items
     */
    public function subItems()
    {
        return $this->hasMany(Item::class, 'parent_id')->where('user_id', auth()->id());
    }
}
