<?php

namespace App\Console\Commands;

use App\Models\Users\Inscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashPasswords extends Command
{
    protected $signature = 'hash:passwords';
    protected $description = 'Hache les mots de passe des inscriptions non hachées';

    public function handle()
    {
        $inscriptions = Inscription::all();

        $this->info("Hachage de {$inscriptions->count()} mots de passe...");

        foreach ($inscriptions as $inscription) {
            $inscription->password = Hash::make($inscription->password);
            $inscription->save();
        }

        $this->info("✅ Tous les mots de passe ont été hachés !");
        return Command::SUCCESS;
    }
}
