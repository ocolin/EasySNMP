<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Exception;
use Ocolin\Env\EasyEnv;
use stdClass;

class EasySNMP
{
    /**
     * @var string IP address of device to query
     */
    private string $ip;

    /**
     * @var string SNMP community string
     */
    private string $community;

    /**
     * @var int Version of SNMP (v3 not supported)
     */
    private int $version;

    /**
     * @var array|string[] List of Data types that are integers
     */
    public static array $int_types = [
        'INTEGER',
        'INTEGER32',
        'UINTEGER32',
        'COUNTER32',
        'COUNTER64',
        'GUAGE32'
    ];


/* CONSTRUCTOR
---------------------------------------------------------------------------- */

    /**
     * @param string $ip IP address of device
     * @param string|null $community SNMP community string
     * @param int|null $version SNMP version to use
     * @param bool $local Get local environment variables
     * @throws Exception
     */
    public function __construct(
         string $ip,
        ?string $community = null,
           ?int $version   = null,
           bool $local     = false
    ) {
        if( $local === true ) {
            EasyEnv::loadEnv(path: __DIR__ . '/../.env' );
        }

        $this->ip = self::validate_IP( ip: $ip );
        $this->community = $community ?? $_ENV['SNMP_COMMUNITY'] ?? 'public';
        $this->version = self::validate_Version( version: $version );
    }



/* PERFORM AN SNMP GET REQUEST
---------------------------------------------------------------------------- */

    /**
     * @param string $oid OID to query
     * @param bool $numeric Use numerical output
     * @return object|null Data object or null if not found
     * @throws Exception
     */
    public function get( string $oid, bool $numeric = false ) : object|null
    {
        $output = $this->execute( cmd: 'snmpget', oid: $oid, numeric: $numeric );

        if( !empty( $output[0] )) {
            if( str_contains(
                haystack: $output[0],
                  needle: 'No Such Object available on this agent at this OID'
            )) {
                return null;
            }

            return self::parse_Row( $output[0] );
        }

        return null;
    }


/* PERFORM AN SNMP GET NEXT REQUEST
---------------------------------------------------------------------------- */

    /**
     * @param string $oid Next OID to query
     * @param bool $numeric Use numerical output
     * @return object|null Data object or null if not found
     * @throws Exception
     */
    public function getNext( string $oid, bool $numeric = false ) : object|null
    {
        $output = $this->execute( cmd: 'snmpgetnext', oid: $oid, numeric: $numeric );

        if( !empty( $output[0] )) {
            if( str_contains(
                haystack: $output[0],
                  needle: 'No Such Object available on this agent at this OID'
            )) {
                return null;
            }

            return self::parse_Row( $output[0] );
        }

        return null;
    }



/* PERFORM AN SNMP WALK OVER AN SNMP TREE
---------------------------------------------------------------------------- */

    /**
     * @param string $oid Root OID to start walk from
     * @param bool $numeric Use numerical names
     * @return object[] Array of row objects
     * @throws Exception
     */
    public function walk(
        string $oid       = '',
          bool $bulk      = true,
          bool $numeric   = false,
          bool $enumerate = true
    ) : array
    {
        $cmd = $bulk ? 'snmpbulkwalk' : 'snmpwalk';

        $output = [];
        $rows =  $this->execute(
                    cmd: $cmd,
                    oid: $oid,
                numeric: $numeric,
            enumeration: $enumerate
        );

        foreach( $rows as $row )
        {
            $output[] = self::parse_Row( row: $row );
        }

        return $output;
    }



/* EXECUTE AN SNMP COMMAND
---------------------------------------------------------------------------- */

    /**
     * @param string $cmd SNMP command to use
     * @param string $oid OID to grab from device
     * @param bool $numeric Numerical output only
     * @return array<int, string>
     * @throws Exception
     */
    public function execute(
        string $cmd,
        string $oid         = '',
          bool $numeric     = true,
          bool $enumeration = true
    ): array
    {
        $allowed_commands = [
            'snmpwalk',
            'snmpbulkwalk',
            'snmpget',
            'snmpgetnext'
        ];
        $num = '';
        $enum = 'e';

        // VALIDATE COMMAND TO SEND
        if( !in_array( needle: $cmd, haystack: $allowed_commands )) {
            throw new Exception( message: "$cmd is not a valid SNMP command." );
        }

        // PREVENT MALICIOUS CODE FROM BEING EXECUTED
        if( !empty( $oid ) AND !self::validate_OID( oid: $oid )) {
            $oid = '';
        }

        // ADD NUMERICAL FLAG IF SPECIFIED
        if( $numeric === true ) {
            $num = 'n';
        }

        if( $enumeration === false ) {
            $enum = '';
        }


        // EXECUTE COMMAND
        $version = self::create_Version_Tag( number: $this->version );
        $exec = "$cmd $version -c '$this->community' -Os{$enum}t$num $this->ip $oid";
        exec( command: "$exec 2> /dev/null", output: $output );

        return $output;
    }



/* PARSE A ROW FROM SNMP CLI OUTPUT
---------------------------------------------------------------------------- */

    public static function parse_Row( string $row ) : object
    {
        $output = new stdClass();
        $output->origin = $row;
        $output->type = '';
        $output->value = '';

        // MIKROTIKS SOMETIMES SHOW ERRORS
        $row = str_replace(
             search: "Wrong Type (should be INTEGER): ",
            replace: '',
            subject: $row
        );

        list( $output->oid, $row ) = explode( separator: ' = ', string: (string)$row );

        // IF THERE IS A TYPE BUT NO VALUE - VALUE COULD HAVE COLON IN IT
        if( preg_match( pattern: "#.+:$#i", subject: $row )) {
            $output->type = trim( substr( string: $row, offset: 0, length: -1 ));
        }

        // IF THERE IS BOTH VALUE AND TYPE
        elseif( str_contains( haystack: $row, needle: ': ' )) {
            list( $output->type, $output->value ) = explode(
                separator: ': ',
                   string: $row,
                    limit: 2
            );
        }
        else {
            $output->value = $row;
        }

        // SOME TYPES USE INTEGER VALUES
        if( in_array( needle: strtoupper($output->type), haystack: self::$int_types )) {
            $output->value = (int)$output->value;
        }

        if( is_string( $output->value )) {
            $output->value = trim( string: $output->value, characters: '"' );
        }

        return $output;
    }



/* VALIDATE IP ADDRESS
---------------------------------------------------------------------------- */

    /**
     * @param string $ip
     * @return string
     * @throws Exception
     */
    public static function validate_IP( string $ip ) : string
    {
        if( !filter_var(
              value: $ip,
             filter: FILTER_VALIDATE_IP,
            options: FILTER_FLAG_IPV4
        )) {
            throw new Exception( message: "$ip is not a valid IPv4 address." );
        }

        return $ip;
    }



/* VALIDATE SNMP COMMUNITY STRING
---------------------------------------------------------------------------- */

    public static function validate_Version( ?int $version = null ) : int
    {
        $allowed = [ 1, 2 ];
        if( $version !== null AND in_array( needle: $version, haystack: $allowed )) {
            return $version;
        }

        if( !empty( $_ENV['SNMP_VERSION']) AND in_array(
              needle: $_ENV['SNMP_VERSION'],
            haystack: $allowed )
        ) {
            return $_ENV['SNMP_VERSION'];
        }

        return 2;
    }



/* CREATE VERSION TAG FOR CLI ARGUMENT
---------------------------------------------------------------------------- */

    public static function create_Version_Tag( int $number ) : string
    {
        return match( $number ) {
            2 => '-v2c',
            default => '-v1',
        };
    }



/* VALIDATE AN OID
---------------------------------------------------------------------------- */

    public static function validate_OID( string $oid ) : int|false
    {
        return preg_match( pattern: "#^\.?([0-2])((\.0)|(\.[1-9][0-9]*))*$#", subject: $oid );
    }
}