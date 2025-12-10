<?php

namespace App\Models;

use App\Enums\DjSoftwareProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property string $name
 * @property int $canonical_artist_id
 * @property DjSoftwareProvider $provider
 * @property string $provider_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read ?CanonicalArtist $canonicalArtist
 * @property-read Collection<RawTrack< $rawTracks
 */
class RawArtist extends Model
{
    protected $fillable = [
        'canonical_artist_id',
        'name',
        'provider',
        'provider_id',
    ];

    protected $casts = [
        'provider' => DjSoftwareProvider::class,
    ];

    /**
     * @return BelongsTo<CanonicalArtist>
     */
    public function canonicalArtist(): BelongsTo
    {
        return $this->belongsTo(CanonicalArtist::class, 'id', 'canonical_artist_id');
    }

    /**
     * @return HasMany<RawTrack>
     */
    public function rawTracks(): HasMany
    {
        return $this->hasMany(RawTrack::class, 'raw_artist_id', 'id');
    }
}
