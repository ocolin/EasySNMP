<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\IpForwardEntry;

trait IpForwardTableTrait
{

    private const string IP_FORWARD_TABLE     = '1.3.6.1.2.1.4.24.4.1.';
    private const string IP_FORWARD_IFINDEX   = self::IP_FORWARD_TABLE . '5';
    private const string IP_FORWARD_TYPE      = self::IP_FORWARD_TABLE . '6';
    private const string IP_FORWARD_PROTO     = self::IP_FORWARD_TABLE . '7';
    private const string IP_FORWARD_AGE       = self::IP_FORWARD_TABLE . '8';
    private const string IP_FORWARD_INFO      = self::IP_FORWARD_TABLE . '9';
    private const string IP_FORWARD_NEXTHOPAS = self::IP_FORWARD_TABLE . '10';
    private const string IP_FORWARD_METRIC1   = self::IP_FORWARD_TABLE . '11';
    private const string IP_FORWARD_METRIC2   = self::IP_FORWARD_TABLE . '12';
    private const string IP_FORWARD_METRIC3   = self::IP_FORWARD_TABLE . '13';
    private const string IP_FORWARD_METRIC4   = self::IP_FORWARD_TABLE . '14';
    private const string IP_FORWARD_METRIC5   = self::IP_FORWARD_TABLE . '15';
    private const string IP_FORWARD_STATUS    = self::IP_FORWARD_TABLE . '16';
    private const array IP_FORWARD_COLUMN_MAP = [
        'interface' => self::IP_FORWARD_IFINDEX,
        'type'      => self::IP_FORWARD_TYPE,
        'protocol'  => self::IP_FORWARD_PROTO,
        'age'       => self::IP_FORWARD_AGE,
        'info'      => self::IP_FORWARD_INFO,
        'nextHopAs' => self::IP_FORWARD_NEXTHOPAS,
        'metric1'   => self::IP_FORWARD_METRIC1,
        'metric2'   => self::IP_FORWARD_METRIC2,
        'metric3'   => self::IP_FORWARD_METRIC3,
        'metric4'   => self::IP_FORWARD_METRIC4,
        'metric5'   => self::IP_FORWARD_METRIC5,
        'status'    => self::IP_FORWARD_STATUS,
    ];



/* GET IP FORWARD TABLE
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns List of columns to fetch.
     * @return IpForwardEntry[] IP forwarding table.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function getIpForwardTable(
        array $columns = [
            'interface',
            'type',
            'protocol',
            'metric1',
            'status',
        ]
    ) : array
    {
        $data = [];
        $table = [];
        $indexes = $this->getIpForwardIndexes();
        $count = count( $indexes );

        foreach( $columns as $column )
        {
            if( isset( self::IP_FORWARD_COLUMN_MAP[$column] )) {
                $data[$column] = $this->getCompositeColumn(
                      oid: self::IP_FORWARD_COLUMN_MAP[$column],
                    count: $count
                );
            }
        }

        foreach( $indexes as $index => $values )
        {
            $table[] = new IpForwardEntry(
                destination: (string)$values['destination'],
                       mask: (string)$values['mask'],
                     policy: (int)$values['policy'],
                    nextHop: (string)$values['nextHop'],
                  interface: isset( $data['interface'][$index] )
                            ? (int)$data['interface'][$index] : null,
                       type: isset( $data['type'][$index] )
                            ? (int)$data['type'][$index] : null,
                   protocol: isset( $data['protocol'][$index] )
                            ? (int)$data['protocol'][$index] : null,
                        age: isset( $data['age'][$index] )
                            ? (int)$data['age'][$index] : null,
                        info: isset( $data['info'][$index] )
                            ? (string)$data['info'][$index] : null,
                   nextHopAs: isset( $data['nextHopAs'][$index] )
                            ? (int)$data['nextHopAs'][$index] : null,
                     metric1: isset( $data['metric1'][$index] )
                            ? (int)$data['metric1'][$index] : null,
                     metric2: isset( $data['metric2'][$index] )
                            ? (int)$data['metric2'][$index] : null,
                     metric3: isset( $data['metric3'][$index] )
                            ? (int)$data['metric3'][$index] : null,
                     metric4: isset( $data['metric4'][$index] )
                            ? (int)$data['metric4'][$index] : null,
                     metric5: isset( $data['metric5'][$index] )
                            ? (int)$data['metric5'][$index] : null,
                      status: isset( $data['status'][$index] )
                            ? (int)$data['status'][$index] : null,
            );
        }

        return $table;
    }



/* GET IP FORWARD INDEXES
----------------------------------------------------------------------------- */

    /**
     * @return array<string,array<string, int|string>> Index and column data.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    private function getIpForwardIndexes() : array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::IP_FORWARD_IFINDEX ) as $object ) {
            $index = substr(
                string: $object->getOid(),
                offset: strlen( string: self::IP_FORWARD_IFINDEX ) + 1
            );
            $parts = explode( separator: '.', string: $index );
            $indexes[$index] = [
                'destination' => implode(
                    separator: '.', array: array_slice(
                        array: $parts, offset: 0, length: 4
                    )
                ),
                'mask' => implode(
                    separator: '.', array: array_slice(
                        array: $parts, offset: 4, length: 4
                    )
                ),
                'policy' => (int)$parts[8],
                'nextHop' => implode(
                    separator: '.', array: array_slice(
                        array: $parts, offset: 9, length: 4
                    )
                ),
            ];
        }

        return $indexes;
    }
}