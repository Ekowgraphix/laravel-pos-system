<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'criteria', 'customer_count', 'is_active'];
    protected $casts = ['criteria' => 'array', 'is_active' => 'boolean'];

    public function users() {
        return $this->belongsToMany(User::class, 'customer_segment_user')
                    ->withPivot('assigned_at');
    }

    public function assignUser($userId)
    {
        if (!$this->users()->where('user_id', $userId)->exists()) {
            $this->users()->attach($userId, ['assigned_at' => now()]);
            $this->increment('customer_count');
        }
    }

    public function removeUser($userId)
    {
        if ($this->users()->where('user_id', $userId)->exists()) {
            $this->users()->detach($userId);
            $this->decrement('customer_count');
        }
    }

    public function refreshCustomers()
    {
        $users = User::where('role', 'user')->get();
        $matchedUsers = [];

        foreach ($users as $user) {
            if ($this->matchesCriteria($user)) {
                $matchedUsers[] = $user->id;
            }
        }

        $this->users()->sync($matchedUsers);
        $this->update(['customer_count' => count($matchedUsers)]);
    }

    private function matchesCriteria($user): bool
    {
        if (!$this->criteria) return false;

        foreach ($this->criteria as $key => $value) {
            switch ($key) {
                case 'min_total_spent':
                    if ($user->total_spent < $value) return false;
                    break;
                case 'min_orders':
                    if ($user->total_orders < $value) return false;
                    break;
                case 'customer_type':
                    if ($user->customer_type !== $value) return false;
                    break;
                case 'inactive_days':
                    if ($user->last_purchase_at && $user->last_purchase_at->diffInDays(now()) < $value) return false;
                    break;
            }
        }

        return true;
    }
}
