<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property string $name
 * @property-read Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<RawArtist> $rawArtists
 */
class CanonicalArtist extends Model
{
    protected $fillable = [
        'bpm',
        'duration',
        'key',
        'name',
    ];

    /**
     * @return HasMany<RawArtist>
     */
    public function rawArtists(): HasMany
    {
        return $this->hasMany(RawArtist::class, 'canonical_artist_id', 'id');
    }
}
