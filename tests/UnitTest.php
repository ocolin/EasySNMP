<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Tests;

use Ocolin\EasySNMP\Errors\EasySnmpInvalidIpError;
use Ocolin\EasySNMP\Errors\EasySnmpInvalidOidError;
use Ocolin\EasySNMP\Errors\EasySnmpMissingCommunityError;
use PHPUnit\Framework\TestCase;
use Ocolin\EasySNMP\SNMP;

class UnitTest extends TestCase
{
    public function testIntegerTypes() : void
    {
        $integerTypes = SNMP::integer_Types();
        $this->assertIsArray( actual: $integerTypes );
        $this->assertIsString( actual: $integerTypes[0] );
        $this->assertEquals( expected: 'INTEGER', actual: $integerTypes[0] );
    }

    public function testValidateOid() : void
    {
        $this->expectNotToPerformAssertions();
        SNMP::validate_OID( oid: '.1.3.6.1.2.1.31.1.1.1.18.1' );
    }
    public function testValidateOidException() : void
    {
        $this->expectException( EasySnmpInvalidOidError::class );
        SNMP::validate_OID( oid: 'test' );
    }

    public function testAllowedCommands() : void
    {
        $cmds = SNMP::allowed_Commands();
        $this->assertIsArray( actual: $cmds );
        $this->assertIsString( actual: $cmds[0] );
        $this->assertEquals( expected: 'snmpwalk', actual: $cmds[0] );
    }

    public function testGetCommunity() : void
    {
        $string = SNMP::get_community(  community: 'TEST' );
        $this->assertIsString( $string );
        $this->assertEquals( expected: 'TEST', actual: $string );
    }


    public function testGetCommunityEnv() : void
    {
        $_ENV['SNMP_COMMUNITY'] = 'TEST';
        $string = SNMP::get_community();
        $this->assertIsString( $string );
        $this->assertEquals( expected: 'TEST', actual: $string );
    }

    public function testGetCommunityEnvPrefix() : void
    {
        $_ENV['PREFIX_SNMP_COMMUNITY'] = 'TEST';
        $string = SNMP::get_community( prefix: 'PREFIX' );
        $this->assertIsString( $string );
        $this->assertEquals( expected: 'TEST', actual: $string );
    }


    public function testGetCommunityException() : void
    {
        $this->expectException( EasySnmpMissingCommunityError::class );
        $string = SNMP::get_community( prefix: 'BAD' );
    }

    public function testGetVersionV1() : void
    {
        $version = SNMP::get_Version( version: 1 );
        $this->assertIsString( $version );
        $this->assertEquals( expected: '-v1', actual: $version );
    }

    public function testGetVersionV2() : void
    {
        $version = SNMP::get_Version( version: 2 );
        $this->assertIsString( $version );
        $this->assertEquals( expected: '-v2c', actual: $version );
    }

    public function testGetVersionEnv() : void
    {
        $_ENV['SNMP_VERSION'] = 2;
        $version = SNMP::get_Version();
        $this->assertIsString( $version );
        $this->assertEquals( expected: '-v2c', actual: $version );
    }

    public function testGetVersionPrefix() : void
    {
        $_ENV['PREFIX_SNMP_VERSION'] = 2;
        $version = SNMP::get_Version( prefix: 'PREFIX_' );
        $this->assertIsString( $version );
        $this->assertEquals( expected: '-v2c', actual: $version );
    }

    public function testGetVersionDefault() : void
    {
        $version = SNMP::get_Version();
        $this->assertIsString( $version );
        $this->assertEquals( expected: '-v2c', actual: $version );
    }

    public function testGetIP() : void
    {
        $ip = SNMP::get_IP( ip: '192.168.1.1' );
        $this->assertIsString( $ip );
        $this->assertEquals( expected: '192.168.1.1', actual: $ip );
    }

    public function testGetIPEnv() : void
    {
        $_ENV['SNMP_IP'] = '192.168.1.1';
        $ip = SNMP::get_IP();
        $this->assertIsString( $ip );
        $this->assertEquals( expected: '192.168.1.1', actual: $ip );
    }

    public function testGetIPPrefix() : void
    {
        $_ENV['PREFIX_SNMP_IP'] = '192.168.1.1';
        $ip = SNMP::get_IP( prefix: 'PREFIX' );
        $this->assertIsString( $ip );
        $this->assertEquals( expected: '192.168.1.1', actual: $ip );
    }

    public function testGetIPExcept() : void
    {
        unset( $_ENV['SNMP_IP'] );
        $this->expectException( EasySnmpInvalidIpError::class );
        $ip = SNMP::get_IP();
    }
}