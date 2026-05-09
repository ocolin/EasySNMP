<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use FreeDSx\Snmp\Exception\ConnectionException;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use FreeDSx\Snmp\OidList;
use FreeDSx\Snmp\SnmpClient;
use FreeDSx\Snmp\Oid;
use FreeDSx\Snmp\SnmpWalk;
use FreeDSx\Snmp\Value\IntegerValue;
use FreeDSx\Snmp\Value\CounterValue;
use FreeDSx\Snmp\Value\BigCounterValue;
use FreeDSx\Snmp\Value\UnsignedIntegerValue;
use FreeDSx\Snmp\Value\IpAddressValue;
use FreeDSx\Snmp\Value\TimeTicksValue;

use Ocolin\EasySNMP\Traits\IfTableTrait;
use Ocolin\EasySNMP\Traits\IfXTableTrait;
use Ocolin\EasySNMP\Traits\SystemTrait;
use Ocolin\EasySNMP\Traits\ArpTableTrait;
use Ocolin\EasySNMP\Traits\LldpRemTableTrait;
use Ocolin\EasySNMP\Traits\IpAddrTableTrait;
use Ocolin\EasySNMP\Traits\MacTableTrait;

class EasySNMP
{
    /**
     * @var SnmpClient SNMP client.
     */
    private SnmpClient $client;

    use IfTableTrait;
    use IfXTableTrait;
    use SystemTrait;
    use ArpTableTrait;
    use LldpRemTableTrait;
    use IpAddrTableTrait;
    use MacTableTrait;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?Config $config Configuration data.
     * @param ?SnmpClient $client Optional SNMP client.
     */
    public function __construct(
            ?Config $config = null,
        ?SnmpClient $client = null
    )
    {
        $config = $config ?? new Config();
        // FreeDSx has depreciated null type issue in walk().
        $previous = error_reporting( E_ALL & ~E_DEPRECATED );
        $this->client = $client ?? new SnmpClient( $config->getOptions() );
        error_reporting( $previous );
    }



/* BULK WALK
----------------------------------------------------------------------------- */

    /**
     * Bulk get data from device.
     *
     * @param string $oid OID to start bulk walk.
     * @param int $maxRepetitions Maximum repetitions.
     * @return Oid[] List of OID objects.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error getting device data.
     */
    public function bulkWalk( string $oid,  int $maxRepetitions = 20 ) : array
    {
        $baseOid = $oid;
        $results = [];

        do {
            // FreeDSx has a PHP 8.4 nullable type deprecation in walk().
            // Suppress during getBulk() call only. PR pending upstream.
            $previous = error_reporting( error_level: E_ALL & ~E_DEPRECATED );
            $response = $this->client->getBulk(
                maxRepetitions: $maxRepetitions,
                  nonRepeaters: 0,
                          oids: $oid
            );
            error_reporting( $previous );

            foreach( $response as $object ) {
                if( !str_starts_with( $object->getOid(), $baseOid ) ) {
                    break 2;
                }
                $results[] = $object;
                $oid = $object->getOid();
            }
        } while( count( $response ) > 0 );

        return $results;
    }



/* FREEDSX GET
----------------------------------------------------------------------------- */

    /**
     * @param Oid|string ...$oids OIDs to fetch.
     * @return OidList<Oid> OID objects.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function get( Oid|string ...$oids ) : OidList
    {
        return $this->client->get( ...$oids );
    }



/* FREEDESX GET NEXT
----------------------------------------------------------------------------- */

    /**
     * @param Oid|string ...$oids OIDs to fetch.
     * @return OidList<Oid> OID objects.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error reading SNMP response.
     */
    public function getNext( Oid|string ...$oids ) : OidList
    {
        return $this->client->getNext(...$oids );
    }



/* FREEDSX WALK
----------------------------------------------------------------------------- */

    /**
     * @param ?string $startAt Walk starting OID.
     * @param ?string $endAt Walk ending OID.
     * @return SnmpWalk Walk object.
     */
    public function walk( ?string $startAt = null, ?string $endAt = null ) : SnmpWalk
    {
        // FreeDSx has depreciated null type issue in walk().
        $previous = error_reporting( error_level: E_ALL & ~E_DEPRECATED );
        $walk =  $this->client->walk( startAt: $startAt, endAt: $endAt );
        error_reporting( $previous );

        return $walk;
    }



/* GET SNMP TABLE COLUMN
----------------------------------------------------------------------------- */

    /**
     * @param string $oid OID of column.
     * @param int $count Number of rows to get.
     * @return array<int, string|int> Column array.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error getting data from device.
     */
    protected function getColumn( string $oid, int $count = 20 ) : array
    {
        $output = [];
        foreach(
            $this->bulkWalk( oid: $oid,  maxRepetitions: $count ) as $object
        )
        {
            $index = (int)substr(
                string: $object->getOid(), offset: strrpos(
                    haystack: $object->getOid(), needle: '.'
                ) + 1
            );
            $value = $object->getValue();
            $output[$index] = match(true) {
                $value instanceof IntegerValue        => (int)(string)$value,
                $value instanceof CounterValue        => (int)(string)$value,
                $value instanceof BigCounterValue     => (int)(string)$value,
                $value instanceof UnsignedIntegerValue => (int)(string)$value,
                $value instanceof IpAddressValue      => (string)$value,
                $value instanceof TimeTicksValue      => (int)(string)$value,
                default                               => (string)$value,
            };
        }

        return $output;
    }



/* GET SNMP TABLE COLUMN WITH COMPOSITE INDEX
----------------------------------------------------------------------------- */

    /**
     * @param string $oid OID of table to retrieve.
     * @param int $count Number of rows to get.
     * @return array<string, string|int> Column array.
     * @throws ConnectionException Error connecting to device.
     * @throws SnmpRequestException Error getting SNMP data.
     */
    protected function getCompositeColumn( string $oid, int $count = 20 ) : array
    {
        $output = [];
        foreach(
            $this->bulkWalk( oid: $oid,  maxRepetitions: $count ) as $object
        )
        {
            $index = substr(
                string: $object->getOid(),
                offset: strlen( $oid ) + 1
            );
            $value = $object->getValue();
            $output[$index] = match(true) {
                $value instanceof IntegerValue        => (int)(string)$value,
                $value instanceof CounterValue        => (int)(string)$value,
                $value instanceof BigCounterValue     => (int)(string)$value,
                $value instanceof UnsignedIntegerValue => (int)(string)$value,
                $value instanceof IpAddressValue      => (string)$value,
                $value instanceof TimeTicksValue      => (int)(string)$value,
                default                               => (string)$value,
            };
        }

        return $output;
    }



/* CONVERT STRING VALUE
----------------------------------------------------------------------------- */

    /**
     * @param array<string, array<int, int|string>> $data Array of data.
     * @param string $key Name array section.
     * @param int $index Index of array section.
     * @return ?string String value.
     */
    protected static function strVal( array $data, string $key, int $index ) : ?string
    {
        return isset( $data[$key][$index] ) ? (string)$data[$key][$index] : null;
    }



/* CONVERT INTEGER VALUE
----------------------------------------------------------------------------- */

    /**
     * Convert data to an integer value.
     *
     * @param array<string, array<int, int|string>> $data Array of data.
     * @param string $key Name array section.
     * @param int $index Index of array section.
     * @return ?int Integer value.
     */
    protected static function intVal( array $data, string $key, int $index ) : ?int
    {
        return isset( $data[$key][$index] ) ? (int)$data[$key][$index] : null;
    }
}