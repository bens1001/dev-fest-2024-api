<?php

namespace App\Http\Controllers;

use App\Services\DataProcessingService;

class DataController extends Controller
{
    protected $dataProcessingService;

    public function __construct(DataProcessingService $dataProcessingService)
    {
        $this->dataProcessingService = $dataProcessingService;
    }

    public function processData()
    {
        $this->dataProcessingService->processData();
        return response()->json(['message' => 'Data processed successfully.']);
    }
}

