<?php

namespace App\Services;

use App\Document;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class DocumentService
{
    /**
     * Response service 
     * @var App\Services\ResponseService
     */
    protected $responseService;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->responseService = new ResponseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Services\ResponseService
     */
    public function index() : ResponseService
    {
        $this->responseService->addResult(Document::all());
        return $this->responseService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Services\ResponseService
     */
    public function store(Request $request) : ResponseService
    {
        $validator = \Validator::make($request->json()->all(), [
            'document.*.key' => 'required|string',
            'document.*.type' => 'required|string',
            'document.*.value' => 'required|string'
        ]);

        if($validator->fails()){
            $this->responseService->setStatus($this->responseService::status_failed);
            $this->responseService->setCode($this->responseService::code_failed);
            foreach ($validator->errors()->getMessages() as $item) {
                $this->responseService->addResult($item);
            }
        } else {
            $document = new Document;
            $document->document = json_encode($request->document);
            $document->save();
            $this->responseService->addResult($document);
        }

        return $this->responseService;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return App\Services\ResponseService
     */
    public function show(int $id) : ResponseService
    {
        $document = Document::findOrFail($id);
        $this->responseService->addResult($document);
        return $this->responseService;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return App\Services\ResponseService
     */
    public function update($request, $id) : ResponseService
    {
        $document = Document::findOrFail($id);
        $document->document = json_encode($request->document);
        $document->save();
        $this->responseService->addResult($document);
        return $this->responseService;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return App\Services\ResponseService
     */
    public function delete($id) : ResponseService
    {
        $document = Document::findOrFail($id);
        $document->delete();

        $this->responseService->addResult("Document deleted");
        return $this->responseService;
    }

    /**
     * Export document to CSV.
     *
     * @param  int  $id
     * @return App\Services\ResponseService
     */
    public function export(string $format, int $id)
    {
        switch (strtolower($format)) {
            case 'csv':
                return $this->exportAndDownloadDocToCSV($id);
                break;

            case 'cloud':
                return $this->exportDocToCloud($id);
                break;
            
            default:
                $this->responseService->addMessage("Export format undefined");
                return $this->responseService;
                break;
        }
    }

    /**
     * Export document to a csv file and download it
     * @param  int    $id Document id
     * @return object     Streaming response
     */
    public function exportAndDownloadDocToCSV(int $id)
    {        
        $this->updateExportedAt($id);
        
        return response()->streamDownload(function () use ($id) {
            echo $this->exportDocToCSV($id); 
        }, 'doc_'.$id.'.csv');
    }

    /**
     * Export document to cloud storage
     * @param  int    $id Document id
     * @return Illuminate\Http\JsonResponse
     */
    public function exportDocToCloud(int $id) : JsonResponse
    {
        $contents = $this->exportDocToCSV($id);
        $filename = 'doc_'.$id.'.csv';

        if (Storage::disk('s3')->put($filename, $contents, 'public')) {
            $this->updateExportedAt($id);
            $this->responseService->addResult([
                "url" => Storage::disk('s3')->url($filename)
            ], 'r');
            return response()->json($this->responseService);
        }

        $this->responseService->addMessage('Unable to export file to cloud storage');
        $this->responseService->setStatus($this->responseService::status_failed);
        $this->responseService->setCode($this->responseService::code_failed);
        $this->responseService->addResult(null, 0);
        return response()->json($this->responseService);
    }

    /**
     * Export stored document for download as a comma separated text file
     * @param  int    $id Document id
     * @return string     CSV string
     */
    public function exportDocToCSV(int $id)
    {
        $data = $this->show($id);
        $data = $data->result[0];

        $csv_string = '';
        $csv_string .= 'created_at,updated_at'.PHP_EOL;
        $csv_string .= $data->created_at.','.$data->updated_at.PHP_EOL;
        $csv_string .= 'key,value'.PHP_EOL;

        $_documents = json_decode($data->document);
        foreach ($_documents as $_document) {
            $csv_string .= '"'.$_document->key.'","'.$_document->value.'"'.PHP_EOL;
        }
        return $csv_string;
    }

    /**
     * Update metadata last exported data
     * @param  int    $id Document id
     */
    protected function updateExportedAt(int $id)
    {
        $document = Document::findOrFail($id);
        $document->exported_at = now();
        $document->save();
    }

    /**
     * Return laravel view for index route
     * @return object View
     */
    public function getAllDocuments()
    {
        return view('index', [
            'documents' => Document::all()
        ]);
    }
}
