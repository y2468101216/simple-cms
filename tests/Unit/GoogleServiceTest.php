<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Service\GoogleService;
use Illuminate\Http\Request;

class GoogleServiceTest extends TestCase
{
    const SERIAL = 'asdf';
    const AMOUNT = 1000;
    const STATUS = 'success';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSerial()
    {
        $data = ['serial' => static::SERIAL, 'amount' => static::AMOUNT, 'status' => static::STATUS];

        $service = new GoogleService($data);
        $actual = $service->getSerial();
        $expect = static::SERIAL;

        $this->assertEquals($actual, $expect);
    }

    public function testGetAmount()
    {
        $data = ['serial' => static::SERIAL, 'amount' => static::AMOUNT, 'status' => static::STATUS];

        $service = new GoogleService($data);
        $actual = $service->getAmount();
        $expect = static::AMOUNT;

        $this->assertEquals($actual, $expect);
    }

    public function testGetStatus()
    {
        $data = ['serial' => static::SERIAL, 'amount' => static::AMOUNT, 'status' => static::STATUS];

        $service = new GoogleService($data);
        $actual = $service->getStatus();
        $expect = static::STATUS;

        $this->assertEquals($actual, $expect);
    }
}
