<?php
namespace App\Services;

class ResponseService
{
    const status_ok = "OK";
    const status_failed = "FAIL";
    const code_ok = 200;
    const code_failed = 400;
    const code_unauthorized = 403;
    const code_not_found = 404;
    const code_error = 500;

    /**
     * Response status
     * @var string
     */
    public $status = self::status_ok;

    /**
     * Response code
     * @var int
     */
    public $code = self::code_ok;

    /**
     * Response message
     * @var array
     */
    public $messages = array();

    /**
     * Response result
     * @var array
     */
    public $result = array();

    /**
     * Add result to response
     * @param String|Object $item Result
     */
    public function addResult($item)
    {
        array_push($this->result, $item);        
    }

    /**
     * Add a message to response
     * @param string $item Message
     */
    public function addMessage(string $item)
    {
        array_push($this->messages, $item);
    }

    /**
     * Set status response
     * @param string $status Response status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Set code response
     * @param string $code Code status
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }
}