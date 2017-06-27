<?php

namespace App\Service;

use App\Service\IService;

class GithubService implements IService
{
    private $serial = '';
    private $status = '';
    private $amount = 0;

    public function __construct(string $data)
    {
        $this->handleRequest($data);
    }

    public function handleRequest(string $data) : void
    {
        $data = simplexml_load_string($data);
        
        $this->serial = (string) $data->serial;
        $this->amount = (string) $data->amount;
        $this->status = (string) $data->status;
    }

    public function getSerial() : string
    {
        return $this->serial;
    }

    public function getAmount() : int
    {
        return $this->amount;
    }

    public function getStatus() : string
    {
        return $this->status;
    }
}
