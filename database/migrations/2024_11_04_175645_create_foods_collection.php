<?php

use App\Services\CachingService\CachingService;
use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->resetCache();

        Schema::connection('mongodb')->create('foods', function (Blueprint $collection) {
            $collection->index('code');
            $collection->dateTime('imported_t');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->resetCache();
        Schema::connection('mongodb')->dropIfExists('foods');
    }

    private function resetCache() : void{
        //Limpar Cache
        $redisService = app(CachingService::class);
        $redisService->reset();
    }
};
