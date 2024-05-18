<?php

namespace Tests;

use Cruzio\lib\EasySNMP\EasySNMP;
use Exception;
use Ocolin\Env\EasyEnv;
use PHPUnit\Framework\TestCase;

class WalkTest extends TestCase
{

/* TEST BULK WALK
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testBulkWalk() : void
    {
        $snmp = new EasySNMP(
               ip: $_ENV['SNMP_TEST_DEVICE'],
            local: true
        );

        $result = $snmp->walk( oid: '.1.3.6.1.2.1.25.2' );

        $this->assertIsArray(  actual: $result );
        $this->assertNotEmpty( actual: $result );
        $this->assertIsObject( actual: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'origin', object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'type',   object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'value',  object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'oid',   object: $result[0] );
        $this->assertIsString( actual: $result[0]->origin );
        $this->assertIsString( actual: $result[0]->type );
        $this->assertIsString( actual: $result[0]->oid );
    }



/* TEST REGULAR WALK
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testWalk() : void
    {
        $snmp = new EasySNMP(
               ip: $_ENV['SNMP_TEST_DEVICE'],
            local: true
        );

        $result = $snmp->walk( oid: '.1.3.6.1.2.1.25.2', bulk: false );

        $this->assertIsArray(  actual: $result );
        $this->assertNotEmpty( actual: $result );
        $this->assertIsObject( actual: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'origin', object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'type',   object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'value',  object: $result[0] );
        $this->assertObjectHasProperty( propertyName: 'oid',   object: $result[0] );
        $this->assertIsString( actual: $result[0]->origin );
        $this->assertIsString( actual: $result[0]->type );
        $this->assertIsString( actual: $result[0]->oid );
    }



/* SET UP BEFORE CLASS
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass() : void
    {
        EasyEnv::loadEnv( path: __DIR__ . '/../.env' );
    }
}
