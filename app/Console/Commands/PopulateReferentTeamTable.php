<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateReferentTeamTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-referent-team-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'popola la tabella referent team';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::statement('
            INSERT INTO referent_team (team_id, referent_id, created_at, updated_at)
            SELECT team_id, id, NOW(), NOW() FROM referents
        ');

        $this->info('popolamento tabella completato');
    }
}
