<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
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
        'completed_at',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_all_complete' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the User that owns the TodoList.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ListItems for the TodoList.
     */
    public function listItems()
    {
        return $this->hasMany(ListItem::class)->where('user_id', auth()->id())->whereNull('parent_id');
    }
}
