<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\LldpRemEntry;

trait LldpRemTableTrait
{
    private const string LLDP_REM_TABLE       = '1.0.8802.1.1.2.1.4.1.1.';
    private const string LLDP_REM_CHASSISTYPE = self::LLDP_REM_TABLE . '4';
    private const string LLDP_REM_CHASSISID   = self::LLDP_REM_TABLE . '5';
    private const string LLDP_REM_PORTIDTYPE  = self::LLDP_REM_TABLE . '6';
    private const string LLDP_REM_PORTID      = self::LLDP_REM_TABLE . '7';
    private const string LLDP_REM_PORTDESC    = self::LLDP_REM_TABLE . '8';
    private const string LLDP_REM_SYSNAME     = self::LLDP_REM_TABLE . '9';
    private const string LLDP_REM_SYSDESC     = self::LLDP_REM_TABLE . '10';
    private const string LLDP_REM_SUPPORTED   = self::LLDP_REM_TABLE . '11';
    private const string LLDP_REM_ENABLED     = self::LLDP_REM_TABLE . '12';

    private const array LLDP_REM_COLUMN_MAP = [
        'chassisIdType' => self::LLDP_REM_CHASSISTYPE,
        'chassisId'     => self::LLDP_REM_CHASSISID,
        'portIdType'    => self::LLDP_REM_PORTIDTYPE,
        'portId'        => self::LLDP_REM_PORTID,
        'portDesc'      => self::LLDP_REM_PORTDESC,
        'systemName'    => self::LLDP_REM_SYSNAME,
        'systemDesc'    => self::LLDP_REM_SYSDESC,
        'capSupported'  => self::LLDP_REM_SUPPORTED,
        'capEnabled'    => self::LLDP_REM_ENABLED,
    ];



/* GET LLDP REMOTE TABLE
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns List of columns to return.
     * @return LldpRemEntry[] List of LLDP remote table entries.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function getLldpRemTable(
        array $columns = [
            'chassisIdType',
            'chassisId',
            'portIdType',
            'portId',
            'portDesc',
            'systemName',
            'systemDesc',
            'capSupported',
            'capEnabled',
        ]
    ) : array
    {
        $data = [];
        $table = [];
        $indexes = $this->getLldpRemIndexes();
        $count = count( $indexes );

        foreach( $columns as $column ) {
            if( isset( self::LLDP_REM_COLUMN_MAP[$column] ) ) {
                $data[$column] = $this->getCompositeColumn(
                    self::LLDP_REM_COLUMN_MAP[$column], $count
                );
            }
        }

        foreach ( $indexes as $index => $chassisIdType )
        {
            $parts     = explode( separator: '.', string: $index );
            $localPort = (int)$parts[1];

            $table[] = new LldpRemEntry(
                    localPort: $localPort,
                chassisIdType: $chassisIdType,
                    chassisId: isset( $data['chassisId'][$index] )
                        ? (string)$data['chassisId'][$index] : null,
                   portIdType: isset( $data['portIdType'][$index] )
                        ? (int)$data['portIdType'][$index] : null,
                       portId: isset( $data['portId'][$index] )
                        ? (string)$data['portId'][$index] : null,
                     portDesc: isset( $data['portDesc'][$index] )
                        ? (string)$data['portDesc'][$index] : null,
                      sysName: isset( $data['systemName'][$index] )
                        ? (string)$data['systemName'][$index] : null,
                      sysDesc: isset( $data['systemDesc'][$index] )
                        ? (string)$data['systemDesc'][$index] : null,
                 capSupported: isset( $data['capSupported'][$index] )
                        ? (string)$data['capSupported'][$index] : null,
                   capEnabled: isset( $data['capEnabled'][$index] )
                        ? (string)$data['capEnabled'][$index] : null,
            );
        }

        return $table;
    }



/* GET LLDP REMOTE INDEXES
----------------------------------------------------------------------------- */

    /**
     * @return array<string, int> List of interfaces and indexes.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP output.
     */
    private function getLldpRemIndexes() : array
    {
        $indexes = [];
        foreach( $this->bulkWalk( oid: self::LLDP_REM_CHASSISTYPE ) as $object ) {
            $key = substr(
                string: $object->getOid(),
                offset: strlen( string: self::LLDP_REM_CHASSISTYPE ) + 1
            );
            $indexes[$key] = (int)(string)$object->getValue();
        }

        return $indexes;
    }
}