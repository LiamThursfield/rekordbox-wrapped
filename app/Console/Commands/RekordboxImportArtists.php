<?php

namespace App\Console\Commands;

use App\Enums\DjSoftwareProvider;
use App\Models\CanonicalArtist;
use App\Models\RawArtist;
use App\Models\Rekordbox\RekordboxArtist;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;

class RekordboxImportArtists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekordbox:import-artists {--skip-canonical}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the artists from the rekordbox database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $existingArtists = RawArtist::query()
            ->select('provider_id')
            ->where('provider', DjSoftwareProvider::REKORDBOX)
            ->pluck('provider_id');

        $newArtistsCount = 0;

        RekordboxArtist::query()->whereNotIn(
            'ID',
            $existingArtists
        )->chunk(500, function (Collection $artistCollection) use (&$newArtistsCount) {
            $artistCollection->each(function (RekordboxArtist $rekordboxArtist) use (&$newArtistsCount) {
                $name = Str::trim($rekordboxArtist->Name);

                $this->info("Creating Artist: $name ($rekordboxArtist->ID)", OutputInterface::VERBOSITY_VERBOSE);

                $canonicalArtist = null;
                if (! $this->option('skip-canonical')) {
                    $this->info('Creating Canonical Artist', OutputInterface::VERBOSITY_VERY_VERBOSE);

                    $canonicalArtist = CanonicalArtist::query()->create([
                        'name' => $name,
                    ]);
                }

                RawArtist::query()->create([
                    'name' => $name,
                    'canonical_artist_id' => $canonicalArtist?->id ?? null,
                    'provider' => DjSoftwareProvider::REKORDBOX,
                    'provider_id' => $rekordboxArtist->ID,
                ]);

                $newArtistsCount++;
            });
        });

        $this->info("Imported $newArtistsCount new artists");

        return self::SUCCESS;
    }
}
