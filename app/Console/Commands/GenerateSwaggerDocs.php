<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateSwaggerDocs extends Command
{
    protected $signature = 'swagger:generate';
    protected $description = 'Gera doc do swagger';

    public function handle()
    {
        $openapi = Generator::scan([app_path('Http/Controllers/Api')]);
        $openapi->saveAs(public_path('swagger.json'));
        $this->info('Swagger documentation generated successfully.');
    }
}
