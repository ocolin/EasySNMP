<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Ocolin\EasySNMP\Errors\EasySnmpInvalidCmdError;
use Ocolin\EasySNMP\Errors\EasySnmpInvalidIpError;
use Ocolin\EasySNMP\Errors\EasySnmpMissingCommunityError;
use Ocolin\EasySNMP\Errors\EasySnmpInvalidOidError;
use stdClass;

class SNMP
{
    /**
     * @var string IP address of device.
     */
    private string $ip;

    /**
     * @var string Community string of device
     */
    private string $community;

    /**
     * @var string SNMP version of device (3 not supported yet))
     */
    private string $version;


/* CONSTRUCTOR
---------------------------------------------------------------------------- */

    /**
     * @param string|null $ip IP address to query.
     * @param string|null $community Community string of device.
     * @param int|null $version SNMP version of query.
     * @throws EasySnmpInvalidIpError Bad IP address.
     * @throws EasySnmpMissingCommunityError Missing community string.
     */
    public function __construct(
        ?string $ip = null,
        ?string $community = null,
           ?int $version = 2,
         string $prefix = ''
    )
    {
        $this->ip = self::get_IP( ip: $ip, prefix: $prefix );
        $this->community = self::get_Community(
            community: $community,
               prefix: $prefix
        );
        $this->version = self::get_Version( version: $version, prefix: $prefix );
    }



/* SNMP GET
---------------------------------------------------------------------------- */

    /**
     * @param string $oid SNMP OID to get.
     * @param bool $numeric Return row names as numerical OIDs.
     * @return object|null Return SNMP object or NULL if it fails.
     * @throws EasySnmpInvalidCmdError
     * @throws EasySnmpInvalidOidError
     */
    public function get( string $oid, bool $numeric = false ) : object|null
    {
        $output = $this->execute( command: 'snmpget', oid: $oid, numeric: $numeric );

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
     * @param string $oid Next OID to query.
     * @param bool $numeric Use numerical output.
     * @return object|null Data object or null if not found.
     * @throws EasySnmpInvalidCmdError
     * @throws EasySnmpInvalidOidError
     */
    public function getNext( string $oid, bool $numeric = false ) : object|null
    {
        $output = $this->execute(
            command: 'snmpgetnext',
                oid: $oid,
            numeric: $numeric
        );

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
     * @param string $oid Root OID to start walk from. Omit for full tree.
     * @param bool $numeric Use numerical names.
     * @return object[] Array of row objects.
     * @throws EasySnmpInvalidCmdError
     * @throws EasySnmpInvalidOidError
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
                command: $cmd,
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



/* EXECUTE SNMP COMMAND
---------------------------------------------------------------------------- */

    /**
     * @param string $command SNMP command to run.
     * @param string $oid OID of SNMP tree.
     * @param bool $numeric Return numeric OIDs
     * @param bool $enumeration Removes the symbolic labels from enumeration values
     * @return list<string> SNMP response data.
     * @throws EasySnmpInvalidCmdError
     * @throws EasySnmpInvalidOidError
     */
    private function execute(
        string $command,
        string $oid = '',
          bool $numeric = true,
          bool $enumeration = true
    ) : array
    {
        if( !in_array( needle: $command, haystack: self::allowed_Commands())) {
            throw new EasySnmpInvalidCmdError(
                message: "Invalid SNMP command: '{$command}'"
            );
        }
        self::validate_OID( oid: $oid );
        $num = '';
        $enum = 'e';

        // ADD NUMERICAL FLAG IF SPECIFIED
        if( $numeric === true ) { $num = 'n'; }
        if( $enumeration === false ) { $enum = ''; }

        $exec = "$command {$this->version} -c '$this->community' -Os{$enum}t$num $this->ip $oid";
        exec( command: "$exec 2> /dev/null", output: $output );

        return $output;
    }



/* PARSE A ROW FROM SNMP CLI OUTPUT
---------------------------------------------------------------------------- */

    /**
     * @param string $row SNMP output text row.
     * @return stdClass Object version of row output.
     */
    public static function parse_Row( string $row ) : object
    {
        $output = new stdClass();
        $output->origin = $row;
        $output->type   = '';
        $output->value  = '';

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
        if( in_array( needle: strtoupper($output->type), haystack: self::integer_Types() )) {
            $output->value = (int)$output->value;
        }

        if( is_string( $output->value )) {
            $output->value = trim( string: $output->value, characters: '"' );
        }

        return $output;
    }



/* GET IP ADDRESS
---------------------------------------------------------------------------- */

    /**
     * @param string|null $ip IP address from optional argument.
     * @return string Validated IP address.
     * @throws EasySnmpInvalidIpError
     */
    public static function get_IP(
        ?string $ip = null,
         string $prefix = ''
    ) : string
    {
        if( empty( $prefix ) AND !empty( $_ENV['SNMP_IP'])) {
            $ip = $_ENV['SNMP_IP'];
        }
        else if( !empty( $prefix ) AND !empty( $_ENV[ $prefix . '_SNMP_IP' ] )) {
            $ip = $_ENV[ $prefix . '_SNMP_IP' ];
        }

        if(
            $ip === null OR
            !filter_var( value: $ip, filter: FILTER_VALIDATE_IP ) OR
            !is_string( value: $ip )
        ) {
            throw new EasySnmpInvalidIpError(
                message: "Invalid or missing IP address."
            );
        }

        return $ip;
    }



/* GET SNMP VERSION
---------------------------------------------------------------------------- */

    /**
     * @param int|null $version Numeric version of SNMP from arg.
     * @return string CLI argument for SNMP version.
     */
    public static function get_Version(
          ?int $version = null,
        string $prefix = ''
    ) : string
    {
        if( empty( $prefix ) AND !empty( $_ENV['SNMP_VERSION'])) {
            $version = $_ENV['SNMP_VERSION'];
        }
        else if( !empty( $prefix ) AND !empty( $_ENV[ $prefix . '_SNMP_VERSION' ] )) {
            $version = $_ENV[ $prefix . '_SNMP_VERSION' ];
        }

        return match( $version )
        {
            1 => '-v1',
            default => '-v2c',
        };
    }



/* GET SNMP COMMUNITY STRING
---------------------------------------------------------------------------- */

    /**
     * @param string|null $community Argument community string if supplied.
     * @return string Escaped community string.
     * @throws EasySnmpMissingCommunityError No community provided.
     */
    public static function get_Community(
        ?string $community = null,
         string $prefix = ''
    ) : string
    {
        if( $community === null ) {
            if( !empty( $prefix ) and !empty( $_ENV[ $prefix . '_SNMP_COMMUNITY'] )) {
                $community = $_ENV[ $prefix . '_SNMP_COMMUNITY' ];
            }
            else if( empty( $prefix ) AND !empty( $_ENV['SNMP_COMMUNITY'] )) {
                $community = $_ENV['SNMP_COMMUNITY'];
            }

            if( empty( $community ) or !is_string( value: $community )) {
                throw new EasySnmpMissingCommunityError(
                    message: "Invalid community string."
                );
            }
        }

        return $community;
    }



/* LIST OF SUPPORTED COMMAND
---------------------------------------------------------------------------- */

    /**
     * @return string[] List of allowed commands.
     */
    public static function allowed_Commands() : array
    {
        return [
            'snmpwalk',
            'snmpbulkwalk',
            'snmpget',
            'snmpgetnext'
        ];
    }


/* VALIDATE AN OID
---------------------------------------------------------------------------- */

    /**
     * @param string $oid OID of query.
     * @return void
     * @throws EasySnmpInvalidOidError
     */
    public static function validate_OID( string $oid ) : void
    {
        if(
            !empty( $oid ) AND
            !preg_match( pattern: "#^\.?([0-2])((\.0)|(\.[1-9][0-9]*))*$#", subject: $oid )
        ) {
            throw new EasySnmpInvalidOidError( message: "Invalid OID '{$oid}'" );
        }
    }



/* GET LIST OF VALID SNMP INTEGER TYPES
---------------------------------------------------------------------------- */

    /**
     * @return string[] List of SNMP Integer types
     */
    public static function integer_Types() : array
    {
        return [
            'INTEGER',
            'INTEGER32',
            'UINTEGER32',
            'COUNTER32',
            'COUNTER64',
            'GUAGE32'
        ];
    }
}