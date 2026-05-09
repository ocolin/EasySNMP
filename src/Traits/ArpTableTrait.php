<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\ArpTable;

trait ArpTableTrait
{
    private const string ARP_TABLE = '1.3.6.1.2.1.4.22.1.';
    private const string ARP_IF_INDEX = self::ARP_TABLE . '1';
    private const string ARP_PHYSICAL = self::ARP_TABLE . '2';
    private const string ARP_ADDRESS  = self::ARP_TABLE . '3';
    private const string ARP_TYPE     = self::ARP_TABLE . '4';


/* GET ARP TABLE
----------------------------------------------------------------------------- */

    /**
     * @return ArpTable[] List of ARP table entries.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function getArpTable() : array
    {
        $table = [];
        $indexes = $this->getArpIndexes();
        $count = count( $indexes );

        $physical = $this->getCompositeColumn( oid: self::ARP_PHYSICAL, count: $count );
        $address  = $this->getCompositeColumn( oid: self::ARP_ADDRESS,  count: $count );
        $type     = $this->getCompositeColumn( oid: self::ARP_TYPE,     count: $count );

        foreach( $indexes as $index => $interface )
        {
            $table[] = new ArpTable(
                interface: (int)$interface,
                      mac: isset( $physical[$index] ) ? (string)$physical[$index] : null,
                ipAddress: isset( $address[$index] )  ? (string)$address[$index]  : null,
                     type: isset( $type[$index] )     ? (int)$type[$index]        : null,
            );
        }

        return $table;
    }



/* GET TABLE INDEXED
----------------------------------------------------------------------------- */

    /**
     * @return array<string, int> List of interfaces and indexes.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    private function getArpIndexes() : array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::ARP_IF_INDEX ) as $object ) {
            $key = substr(
                string: $object->getOid(),
                offset: strlen( string: self::ARP_IF_INDEX ) + 1
            );
            $indexes[$key] = (int)(string)$object->getValue();
        }

        return $indexes;
    }
}