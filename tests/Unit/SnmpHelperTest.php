<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Tests\Unit;

use Ocolin\EasySNMP\SnmpHelper;
use PHPUnit\Framework\TestCase;

class SnmpHelperTest extends TestCase
{

    /* FORMAT MAC ADDRESS
    ----------------------------------------------------------------------------- */

    public function test_formatMacAddress_returns_null_on_null() : void
    {
        $this->assertNull( SnmpHelper::formatMacAddress( null ) );
    }

    public function test_formatMacAddress_formats_correctly() : void
    {
        $this->assertSame(
            expected: '00:11:22:33:44:55',
            actual: SnmpHelper::formatMacAddress( "\x00\x11\x22\x33\x44\x55" )
        );
    }



    /* FORMAT IF TYPE
    ----------------------------------------------------------------------------- */

    public function test_formatIfType_returns_null_on_null() : void
    {
        $this->assertNull( SnmpHelper::formatIfType( null ) );
    }

    public function test_formatIfType_returns_ethernet() : void
    {
        $this->assertSame( 'ethernet', SnmpHelper::formatIfType( 6 ) );
    }

    public function test_formatIfType_returns_softwareLoopback() : void
    {
        $this->assertSame( 'softwareLoopback', SnmpHelper::formatIfType( 24 ) );
    }

    public function test_formatIfType_returns_tunnel() : void
    {
        $this->assertSame( 'tunnel', SnmpHelper::formatIfType( 131 ) );
    }

    public function test_formatIfType_returns_ieee8023adLag() : void
    {
        $this->assertSame( 'ieee8023adLag', SnmpHelper::formatIfType( 161 ) );
    }

    public function test_formatIfType_returns_raw_string_for_unknown() : void
    {
        $this->assertSame( '999', SnmpHelper::formatIfType( 999 ) );
    }



    /* FORMAT ADMIN STATUS
    ----------------------------------------------------------------------------- */

    public function test_formatAdminStatus_returns_null_on_null() : void
    {
        $this->assertNull( SnmpHelper::formatAdminStatus( null ) );
    }

    public function test_formatAdminStatus_returns_up() : void
    {
        $this->assertSame( 'up', SnmpHelper::formatAdminStatus( 1 ) );
    }

    public function test_formatAdminStatus_returns_down() : void
    {
        $this->assertSame( 'down', SnmpHelper::formatAdminStatus( 2 ) );
    }

    public function test_formatAdminStatus_returns_testing() : void
    {
        $this->assertSame( 'testing', SnmpHelper::formatAdminStatus( 3 ) );
    }

    public function test_formatAdminStatus_returns_raw_string_for_unknown() : void
    {
        $this->assertSame( '999', SnmpHelper::formatAdminStatus( 999 ) );
    }



    /* FORMAT OPER STATUS
    ----------------------------------------------------------------------------- */

    public function test_formatOperStatus_returns_null_on_null() : void
    {
        $this->assertNull( SnmpHelper::formatOperStatus( null ) );
    }

    public function test_formatOperStatus_returns_up() : void
    {
        $this->assertSame( 'up', SnmpHelper::formatOperStatus( 1 ) );
    }

    public function test_formatOperStatus_returns_down() : void
    {
        $this->assertSame( 'down', SnmpHelper::formatOperStatus( 2 ) );
    }

    public function test_formatOperStatus_returns_testing() : void
    {
        $this->assertSame( 'testing', SnmpHelper::formatOperStatus( 3 ) );
    }

    public function test_formatOperStatus_returns_unknown() : void
    {
        $this->assertSame( 'unknown', SnmpHelper::formatOperStatus( 4 ) );
    }

    public function test_formatOperStatus_returns_dormant() : void
    {
        $this->assertSame( 'dormant', SnmpHelper::formatOperStatus( 5 ) );
    }

    public function test_formatOperStatus_returns_notPresent() : void
    {
        $this->assertSame( 'notPresent', SnmpHelper::formatOperStatus( 6 ) );
    }

    public function test_formatOperStatus_returns_lowerLayerDown() : void
    {
        $this->assertSame( 'lowerLayerDown', SnmpHelper::formatOperStatus( 7 ) );
    }

    public function test_formatOperStatus_returns_raw_string_for_unknown() : void
    {
        $this->assertSame( '999', SnmpHelper::formatOperStatus( 999 ) );
    }



    /* FORMAT ARP TYPE
    ----------------------------------------------------------------------------- */

    public function test_formatArpType_returns_null_on_null() : void
    {
        $this->assertNull( SnmpHelper::formatArpType( null ) );
    }

    public function test_formatArpType_returns_other() : void
    {
        $this->assertSame( 'other', SnmpHelper::formatArpType( 1 ) );
    }

    public function test_formatArpType_returns_invalid() : void
    {
        $this->assertSame( 'invalid', SnmpHelper::formatArpType( 2 ) );
    }

    public function test_formatArpType_returns_dynamic() : void
    {
        $this->assertSame( 'dynamic', SnmpHelper::formatArpType( 3 ) );
    }

    public function test_formatArpType_returns_static() : void
    {
        $this->assertSame( 'static', SnmpHelper::formatArpType( 4 ) );
    }

    public function test_formatArpType_returns_raw_string_for_unknown() : void
    {
        $this->assertSame( '999', SnmpHelper::formatArpType( 999 ) );
    }
}