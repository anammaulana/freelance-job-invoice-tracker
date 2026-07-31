<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable(['name', 'email', 'phone_number', 'company', 'address'])]
class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function activeProjects(): HasMany
    {
        return $this->projects()->where('status', Project::STATUS_ACTIVE);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'attachable');
    }
}
