<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\IpAddrEntry;

trait IpAddrTableTrait
{
    private const string IPADR_TABLE    = '1.3.6.1.2.1.4.20.1.';
    private const string IPADR_ADDRESS  = self::IPADR_TABLE . '1';
    private const string IPADR_IF_INDEX = self::IPADR_TABLE . '2';
    private const string IPADR_NETMASK  = self::IPADR_TABLE . '3';
    private const string IPADR_BCAST    = self::IPADR_TABLE . '4';
    private const string IPADR_MAXSIZE  = self::IPADR_TABLE . '5';

    private const array IP_COLUMN_MAP = [
        'address'       => self::IPADR_ADDRESS,
        'interface'     => self::IPADR_IF_INDEX,
        'netmask'       => self::IPADR_NETMASK,
        'bcast'         => self::IPADR_BCAST,
        'reasmMaxSize'  => self::IPADR_MAXSIZE,
    ];



/* GET IP ADDRESS TABLE
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns Columns to include.
     * @return IpAddrEntry[] List of IP addresses.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error getting SNMP response.
     */
    public function getIpAddrTable(
        array $columns = [
            'address',
            'interface',
            'netmask',
            'bcast',
            'reasmMaxSize',
        ]
    ) : array
    {
        $data = [];
        $table = [];
        $indexes = $this->getIpIndexes();
        $count = count( $indexes );

        foreach( $columns as $column ) {
            if( isset( self::IP_COLUMN_MAP[$column] ) ) {
                $data[$column] = $this->getCompositeColumn(
                    self::IP_COLUMN_MAP[$column], $count
                );
            }
        }

        foreach ( $indexes as $index => $ip )
        {
            $table[] = new IpAddrEntry(
                     address: (string)$ip,
                   interface: isset( $data['interface'][$index] ) ? (int)$data['interface'][$index] : null,
                     netmask: isset( $data['netmask'][$index] ) ? (string)$data['netmask'][$index] : null,
                       bcast: isset( $data['bcast'][$index] ) ? (int)$data['bcast'][$index] : null,
                reasmMaxSize: isset( $data['reasmMaxSize'][$index] ) ? (int)$data['reasmMaxSize'][$index] : null,
            );
        }

        return $table;
    }



/* GET IP INDEX VALUES
----------------------------------------------------------------------------- */

    /**
     * @return array<string, string> List of IPs and indexes.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    private function getIpIndexes() : array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::IPADR_ADDRESS ) as $object ) {
            $key = substr(
                string: $object->getOid(),
                offset: strlen( string: self::IPADR_ADDRESS ) + 1
            );
            $indexes[$key] = (string)$object->getValue();
        }

        return $indexes;
    }
}