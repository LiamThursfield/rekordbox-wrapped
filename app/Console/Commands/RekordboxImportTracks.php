<?php

namespace App\Console\Commands;

use App\Enums\DjSoftwareProvider;
use App\Models\CanonicalTrack;
use App\Models\RawTrack;
use App\Models\Rekordbox\RekordboxTrack;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;

class RekordboxImportTracks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekordbox:import-tracks {--skip-canonical}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the tracks from the rekordbox database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $existingTracks = RawTrack::query()
            ->select('provider_id')
            ->where('provider', DjSoftwareProvider::REKORDBOX)
            ->pluck('provider_id');

        $newTrackCount = 0;

        RekordboxTrack::query()->whereNotIn(
            'ID',
            $existingTracks
        )->chunk(500, function (Collection $trackCollection) use (&$newTrackCount) {
            $trackCollection->load(['rekordboxKey', 'rawArtist']);
            $trackCollection
                ->filter(function (RekordboxTrack $rekordboxTrack) {
                    // Skip any tracks with an empty title
                    $title = Str::trim($rekordboxTrack->Title);
                    if (Str::length($title) === 0) {
                        $this->warn("Skipping track: $rekordboxTrack->ID as it has no title", OutputInterface::VERBOSITY_VERBOSE);

                        return false;
                    }

                    // Skip any tracks that we have yet to import the artist for
                    if ($rekordboxTrack->ArtistID !== null && $rekordboxTrack->rawArtist === null) {
                        $this->warn("Skipping track: $title ($rekordboxTrack->ID) as raw artist with ID $rekordboxTrack->ArtistID doesn't exist");

                        return false;
                    }

                    return true;
                })->each(function (RekordboxTrack $rekordboxTrack) use (&$newTrackCount) {
                    $title = Str::trim($rekordboxTrack->Title);

                    $this->info("Creating Track: $title ($rekordboxTrack->ID)", OutputInterface::VERBOSITY_VERBOSE);

                    $baseData = [
                        'title' => $title,
                        'raw_artist_id' => $rekordboxTrack->rawArtist?->id,
                        'bpm' => $rekordboxTrack->BPM / 100,
                        'duration' => $rekordboxTrack->Length,
                        'key' => $rekordboxTrack->rekordboxKey?->ScaleName,
                    ];

                    $canonicalTrack = null;
                    if (! $this->option('skip-canonical')) {
                        $this->info('Creating Canonical Track', OutputInterface::VERBOSITY_VERY_VERBOSE);
                        $canonicalTrack = CanonicalTrack::query()->create($baseData);
                    }

                    RawTrack::query()->create([
                        ...$baseData,
                        'canonical_track_id' => $canonicalTrack?->id,
                        'provider' => DjSoftwareProvider::REKORDBOX,
                        'provider_id' => $rekordboxTrack->ID,
                    ]);

                    $newTrackCount++;
                });
        });

        $this->info("Imported $newTrackCount new tracks");

        return self::SUCCESS;
    }
}
