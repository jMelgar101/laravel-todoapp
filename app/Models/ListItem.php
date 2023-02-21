<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
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
        'to_complete_at',
        'completed_at',
        'user_id',
        'todo_list_id',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_complete' => 'boolean',
        'to_complete_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'todoList',
    ];

    /**
     * Get the User that owns the ListItem.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the TodoList that owns the ListItem.
     */
    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }

    /**
     * Get nested ListItems
     */
    public function subListItems()
    {
        return $this->hasMany(ListItem::class, 'parent_id')->where('user_id', auth()->id());
    }
}
