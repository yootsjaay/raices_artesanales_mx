<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categoria;
use Illuminate\Support\Str;
class GenerarSlugsCategorias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generar-slugs-categorias';

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
       
        $categorias = Categoria::all();
        $generados = 0;

        foreach ($categorias as $categoria) {
            if (!$categoria->slug || $categoria->slug === '') {
                $baseSlug = Str::slug($categoria->nombre);
                $slug = $baseSlug;
                $count = 1;

                while (Categoria::where('slug', $slug)->where('id', '!=', $categoria->id)->exists()) {
                    $slug = $baseSlug . '-' . $count;
                    $count++;
                }

                $categoria->slug = $slug;
                $categoria->save();
                $this->info("✅ {$categoria->nombre} → {$slug}");
                $generados++;
            }
        }

        if ($generados === 0) {
            $this->warn('⚠️ No se generó ningún slug. Ya estaban todos listos.');
        } else {
            $this->info("🎯 Slugs generados correctamente para {$generados} categoría(s).");
        }
    
    }
}
