<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Service\GithubService;
use Illuminate\Http\Request;

class GithubServiceTest extends TestCase
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
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <res>
                <serial>'.static::SERIAL.'</serial>
                <amount>'.static::AMOUNT.'</amount>
                <status>'.static::STATUS.'</status>
            </res>
        ';

        $service = new GithubService($xml);
        $actual = $service->getSerial();
        $expect = static::SERIAL;

        $this->assertEquals($actual, $expect);
    }

    public function testGetAmount()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <res>
                <serial>'.static::SERIAL.'</serial>
                <amount>'.static::AMOUNT.'</amount>
                <status>'.static::STATUS.'</status>
            </res>
        ';

        $service = new GithubService($xml);
        $actual = $service->getAmount();
        $expect = static::AMOUNT;

        $this->assertEquals($actual, $expect);
    }

    public function testGetStatus()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <res>
                <serial>'.static::SERIAL.'</serial>
                <amount>'.static::AMOUNT.'</amount>
                <status>'.static::STATUS.'</status>
            </res>
        ';

        $service = new GithubService($xml);
        $actual = $service->getStatus();
        $expect = static::STATUS;

        $this->assertEquals($actual, $expect);
    }
}
