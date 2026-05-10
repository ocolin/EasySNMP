<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Traits;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use Ocolin\EasySNMP\DTO\IfXEntry;

trait IfXTableTrait
{
    private const string IF_X_TABLE = '1.3.6.1.2.1.31.1.1.1.';
    private const string IF_NAME          = self::IF_X_TABLE . '1';
    private const string IF_IN_MCAST      = self::IF_X_TABLE . '2';
    private const string IF_IN_BCAST      = self::IF_X_TABLE . '3';
    private const string IF_OUT_MCAST     = self::IF_X_TABLE . '4';
    private const string IF_OUT_BCAST     = self::IF_X_TABLE . '5';
    private const string IF_HC_IN_OCTETS  = self::IF_X_TABLE . '6';
    private const string IF_HC_IN_UCAST   = self::IF_X_TABLE . '7';
    private const string IF_HC_IN_MCAST   = self::IF_X_TABLE . '8';
    private const string IF_HC_IN_BCAST   = self::IF_X_TABLE . '9';
    private const string IF_HC_OUT_OCTETS = self::IF_X_TABLE . '10';
    private const string IF_HC_OUT_UCAST  = self::IF_X_TABLE . '11';
    private const string IF_HC_OUT_MCAST  = self::IF_X_TABLE . '12';
    private const string IF_HC_OUT_BCAST  = self::IF_X_TABLE . '13';
    private const string IF_HIGH_SPEED    = self::IF_X_TABLE . '15';
    private const string IF_ALIAS         = self::IF_X_TABLE . '18';

    private const array IFX_COLUMN_MAP = [
        'name'          => self::IF_NAME,
        'inMcast'       => self::IF_IN_MCAST,
        'inBcast'       => self::IF_IN_BCAST,
        'outMcast'      => self::IF_OUT_MCAST,
        'outBcast'      => self::IF_OUT_BCAST,
        'inHcOctets'    => self::IF_HC_IN_OCTETS,
        'inHcUcast'     => self::IF_HC_IN_UCAST,
        'inHcMcast'     => self::IF_HC_IN_MCAST,
        'inHcBcast'     => self::IF_HC_IN_BCAST,
        'outHcOctets'   => self::IF_HC_OUT_OCTETS,
        'outHcUcast'    => self::IF_HC_OUT_UCAST,
        'outHcMcast'    => self::IF_HC_OUT_MCAST,
        'outHcBcast'    => self::IF_HC_OUT_BCAST,
        'highSpeed'     => self::IF_HIGH_SPEED,
        'alias'         => self::IF_ALIAS,
    ];


/* GET IFX TABLE
----------------------------------------------------------------------------- */

    /**
     * @param string[] $columns Columns to get.
     * @return IfXEntry[] Table of Ifx columns.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Errors getting data from device.
     */
    public function getIfXTable(
        array $columns = [
            'name',
            'inMcast',
            'inBcast',
            'outMcast',
            'outBcast',
            'inHcOctets',
            'inHcUcast',
            'inHcMcast',
            'inHcBcast',
            'outHcOctets',
            'outHcUcast',
            'outHcMcast',
            'outHcBcast',
            'highSpeed',
            'alias'
        ],
    ) : array
    {
        $data = [];
        $table = [];

        $indexes = $this->getColumn( oid: self::IF_INDEX );
        $count = count( $indexes );

        foreach( $columns as $column ) {
            if( isset( self::IFX_COLUMN_MAP[$column] ) ) {
                $data[$column] = $this->getColumn( self::IFX_COLUMN_MAP[$column], $count );
            }
        }

        foreach( $indexes as $index => $value ) {
            $table[] = new IfXEntry(
                      index: $index,
                       name: self::strVal( data: $data, key: 'name',        index: $index ),
                    inMcast: self::intVal( data: $data, key: 'inMcast',     index: $index ),
                    inBcast: self::intVal( data: $data, key: 'inBcast',     index: $index ),
                   outMcast: self::intVal( data: $data, key: 'outMcast',    index: $index ),
                   outBcast: self::intVal( data: $data, key: 'outBcast',    index: $index ),
                 inHcOctets: self::intVal( data: $data, key: 'inHcOctets',  index: $index ),
                  inHcUcast: self::intVal( data: $data, key: 'inHcUcast',   index: $index ),
                  inHcMcast: self::intVal( data: $data, key: 'inHcMcast',   index: $index ),
                  inHcBcast: self::intVal( data: $data, key: 'inHcBcast',   index: $index ),
                outHcOctets: self::intVal( data: $data, key: 'outHcOctets', index: $index ),
                 outHcUcast: self::intVal( data: $data, key: 'outHcUcast',  index: $index ),
                 outHcMcast: self::intVal( data: $data, key: 'outHcMcast',  index: $index ),
                 outHcBcast: self::intVal( data: $data, key: 'outHcBcast',  index: $index ),
                  highSpeed: self::intVal( data: $data, key: 'highSpeed',   index: $index ),
                      alias: self::strVal( data: $data, key: 'alias',       index: $index ),
            );
        }

        return $table;
    }
}