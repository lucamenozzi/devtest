<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneReferentsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-referents-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete referents duplication restoring unique records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $referentsTempArray = [];
        // prendo tutta la tabella referents e la scorro ordinata per id ASC
        $referentsToPrune = DB::select('select * from referents order by id ASC');

        // costruisco array con chiave name_lastname_phone_email e valori i vari ids
        foreach ($referentsToPrune as $referent) {
            $referentsTempArray[$referent->name.$referent->last_name.$referent->email.$referent->phone][] = $referent->id;
        }

        $bar = $this->output->createProgressBar(count($referentsTempArray));

        $bar->start();
        foreach ($referentsTempArray as $userInfo => $ids) {
            // estraggo il primo id
            $idToKeep = array_shift($ids);
            // tengo tutti gli altri da eliminare
            $idsToPrune = $ids;

            if (!empty($idsToPrune)) {
                // sostituisco gli id da eliminare con quello da tenere in referent_shipment
               DB::table('referent_shipment')
                    ->whereIn('referent_id', $idsToPrune)
                    ->update(['referent_id' => $idToKeep]);
            }

            // cancello tutti i referent con id restanti in referents
            DB::table('referents')
                ->whereIn('id', $idsToPrune)
                ->delete();

            $bar->advance();
        }
        $bar->finish();

        $this->info('Processo di pulizia terminato');
    }
}
