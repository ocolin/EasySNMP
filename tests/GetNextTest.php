<?php

namespace Ocolin\EasySNMP\Tests;

use Ocolin\EasySNMP\SNMP;
use Exception;
use Ocolin\EasyEnv\LoadEnv;
use PHPUnit\Framework\TestCase;

class GetNextTest extends TestCase
{

/* TEST GET WITH GOOD DATA
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testGetNextGood() : void
    {
        $snmp = new SNMP();

        $result = $snmp->getNext( oid: '.1.3.6.1.2.1.1.1.0' );

        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( propertyName: 'origin', object: $result );
        $this->assertObjectHasProperty( propertyName: 'type',   object: $result );
        $this->assertObjectHasProperty( propertyName: 'value',  object: $result );
        $this->assertObjectHasProperty( propertyName: 'oid',   object: $result );
        $this->assertIsString( actual: $result->origin );
        $this->assertIsString( actual: $result->type );
        $this->assertIsString( actual: $result->oid );
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
