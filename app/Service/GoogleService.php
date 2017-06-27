<?php

namespace App\Service;

use App\Service\IService;

class GoogleService implements IService
{
    private $serial = '';
    private $status = '';
    private $amount = 0;

    public function __construct($data)
    {
        $this->handleRequest($data);
    }

    public function handleRequest($data) : void
    {
        $this->serial = $data['serial'];
        $this->amount = $data['amount'];
        $this->status = $data['status'];
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
