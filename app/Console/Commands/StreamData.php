<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataProcessingService;

class StreamData extends Command
{
    // The name and signature of the console command
    protected $signature = 'data:stream';

    // The console command description
    protected $description = 'Stream data from the CSV file and process it in real time';

    protected $dataProcessingService;

    // Inject the DataProcessingService dependency
    public function __construct(DataProcessingService $dataProcessingService)
    {
        parent::__construct();
        $this->dataProcessingService = $dataProcessingService;
    }

    public function handle()
    {
        $this->info('Starting data streaming...');

        // Call the processData method in the DataProcessingService
        $this->dataProcessingService->processData();

        $this->info('Data streaming completed.');
    }
}
