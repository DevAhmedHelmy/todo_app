<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $repositoryNamespace = 'App\\Repositories\\' . str_replace('/', '\\', $name);
        $fileName = app_path('Repositories/' . str_replace('\\', '/', ucfirst($name)) . 'Repository.php');

        if (File::exists($fileName)) {
            $this->error('repository file already exists!');
            return;
        }

        $stub = file_get_contents(base_path('stubs/repository.stub'));
        $stub = str_replace(['{{RepositoryNamespace}}', '{{RepositoryName}}'], [$this->getNamespace($repositoryNamespace), $this->getClassName($repositoryNamespace)], $stub);

        $repositoryDirectory = dirname($fileName);
        if (!File::isDirectory($repositoryDirectory)) {
            File::makeDirectory($repositoryDirectory, 0777, true, true);
        }

        file_put_contents($fileName, $stub);

        $this->info("Repository file created: {$fileName}");
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
        $segments =  explode('\\', $name);
        $className = Str::singular(end($segments));
        return ucfirst($className);
    }
}
