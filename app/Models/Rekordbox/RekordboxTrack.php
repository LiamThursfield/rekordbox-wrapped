<?php

namespace App\Models\Rekordbox;

use App\Enums\DjSoftwareProvider;
use App\Models\RawArtist;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read string ID
 * @property-read string ArtistID
 * @property-read int BPM
 * @property-read int KeyID
 * @property-read int Length
 * @property-read string Title
 * @property-read RekordboxKey $rekordboxKey
 * @property-read RawArtist $rawArtist
 */
class RekordboxTrack extends RekordboxModel
{
    protected $table = 'djmdContent';

    /**
     * @return HasOne<RekordboxKey>
     */
    public function rekordboxKey(): HasOne
    {
        return $this->hasOne(RekordboxKey::class, 'ID', 'KeyID');
    }
}
