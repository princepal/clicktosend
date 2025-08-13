<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Loadboard extends Model
{
    protected $fillable = [
        'name',
        'url',
        'logo'
    ];

    /**
     * The users that belong to the loadboard.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
}
