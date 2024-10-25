<?php

namespace App\Services;

use League\Csv\Reader;
use Illuminate\Support\Facades\Http;
use App\Models\DataPoint;
use App\Models\Machine;
use App\Models\Alert;
use Illuminate\Support\Facades\Log;

class DataProcessingService
{
    protected $csvFile;
    protected $flaskUrl;

    public function __construct($csvFilePath, $flaskUrl)
    {
        $this->csvFile = $csvFilePath;
        $this->flaskUrl = $flaskUrl;
    }

    public function processData()
    {
        $csv = Reader::createFromPath($this->csvFile, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $this->processRecord($record);
            sleep(1);  // Optional: simulate real-time streaming delay
        }
    }

    protected function processRecord($record)
    {
        // Send data to the AI model for anomaly detection
        $response = Http::post($this->flaskUrl, [
            'timestamp' => $record['Timestamp'],
            'kpi_value' => $record['KPI_Value'],
            'kpi_name' => $record['KPI_Name'],
        ]);

        if ($response->successful()) {
            $isAnomaly = $response->json()['status'];

            // Save the data point in DataPoints table
            $dataPoint = DataPoint::create([
                'timestamp' => $record['Timestamp'],
                'kpi_name' => $record['KPI_Name'],
                'kpi_value' => $record['KPI_Value'],
                'status' => $isAnomaly,
            ]);

            // If anomaly detected, create an entry in the Alerts table
            if ($isAnomaly) {
                Alert::create([
                    'data_point_id' => $dataPoint->id,
                    'machine_id' => Machine::pluck('id')->random(),
                    'alert_message' => 'Anomaly detected',
                    'alert_time' => $record['Timestamp'],
                ]);
            }
        } else {
            // Log any issues with the Flask response
            Log::error('Failed to get a response from AI model', [
                'status' => $response->status(),
                'error' => $response->body(),
            ]);
        }
    }
}
