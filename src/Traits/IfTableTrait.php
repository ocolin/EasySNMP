<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\IfTable;

trait IfTableTrait
{
    private const string IF_TABLE  = '1.3.6.1.2.1.2.2.1.';
    private const string IF_INDEX        = self::IF_TABLE  . '1';
    private const string IF_DESCR        = self::IF_TABLE  . '2';
    private const string IF_TYPE         = self::IF_TABLE  . '3';
    private const string IF_MTU          = self::IF_TABLE  . '4';
    private const string IF_SPEED        = self::IF_TABLE  . '5';
    private const string IF_MAC_ADDRESS  = self::IF_TABLE  . '6';
    private const string IF_ADMIN_STATUS = self::IF_TABLE  . '7';
    private const string IF_OPER_STATUS  = self::IF_TABLE  . '8';
    private const string IF_LAST_CHANGE  = self::IF_TABLE  . '9';
    private const string IF_IN_OCTETS    = self::IF_TABLE  . '10';
    private const string IF_IN_ERRORS    = self::IF_TABLE  . '14';
    private const string IF_OUT_OCTETS   = self::IF_TABLE  . '16';
    private const string IF_OUT_ERRORS   = self::IF_TABLE  . '20';

    private const array IF_COLUMN_MAP = [
        'description'   => self::IF_DESCR,
        'type'          => self::IF_TYPE,
        'mtu'           => self::IF_MTU,
        'speed'         => self::IF_SPEED,
        'macAddress'    => self::IF_MAC_ADDRESS,
        'adminStatus'   => self::IF_ADMIN_STATUS,
        'operStatus'    => self::IF_OPER_STATUS,
        'lastChange'    => self::IF_LAST_CHANGE,
        'inOctets'      => self::IF_IN_OCTETS,
        'outOctets'     => self::IF_OUT_OCTETS,
        'inErrors'      => self::IF_IN_ERRORS,
        'outErrors'     => self::IF_OUT_ERRORS
    ];


/* GET IF TABLE
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns Columns to get. Defaults to all.
     * @return IfTable[] Table of IF columns.
     * @throws ConnectionException Errors connecting to device.
     * @throws SnmpRequestException Errors getting data from device.
     */
    public function getIfTable(
        array $columns = [
            'description',
            'type',
            'mtu',
            'speed',
            'macAddress',
            'adminStatus',
            'operStatus',
            'lastChange',
            'inOctets',
            'outOctets',
            'inErrors',
            'outErrors'
        ]
    ) : array
    {
        $data = [];
        $table = [];
        $indexes = $this->getColumn( oid: self::IF_INDEX );
        $count = count( $indexes );

        foreach( $columns as $column ) {
            if( isset( self::IF_COLUMN_MAP[$column] ) ) {
                $data[$column] = $this->getColumn( self::IF_COLUMN_MAP[$column], $count );
            }
        }

        foreach( $indexes as $index => $value ) {
            $table[] = new IfTable(
                      index: $index,
                description: self::strVal( data: $data, key: 'description', index: $index ),
                       type: self::intVal( data: $data, key: 'type',        index: $index ),
                        mtu: self::intVal( data: $data, key: 'mtu',         index: $index ),
                      speed: self::intVal( data: $data, key: 'speed',       index: $index ),
                 macAddress: self::strVal( data: $data, key: 'macAddress',  index: $index ),
                adminStatus: self::intVal( data: $data, key: 'adminStatus', index: $index ),
                 operStatus: self::intVal( data: $data, key: 'operStatus',  index: $index ),
                 lastChange: self::intVal( data: $data, key: 'lastChange',  index: $index ),
                   inOctets: self::intVal( data: $data, key: 'inOctets',    index: $index ),
                  outOctets: self::intVal( data: $data, key: 'outOctets',   index: $index ),
                   inErrors: self::intVal( data: $data, key: 'inErrors',    index: $index ),
                  outErrors: self::intVal( data: $data, key: 'outErrors',   index: $index )
            );
        }

        return $table;
    }
}