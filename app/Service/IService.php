<?php

namespace App\Service;

interface IService
{
    public function __construct($data);

    public function handleRequest($data) : void;

    public function getSerial() : string;

    public function getAmount() : int;

    public function getStatus() : string;
}
