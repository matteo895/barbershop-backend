<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barber;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creazione di un nuovo parrucchiere con i dati specificati
        Barber::create([
            'name' => 'Francesco',
            'photo' => 'francesco.jpg',
            'description' => 'Parrucchiere esperto con oltre 10 anni di esperienza.',
        ]);

        // Creazione di un altro parrucchiere con dati diversi
        Barber::create([
            'name' => 'Michele',
            'photo' => 'michele.jpg',
            'description' => 'Specializzato in tagli moderni e alla moda.',
        ]);

        // Puoi aggiungere ulteriori chiamate a Barber::create() per inserire più parrucchieri secondo necessità
    }
}
