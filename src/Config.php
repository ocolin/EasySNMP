<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Ocolin\GlobalType\ENV;

readonly class Config
{
    /**
     * @var ?string Hostname of device.
     */
    public ?string $host;

    /**
     * @var ?string SNMP v1/v2 community string.
     */
    public ?string $community;

    /**
     * @var array<string, string|int|bool|null> SNMP options
     */
    public array $options;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?string $host Hostname of device.
     * @param ?string $community Community string of device.
     * @param ?string $prefix Optional ENV prefix for multi-device setups.
     * <ul>
     * <li>With prefix of 'JUNIPER': JUNIPER_SNMP_HOST</li>
     * <li>Without any prefix: EASY_SNMP_HOST</li>
     * </ul>
     * @param array<string, string|int|bool|null> $options Optional SNMP params for
     * advanced configuration.
     */
    public function __construct(
        ?string $host       = null,
        ?string $community  = null,
        ?string $prefix     = null,
          array $options    = []  // SNMP options
    ) {

        $prefix = strtoupper( string: $prefix ?? 'EASY' );

        $this->host = $host ?? ENV::getStringNull( name: "{$prefix}_SNMP_HOST" );
        $this->community = $community ?? ENV::getStringNull( name: "{$prefix}_SNMP_COMMUNITY" );

        $options['port'] = $options['port']
            ?? ENV::getIntNull( name: "{$prefix}_SNMP_PORT" );
        $options['version'] = $options['version']
            ?? ENV::getIntNull( name: "{$prefix}_SNMP_VERSION" );
        $options['timeout_connect'] = $options['timeout_connect']
            ?? ENV::getIntNull( name: "{$prefix}_SNMP_TIMEOUT_CONNECT" );
        $options['user'] = $options['user']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_USER" );
        $options['timeout_read'] = $options['timeout_read']
            ?? ENV::getIntNull( name: "{$prefix}_SNMP_TIMEOUT_READ" );
        $options['auth_pwd'] = $options['auth_pwd']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_AUTH_PWD" );
        $options['priv_pwd'] = $options['priv_pwd']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_PRIV_PWD" );
        $options['priv_mech'] = $options['priv_mech']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_PRIV_MECH" );
        $options['auth_mech'] = $options['auth_mech']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_AUTH_MECH" );
        $options['engine_id'] = $options['engine_id']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_ENGINE_ID" );
        $options['context_name'] = $options['context_name']
            ?? ENV::getStringNull( name: "{$prefix}_SNMP_CONTEXT_NAME" );
        $options['use_auth'] = $options['use_auth']
            ?? ENV::getBoolNull( name: "{$prefix}_SNMP_USE_AUTH" );
        $options['use_priv'] = $options['use_priv']
            ?? ENV::getBoolNull( name: "{$prefix}_SNMP_USE_PRIV" );
        $this->options = $options;
    }



/* GET ALL SNMP CONFIG OPTIONS
----------------------------------------------------------------------------- */

    /**
     * @return array<string, string|int|bool|null> SNMP config parameters.
     */
    public function getOptions() : array
    {

        return array_filter(
            array: array_merge( $this->options, [
                'host'      => $this->host,
                'community' => $this->community
            ]),
            callback: fn( $v ) => $v !== null
        );
    }
}