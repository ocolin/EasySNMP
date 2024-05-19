<?php

namespace Tests;

use Ocolin\EasySNMP\EasySNMP;
use Exception;
use Ocolin\Env\EasyEnv;
use PHPUnit\Framework\TestCase;

class ExecuteTest extends TestCase
{


/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testExecute() : void
    {
        $snmp = new EasySNMP(
            ip: $_ENV['SNMP_TEST_DEVICE'],
            local: true
        );

        $output = $snmp->execute( cmd: 'snmpbulkwalk', oid: '.1.3.6.1.2.1.25.1' );
        $this->assertIsArray( actual: $output );
    }


/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass() : void
    {
        EasyEnv::loadEnv( path: __DIR__ . '/../.env' );
    }
}
