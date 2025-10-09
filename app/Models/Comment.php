<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'from_role',
        'to_role',
        'subject',
        'message',
        'priority',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForRole($query, $role)
    {
        return $query->where('to_role', $role);
    }

    public function scopeFromRole($query, $role)
    {
        return $query->where('from_role', $role);
    }

    // Accessors
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'badge-secondary',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger',
        ];

        return $badges[$this->priority] ?? 'badge-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'in_progress' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-secondary',
        ];

        return $badges[$this->status] ?? 'badge-warning';
    }

    public function getIsUnreadAttribute()
    {
        return is_null($this->read_at);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Methods
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    public function hasReplies()
    {
        return $this->replies()->count() > 0;
    }

    public function getFormattedPriorityAttribute()
    {
        return ucfirst($this->priority);
    }

    public function getFormattedStatusAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}
