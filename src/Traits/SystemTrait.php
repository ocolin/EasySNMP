<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use FreeDSx\Snmp\Oid;
use Ocolin\EasySNMP\DTO\System;
use FreeDSx\Snmp\OidList;

trait SystemTrait
{
    private const string SYS_DESCR    = '1.3.6.1.2.1.1.1.0';
    private const string SYS_UPTIME   = '1.3.6.1.2.1.1.3.0';
    private const string SYS_CONTACT  = '1.3.6.1.2.1.1.4.0';
    private const string SYS_NAME     = '1.3.6.1.2.1.1.5.0';
    private const string SYS_LOCATION = '1.3.6.1.2.1.1.6.0';
    private const string SYS_OID = '1.3.6.1.2.1.1.2.0';


/* GET SYSTEM INFO
----------------------------------------------------------------------------- */

    /**
     * Get device System data.
     *
     * @return System System data object.
     * @throws ConnectionException Problem connecting to device.
     * @throws SnmpRequestException Error getting data from device.
     */
    public function getSystem() : System
    {
        $response = $this->client->get(
            self::SYS_DESCR,
            self::SYS_UPTIME,
            self::SYS_CONTACT,
            self::SYS_NAME,
            self::SYS_LOCATION,
            self::SYS_OID
        );

        return new System(
               descr: self::getSystemOid( list: $response, oid: self::SYS_DESCR ),
              upTime: (int)self::getSystemOid( list: $response, oid: self::SYS_UPTIME ),
             contact: self::getSystemOid( list: $response, oid: self::SYS_CONTACT ),
                name: self::getSystemOid( list: $response, oid: self::SYS_NAME ),
            location: self::getSystemOid( list: $response, oid: self::SYS_LOCATION ),
                 oid: self::getSystemOid( list: $response, oid: self::SYS_OID ),
        );
    }



/* GET OIDS FROM SYSTEM TABLE
----------------------------------------------------------------------------- */

    /**
     * @param OidList<Oid> $list List of system data.
     * @param string $oid OID to extract.
     * @return ?string String value of system object.
     */
    private static function getSystemOid( OidList $list, string $oid ) : string|null
    {
        foreach( $list as $row )
        {
            if( $row->getOid() === $oid ) { return (string)$row->getValue(); }
        }

        return null;
    }
}