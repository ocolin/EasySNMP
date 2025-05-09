<?php

namespace Ocolin\EasySNMP\Tests;

use Ocolin\EasySNMP\SNMP;
use Exception;
use Ocolin\EasyEnv\LoadEnv;
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
        $snmp = new SNMP();

        $result = $snmp->walk( oid: '.1.3.6.1.2.1.1.9.1.2' );

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
        $snmp = new SNMP();

        $result = $snmp->walk( oid: '.1.3.6.1.2.1.1.9.1.2', bulk: false );

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
        new LoadEnv( files: __DIR__ . '/../.env' );
    }
}
