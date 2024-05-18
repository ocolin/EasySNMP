<?php

namespace Tests;

use Cruzio\lib\EasySNMP\EasySNMP;
use Exception;
use Ocolin\Env\EasyEnv;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{

/*
---------------------------------------------------------------------------- */

    public function testValidate_Version_NoInput() : void
    {
        $output = EasySNMP::validate_Version();

        $this->assertIsInt( $output );
        $this->assertEquals( expected: 2, actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    public function testValidate_Version_Env() : void
    {
        $_ENV['SNMP_VERSION'] = 1;
        $output = EasySNMP::validate_Version();

        $this->assertIsInt( $output );
        $this->assertEquals( expected: 1, actual: $output );

        $_ENV['SNMP_VERSION'] = 2;
    }



/*
---------------------------------------------------------------------------- */

    public function testValidate_Version_WrongEnv() : void
    {
        $_ENV['SNMP_VERSION'] = 3;
        $output = EasySNMP::validate_Version();

        $this->assertIsInt( $output );
        $this->assertEquals( expected: 2, actual: $output );

        $_ENV['SNMP_VERSION'] = 2;
    }



/*
---------------------------------------------------------------------------- */

    public function testValidate_Version_Value() : void
    {
        $output = EasySNMP::validate_Version( version: 2 );

        $this->assertIsInt( $output );
        $this->assertEquals( expected: 2, actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    public function testValidate_Version_WrongValue() : void
    {
        $output = EasySNMP::validate_Version( version: 3 );

        $this->assertIsInt( $output );
        $this->assertEquals( expected: 2, actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testValidate_IP_Good() : void
    {
        $ip = '8.8.8.8';
        $output = EasySNMP::validate_IP( ip: $ip );

        $this->assertIsString( actual: $output );
        $this->assertEquals( expected: $ip, actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */

    public function testValidate_IP_Bad() : void
    {
        $this->expectException( Exception::class );
        $ip = 'ABC';
        $output = EasySNMP::validate_IP( ip: $ip );

    }



/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testCreateVersionTagOne() : void
    {
        $output = EasySNMP::create_Version_Tag( number: 1 );

        $this->assertIsString( actual: $output );
        $this->assertEquals( expected: '-v1', actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testCreateVersionTagTwo() : void
    {
        $output = EasySNMP::create_Version_Tag( number: 2 );

        $this->assertIsString( actual: $output );
        $this->assertEquals( expected: '-v2c', actual: $output );
    }



/*
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testCreateVersionTagBad() : void
    {
        $output = EasySNMP::create_Version_Tag( number: 100 );

        $this->assertIsString( $output );
        $this->assertEquals( expected: '-v1', actual: $output );
    }



/* TEST OID VALIDATION GOOD
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testOidValidationGood() : void
    {
        $output = EasySNMP::validate_OID( oid: '1.3.6.1.2.1.31.1.1.1.18.1' );

        $this->assertIsInt( actual: $output );
        $this->assertEquals( expected: 1, actual: $output );
    }



/* TEST OID VALIDATION BAD
---------------------------------------------------------------------------- */

    /**
     * @throws Exception
     */
    public function testOidValidationBad() : void
    {
        $output = EasySNMP::validate_OID( oid: 'ABC' );

        $this->assertIsInt( actual: $output );
        $this->assertEquals( expected: 0, actual: $output );
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
