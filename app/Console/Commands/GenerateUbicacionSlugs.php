<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ubicacion;
use Illuminate\Support\Str;

class GenerateUbicacionSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-ubicacion-slugs';

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
        
        $ubicaciones = Ubicacion::all();

        foreach ($ubicaciones as $u) {
            $slugBase = Str::slug($u->nombre);
            $slug = $slugBase;
            $count = 1;

            while (Ubicacion::where('slug', $slug)->where('id', '!=', $u->id)->exists()) {
                $slug = $slugBase . '-' . $count++;
            }

            $u->slug = $slug;
            $u->save();

            $this->info("Slug generado para {$u->nombre}: {$slug}");
        }

        $this->info('✅ Slugs generados exitosamente.');
    
    }
}
