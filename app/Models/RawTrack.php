<?php

namespace App\Models;

use App\Enums\DjSoftwareProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $title
 * @property int $raw_artist_id
 * @property float $bpm
 * @property int $duration
 * @property string $key
 * @property int $canonical_track_id
 * @property DjSoftwareProvider $provider
 * @property string $provider_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read ?RawArtist $rawArtist
 */
class RawTrack extends Model
{
    protected $fillable = [
        'bpm',
        'canonical_track_id',
        'duration',
        'key',
        'provider',
        'provider_id',
        'raw_artist_id',
        'title',
    ];

    protected $casts = [
        'provider' => DjSoftwareProvider::class,
    ];

    /**
     * @return BelongsTo<RawArtist>
     */
    public function rawArtist(): BelongsTo
    {
        return $this->belongsTo(RawArtist::class, 'id', 'raw_artist_id');
    }
}
