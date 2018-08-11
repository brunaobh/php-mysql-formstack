<?php

namespace App\Services;

use App\Document;

class DocumentService
{
    public function index()
    {
        return Document::all();
    }

    public function store($request)
    {
        $document = new Document;
        $document->title = rand();
        $document->document = json_encode($request->document);
        $document->save();
    }

    public function show(int $id)
    {
        return Document::findOrFail($id);
    }

    public function update($request, $id)
    {
        $document = Document::findOrFail($id);
        $document->document = json_encode($request->document);
        $document->save();
    }

    public function delete($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
    }

    public function export($format, $id)
    {
        switch (strtolower($format)) {
            case 'csv':
                return $this->exportAndDownloadDocToCSV($id);
                break;

            case 'cloud':
                return $this->exportDocToCloud($id);
                break;
            
            default:
                throw new Exception("Export format undefined");
                break;
        }
    }

    public function exportAndDownloadDocToCSV($id)
    {
        // return new \StreamedResponse(
        //     function () use ($data) {
        //         // A resource pointer to the output stream for writing the CSV to
        //         $handle = fopen('php://output', 'w');
        //         foreach ($data as $row) {
        //             // Loop through the data and write each entry as a new row in the csv
        //             fputcsv($handle, $row);
        //         }

        //         fclose($handle);
        //     },
        //     200,
        //     [
        //         'Content-type'        => 'text/csv',
        //         'Content-Disposition' => 'attachment; filename=members.csv'
        //     ]
        // );
        
        return response()->streamDownload(function () use ($id) {
            echo $this->exportDocToCSV($id);
        }, 'doc_'.$id.'.csv');
    }

    public function exportDocToCSV(int $id)
    {
        $data = $this->show($id);

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

    public function getAllDocuments()
    {
        return view('index', [
            'documents' => $this->index()
        ]);
    }
}
