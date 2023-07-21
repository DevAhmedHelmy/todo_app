<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDTOCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Data Transfer Object (DTO) file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $dtoNamespace = 'App\\DTO\\' . str_replace('/', '\\', $name);
        $fileName = app_path('DTO/' . str_replace('\\', '/', $name) . 'DTO.php');

        if (File::exists($fileName)) {
            $this->error('DTO file already exists!');
            return;
        }

        $stub = file_get_contents(base_path('stubs/dto.stub'));
        $stub = str_replace(['{{DTONamespace}}', '{{DTOName}}'], [$this->getNamespace($dtoNamespace), $this->getClassName($dtoNamespace)], $stub);

        $dtoDirectory = dirname($fileName);
        if (!File::isDirectory($dtoDirectory)) {
            File::makeDirectory($dtoDirectory, 0777, true, true);
        }

        file_put_contents($fileName, $stub);

        $this->info("DTO file created: {$fileName}");
    }


    private function getNamespace(string $name): string
    {
        // Remove the last word from the namespace
        $segments = explode('\\', $name);
        array_pop($segments);
        return implode('\\', $segments);
    }

    private function getClassName(string $name): string
    {
        $segments = explode('\\', $name);
        return end($segments);
    }
}
