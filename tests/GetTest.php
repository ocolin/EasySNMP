<?php

namespace Tests;

use Cruzio\lib\EasySNMP\EasySNMP;
use Exception;
use Ocolin\Env\EasyEnv;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{

/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testGetGood() : void
    {
        $snmp = new EasySNMP(
            ip: $_ENV['SNMP_TEST_DEVICE'],
            local: true
        );

        $result = $snmp->get( oid: '.1.3.6.1.2.1.31.1.1.1.18.1' );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( propertyName: 'origin', object: $result );
        $this->assertObjectHasProperty( propertyName: 'type',   object: $result );
        $this->assertObjectHasProperty( propertyName: 'value',  object: $result );
        $this->assertObjectHasProperty( propertyName: 'index',  object: $result );
        $this->assertObjectHasProperty( propertyName: 'name',   object: $result );
        $this->assertIsInt( actual: $result->index );
        $this->assertIsString( actual: $result->origin );
        $this->assertIsString( actual: $result->type );
        $this->assertIsString( actual: $result->name );
    }


/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testGetBad() : void
    {
        $snmp = new EasySNMP(
            ip: $_ENV['SNMP_TEST_DEVICE'],
            local: true
        );

        $result = $snmp->get(oid: '.1.3.6.1.2.1.31.200.1.1.19.1000');

        $this->assertNull( $result );
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
