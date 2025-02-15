<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Backup\Config\Config; // ¡Añade esta línea!
use Spatie\Backup\Commands\BackupCommand;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Symfony\Component\Console\Output\BufferedOutput;

class BackupController extends Controller
{
    public function create()
    {
        try {
            // Ejecutar el comando de backup
            \Artisan::call('backup:run', [], $output = new BufferedOutput());

            // Opcional: Obtener la salida del comando
            $result = $output->fetch();

            return redirect()->back()->with('success', 'Backup creado correctamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
