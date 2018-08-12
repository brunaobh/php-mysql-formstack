<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Services\DocumentService;
use App\Services\ResponseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DocumentsApiController extends Controller
{
    /**
     * Document service 
     * @var pp\Services\DocumentService
     */
    protected $documentService;

    /**
     * Response service 
     * @var pp\Services\ResponseService
     */
    protected $responseService;

    public function __construct(DocumentService $documentService, ResponseService $responseService)
    {
        $this->documentService = $documentService;
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->documentService->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        return response()->json($this->documentService->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Illuminate\Http\JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        try {
            return response()->json($this->documentService->show($id));
        } catch (\Exception $e) {
            $this->responseService->setStatus($this->responseService::status_failed);
            $this->responseService->setCode($this->responseService::code_not_found);
            $this->responseService->addMessage("Document not found");
            return response()->json($this->responseService, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        try {
            return response()->json($this->documentService->update($request, $id));
        } catch (ModelNotFoundException $e) {
            $this->responseService->setStatus($this->responseService::status_failed);
            $this->responseService->setCode($this->responseService::code_not_found);
            $this->responseService->addMessage("Document not found");
            return response()->json($this->responseService, 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            return response()->json($this->documentService->delete($id));
        } catch (ModelNotFoundException $e) {
            $this->responseService->setStatus($this->responseService::status_failed);
            $this->responseService->setCode($this->responseService::code_not_found);
            $this->responseService->addMessage("Document not found");
            return response()->json($this->responseService, 404);
        }
    }
}
