<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property-read int $id
 * @property string $title
 * @property int $raw_artist_id
 * @property float $bpm
 * @property int $duration
 * @property string $key
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read Collection<RawTrack> $rawTracks
 * @property-read RawArtist $rawArtist
 * @property-read ?CanonicalArtist $canonicalArtist
 */
class CanonicalTrack extends Model
{
    protected $fillable = [
        'bpm',
        'duration',
        'key',
        'raw_artist_id',
        'title',
    ];

    /**
     * @return HasMany<RawTrack>
     */
    public function rawTracks(): HasMany
    {
        return $this->hasMany(RawTrack::class, 'canonical_track_id', 'id');
    }

    /**
     * @return HasOne<RawArtist>
     */
    public function rawArtist(): HasOne
    {
        return $this->hasOne(RawArtist::class, 'id', 'raw_artist_id');
    }

    /**
     * @return HasOneThrough<CanonicalArtist>
     */
    public function canonicalArtist(): HasOneThrough
    {
        return $this->hasOneThrough(CanonicalArtist::class, RawArtist::class);
    }
}
