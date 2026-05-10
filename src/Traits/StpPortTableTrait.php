<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\StpPortEntry;

trait StpPortTableTrait
{
    private const string STP_PORT_TABLE     = '1.3.6.1.2.1.17.2.15.1.';
    private const string STP_PORT_BRIDGE    = self::STP_PORT_TABLE . '1';
    private const string STP_PORT_PRIORITY  = self::STP_PORT_TABLE . '2';
    private const string STP_PORT_STATE     = self::STP_PORT_TABLE . '3';
    private const string STP_PORT_ENABLE    = self::STP_PORT_TABLE . '4';
    private const string STP_PORT_PATHCOST  = self::STP_PORT_TABLE . '5';
    private const string STP_PORT_DROOT     = self::STP_PORT_TABLE . '6';
    private const string STP_PORT_DCOST     = self::STP_PORT_TABLE . '7';
    private const string STP_PORT_DBRIDGE   = self::STP_PORT_TABLE . '8';
    private const string STP_PORT_DPORT     = self::STP_PORT_TABLE . '9';
    private const array STP_PORT_MAP = [
        'priority'  => self::STP_PORT_PRIORITY,
        'state'     => self::STP_PORT_STATE,
        'enable'    => self::STP_PORT_ENABLE,
        'pathCost'  => self::STP_PORT_PATHCOST,
        'desRoot'   => self::STP_PORT_DROOT,
        'desCost'   => self::STP_PORT_DCOST,
        'desBridge' => self::STP_PORT_DBRIDGE,
        'desPort'   => self::STP_PORT_DPORT,
    ];



/* GET STP PORTS
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns List of columns to include.
     * @return StpPortEntry[] STP port table.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP data.
     */
    public function getStpPorts(
        array $columns = [
            'priority',
            'state',
            'enable',
            'pathCost',
            'desRoot',
            'desCost',
            'desBridge',
            'desPort'
        ]
    ): array
    {
        $data = [];
        $table = [];
        $indexes = $this->getStpPortIndexes();
        $count = count( $indexes );

        foreach( $columns as $column )
        {
            if( isset( self::STP_PORT_MAP[$column] ) ) {
                $data[$column] = $this->getColumn( self::STP_PORT_MAP[$column], $count );
            }
        }


        foreach( $indexes as $index )
        {
            $table[] = new StpPortEntry(
                bridge: $index,
                priority: isset( $data['priority'][$index] )
                    ? (int)$data['priority'][$index] : null,
                state: isset( $data['state'][$index] )
                    ? (int)$data['state'][$index] : null,
                enable: isset( $data['enable'][$index] )
                    ? (int)$data['enable'][$index] : null,
                pathCost: isset( $data['pathCost'][$index] )
                    ? (int)$data['pathCost'][$index] : null,
                desRoot: isset( $data['desRoot'][$index] )
                    ? (string)$data['desRoot'][$index] : null,
                desCost: isset( $data['desCost'][$index] )
                    ? (int)$data['desCost'][$index] : null,
                desBridge: isset( $data['desBridge'][$index] )
                    ? (string)$data['desBridge'][$index] : null,
                desPort: isset( $data['desPort'][$index] )
                    ? (string)$data['desPort'][$index] : null,
            );
        }

        return $table;
    }



/* GET STP PORT INDEXES
----------------------------------------------------------------------------- */

    /**
     * @return int[] Array of index values.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP data.
     */
    private function getStpPortIndexes(): array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::STP_PORT_BRIDGE ) as $object ) {
            $indexes[] = (int)(string)$object->getValue();
        }

        return $indexes;
    }
}