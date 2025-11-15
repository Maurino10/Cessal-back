<?php

namespace App\Console\Commands;

use App\Models\Users\Inscription;
use Illuminate\Console\Command;

class AcceptInscription extends Command
{
    protected $signature = 'accept:inscription';
    protected $description = 'Hache les mots de passe des inscriptions non hachées';

    public function handle()
    {
        $inscriptions = Inscription::all();

        $this->info("Inscription de {$inscriptions->count()} accepter..");

        foreach ($inscriptions as $inscription) {
            $inscription->status = 1;
            $inscription->save();
        }

        $this->info("✅ Tous les inscriptions ont été acceptée !");
        return Command::SUCCESS;
    }
}
