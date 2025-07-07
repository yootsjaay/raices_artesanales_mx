<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artesania;
use Illuminate\Support\Str;
class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
    $artesanias = Artesania::all();

    foreach ($artesanias as $a) {
        $slugBase = Str::slug($a->nombre);
        $slug = $slugBase;
        $count = 1;

        while (Artesania::where('slug', $slug)->where('id', '!=', $a->id)->exists()) {
            $slug = $slugBase . '-' . $count;
            $count++;
        }

        $a->slug = $slug;
        $a->save();

        $this->info("Slug generado: {$a->nombre} → {$a->slug}");
    }

    $this->info('✅ Todos los slugs se han generado correctamente.');
    }
}
