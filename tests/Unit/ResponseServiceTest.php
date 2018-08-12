<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponseServiceTest extends TestCase
{
    /**
     * Test - Add new result to response service
     *
     * @return void
     */
    public function testAddNewResult()
    {
    	$responseService = new ResponseService();
    	$responseService->addResult('test');
        $this->assertEquals('test', $responseService->result[0]);
    }

    /**
     * Test - Add new result into first position in array
     *
     * @return void
     */
    public function testAddNewResultAtFirstPosition()
    {
    	$responseService = new ResponseService();
    	$responseService->addResult('test1');
    	$responseService->addResult('test2', 'r');
        $this->assertEquals('test2', $responseService->result[0]);
    }

    /**
     * Test - Add new result to response service
     *
     * @return void
     */
    public function testAddNewMessage()
    {
    	$responseService = new ResponseService();
    	$responseService->addMessage('test');
        $this->assertEquals('test', $responseService->messages[0]);
    }

    /**
     * Test - Set status
     *
     * @return void
     */
    public function testSetStatus()
    {
    	$responseService = new ResponseService();
    	$responseService->setStatus($responseService::status_failed);
        $this->assertEquals('FAIL', $responseService->status);
    }

    /**
     * Test - Set code
     *
     * @return void
     */
    public function testSetCode()
    {
    	$responseService = new ResponseService();
    	$responseService->setCode($responseService::code_not_found);
        $this->assertEquals(404, $responseService->code);
    }
}
