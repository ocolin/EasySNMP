<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Tests\Unit;

use Ocolin\EasySNMP\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

/* SETUP
----------------------------------------------------------------------------- */

    protected function setUp() : void
    {
        // Clear any ENV vars before each test
        foreach( [
                     'EASY_SNMP_HOST', 'EASY_SNMP_COMMUNITY', 'EASY_SNMP_PORT',
                     'EASY_SNMP_VERSION', 'EASY_SNMP_TIMEOUT_CONNECT',
                     'EASY_SNMP_TIMEOUT_READ', 'EASY_SNMP_USER',
                     'EASY_SNMP_AUTH_PWD', 'EASY_SNMP_PRIV_PWD',
                     'EASY_SNMP_PRIV_MECH', 'EASY_SNMP_AUTH_MECH',
                     'EASY_SNMP_ENGINE_ID', 'EASY_SNMP_CONTEXT_NAME',
                     'EASY_SNMP_USE_AUTH', 'EASY_SNMP_USE_PRIV',
                     'TEST_SNMP_HOST', 'TEST_SNMP_COMMUNITY',
                 ] as $key ) {
            unset( $_ENV[$key], $_SERVER[$key] );
        }
    }



/* EXPLICIT VALUES
----------------------------------------------------------------------------- */

    public function test_explicit_host_is_set() : void
    {
        $config = new Config( host: '10.0.0.1' );
        $this->assertSame( '10.0.0.1', $config->host );
    }

    public function test_explicit_community_is_set() : void
    {
        $config = new Config( community: 'public' );
        $this->assertSame( 'public', $config->community );
    }

    public function test_explicit_values_take_priority_over_env() : void
    {
        $_ENV['EASY_SNMP_HOST'] = '192.168.1.1';
        $config = new Config( host: '10.0.0.1' );
        $this->assertSame( '10.0.0.1', $config->host );
    }



/* ENV FALLBACK
----------------------------------------------------------------------------- */

    public function test_host_falls_back_to_env() : void
    {
        $_ENV['EASY_SNMP_HOST'] = '192.168.1.1';
        $config = new Config();
        $this->assertSame( '192.168.1.1', $config->host );
    }

    public function test_community_falls_back_to_env() : void
    {
        $_ENV['EASY_SNMP_COMMUNITY'] = 'private';
        $config = new Config();
        $this->assertSame( 'private', $config->community );
    }

    public function test_host_is_null_when_not_set() : void
    {
        $config = new Config();
        $this->assertNull( $config->host );
    }

    public function test_community_is_null_when_not_set() : void
    {
        $config = new Config();
        $this->assertNull( $config->community );
    }



/* PREFIX
----------------------------------------------------------------------------- */

    public function test_default_prefix_is_easy() : void
    {
        $_ENV['EASY_SNMP_HOST'] = '10.0.0.1';
        $config = new Config();
        $this->assertSame( '10.0.0.1', $config->host );
    }

    public function test_custom_prefix_is_used() : void
    {
        $_ENV['TEST_SNMP_HOST'] = '10.0.0.2';
        $config = new Config( prefix: 'TEST' );
        $this->assertSame( '10.0.0.2', $config->host );
    }

    public function test_prefix_is_uppercased() : void
    {
        $_ENV['TEST_SNMP_HOST'] = '10.0.0.2';
        $config = new Config( prefix: 'test' );
        $this->assertSame( '10.0.0.2', $config->host );
    }

    public function test_custom_prefix_does_not_use_easy_env() : void
    {
        $_ENV['EASY_SNMP_HOST'] = '10.0.0.1';
        $config = new Config( prefix: 'TEST' );
        $this->assertNull( $config->host );
    }



/* OPTIONS
----------------------------------------------------------------------------- */

    public function test_explicit_option_is_set() : void
    {
        $config = new Config( options: [ 'port' => 161 ] );
        $this->assertSame( 161, $config->options['port'] );
    }

    public function test_options_env_fallback() : void
    {
        $_ENV['EASY_SNMP_PORT'] = '161';
        $config = new Config();
        $this->assertSame( 161, $config->options['port'] );
    }

    public function test_explicit_option_takes_priority_over_env() : void
    {
        $_ENV['EASY_SNMP_PORT'] = '162';
        $config = new Config( options: [ 'port' => 161 ] );
        $this->assertSame( 161, $config->options['port'] );
    }



    /* GET OPTIONS
    ----------------------------------------------------------------------------- */

    public function test_getOptions_includes_host_and_community() : void
    {
        $config = new Config( host: '10.0.0.1', community: 'public' );
        $options = $config->getOptions();
        $this->assertSame( '10.0.0.1', $options['host'] );
        $this->assertSame( 'public', $options['community'] );
    }

    public function test_getOptions_strips_null_values() : void
    {
        $config = new Config( host: '10.0.0.1' );
        $options = $config->getOptions();
        $this->assertArrayNotHasKey( 'community', $options );
    }

    public function test_getOptions_includes_non_null_options() : void
    {
        $config = new Config(
            host: '10.0.0.1',
            options: [ 'port' => 161, 'version' => 2 ]
        );
        $options = $config->getOptions();
        $this->assertSame( 161, $options['port'] );
        $this->assertSame( 2, $options['version'] );
    }

    public function test_getOptions_excludes_null_options() : void
    {
        $config = new Config( host: '10.0.0.1' );
        $options = $config->getOptions();
        $this->assertArrayNotHasKey( 'port', $options );
        $this->assertArrayNotHasKey( 'version', $options );
    }
}