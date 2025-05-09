<?php

namespace Ocolin\EasySNMP\Tests;

use Ocolin\EasySNMP\SNMP;
use Exception;
use Ocolin\EasyEnv\LoadEnv;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{

/* TEST GET WITH GOOD DATA
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testGetGood() : void
    {
        $snmp = new SNMP();

        $result = $snmp->get( oid: '.1.3.6.1.2.1.1.1.0' );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( propertyName: 'origin', object: $result );
        $this->assertObjectHasProperty( propertyName: 'type',   object: $result );
        $this->assertObjectHasProperty( propertyName: 'value',  object: $result );
        $this->assertObjectHasProperty( propertyName: 'oid',   object: $result );
        $this->assertIsString( actual: $result->origin );
        $this->assertIsString( actual: $result->type );
        $this->assertIsString( actual: $result->oid );
    }



/* TEST GET WITH BAD DATA
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testGetBad() : void
    {
        $snmp = new SNMP();
        $result = $snmp->get( oid: '.1.3.6.1.2.1.31.200.1.1.19.1000');

        $this->assertNull( actual: $result );
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
