<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\MacTable;

trait MacTableTrait
{
    private const string MAC_TABLE = '1.3.6.1.2.1.17.4.3.1.';
    private const string MAC_PORT_IFINDEX = '1.3.6.1.2.1.17.1.4.1.2';
    private const string MAC_ADDRESS = self::MAC_TABLE . '1';
    private const string MAC_BRIDGE = self::MAC_TABLE . '2';
    private const string MAC_STATUS = self::MAC_TABLE . '3';


/* GET MAC TABLE
----------------------------------------------------------------------------- */

    /**
     * @return MacTable[] List of MAC entries.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function getMacTable() : array
    {
        $table = [];
        $indexes = $this->getMacTableIndexes();
        $count = count( $indexes );

        $bridges    = $this->getCompositeColumn( oid: self::MAC_BRIDGE, count: $count );
        $statuses   = $this->getCompositeColumn( oid: self::MAC_STATUS, count: $count );
        $interfaces = $this->getColumn( oid: self::MAC_PORT_IFINDEX );

        foreach ( $indexes as $index => $mac )
        {
            $table[] = new MacTable(
                      mac: $mac,
                   bridge: isset( $bridges[ $index ] )
                          ? (int)$bridges[ $index ] : null,
                   status: isset( $statuses[ $index ] )
                          ? (int)$statuses[ $index ] : null,
                interface: isset( $bridges[ $index ] )
                      && isset( $interfaces[ $bridges[ $index ]] )
                        ? (int)$interfaces[ $bridges[ $index ]] : null
            );
        }

        return $table;
    }



/* GET MAC TABLE INDEXES
----------------------------------------------------------------------------- */

    /**
     * @return array<string, string> Index and MAC address.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    private function getMacTableIndexes() : array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::MAC_ADDRESS ) as $object ) {
            $key = substr(
                string: $object->getOid(),
                offset: strlen( string: self::MAC_ADDRESS ) + 1
            );
            $indexes[$key] = (string)$object->getValue();
        }

        return $indexes;
    }
}