<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

class SnmpHelper
{

/* FORMAT MAC ADDRESS
----------------------------------------------------------------------------- */

    /**
     * Convert a raw MAC address into a string.
     *
     * @param ?string $value Raw mac address or null.
     * @return ?string Formatted MAC address or null.
     */
    public static function formatMacAddress( ?string $value ) : ?string
    {
        if( $value === null ) { return null; }

        return strtoupper( implode(
            separator: ':',
                array: str_split( string: bin2hex( $value ), length: 2 )
        ));
    }



/* FORMAT INTERFACE TYPE
----------------------------------------------------------------------------- */

    /**
     * Convert Interface type number to a string label.
     * https://www.iana.org/assignments/ianaiftype-mib/ianaiftype-mib
     *
     * @param ?int $value Interface type numeric value.
     * @return ?string Interface type string label.
     */
    public static function formatIfType( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'other',
            2   => 'regular1822',
            3   => 'hdh1822',
            4   => 'ddnX25',
            5   => 'rfc877x25',
            6   => 'ethernet',
            7   => 'iso88023Csmacd',
            8   => 'iso88024TokenBus',
            9   => 'iso88025TokenRing',
            10  => 'iso88026Man',
            11  => 'starLan',
            12  => 'proteon10Mbit',
            13  => 'proteon80Mbit',
            14  => 'hyperchannel',
            15  => 'fddi',
            16  => 'lapb',
            17  => 'sdlc',
            18  => 'ds1',
            19  => 'e1',
            20  => 'basicISDN',
            21  => 'primaryISDN',
            22  => 'ppp',
            24  => 'softwareLoopback',
            27  => 'slip',
            32  => 'frameRelay',
            33  => 'rs232',
            37  => 'atm',
            48  => 'modem',
            53  => 'propVirtual',
            54  => 'propMultiplexor',
            56  => 'fibreChannel',
            58  => 'frameRelayInterconnect',
            62  => 'fastEther',
            70  => 'channel',
            71  => 'ieee80211',
            77  => 'lapd',
            78  => 'ipSwitch',
            126 => 'ip',
            131 => 'tunnel',
            135 => 'l2vlan',
            136 => 'l3ipvlan',
            142 => 'ipForward',
            160 => 'usb',
            161 => 'ieee8023adLag',
            166 => 'mpls',
            188 => 'radioMAC',
            203 => 'sipTg',
            204 => 'sipSig',
            209 => 'bridge',
            230 => 'adsl2',
            248 => 'pip',
            250 => 'gpon',
            255 => 'bits',
            279 => 'gfast',
            289 => 'ptm',
            303 => 'p2pOverLan',
            default => (string)$value,
        };
    }



/* FORMAT ADMIN STATUS
----------------------------------------------------------------------------- */

    /**
     * Convert Admin Status number to a string label.
     *
     * @param ?int $value Admin status integer value.
     * @return ?string Admin status string value.
     */
    public static function formatAdminStatus( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'up',
            2   => 'down',
            3   => 'testing',
            default => (string)$value,
        };
    }



/* FORMAT OPERATION STATUS
----------------------------------------------------------------------------- */

    /**
     * Convert Operation Status numerical value to a string label.
     *
     * @param ?int $value Operation Status numerical value.
     * @return ?string Operation Status string label.
     */
    public static function formatOperStatus( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'up',
            2   => 'down',
            3   => 'testing',
            4   => 'unknown',
            5   => 'dormant',
            6   => 'notPresent',
            7   => 'lowerLayerDown',
            default => (string)$value,
        };
    }



/* FORMAT ARP TYPE
----------------------------------------------------------------------------- */

    /**
     * Convert ARP table type numbers to a string label.
     *
     * @param ?int $value ARP type integer value.
     * @return ?string ARP type label.
     */
    public static function formatArpType( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1   => 'other',
            2   => 'invalid',
            3   => 'dynamic',
            4   => 'static',
            default => (string)$value,
        };
    }



/* FORMAT UPTIME
----------------------------------------------------------------------------- */

    /**
     * @param ?int $ticks Integer representing uptime.
     * @return ?string Human-readable time.
     */
    public static function formatUpTime( ?int $ticks ) : ?string
    {
        if( $ticks === null ) { return null; }

        $seconds = (int)( $ticks / 100 );
        $days    = (int)( $seconds / 86400 );
        $seconds = $seconds % 86400;
        $hours   = (int)( $seconds / 3600 );
        $seconds = $seconds % 3600;
        $minutes = (int)( $seconds / 60 );
        $seconds = $seconds % 60;

        return "{$days}d {$hours}h {$minutes}m {$seconds}s";
    }



/* FORMAT PORT SPEED
----------------------------------------------------------------------------- */

    /**
     * @param ?int $bps Integer version of speed.
     * @return ?string String version of speed.
     */
    public static function formatSpeed( ?int $bps ) : ?string
    {
        if( $bps === null ) { return null; }

        return match(true) {
            $bps >= 1_000_000_000 => ( $bps / 1_000_000_000 ) . ' Gbps',
            $bps >= 1_000_000     => ( $bps / 1_000_000 ) . ' Mbps',
            $bps >= 1_000         => ( $bps / 1_000 ) . ' Kbps',
            default               => $bps . ' bps',
        };
    }



/* FORMAT LLDP SUB TYPE
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Numerical value.
     * @return ?string String label.
     */
    public static function formatLldpIdSubtype( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }

        return match( $value ) {
            1   => 'chassisComponent',
            2   => 'interfaceAlias',
            3   => 'portComponent',
            4   => 'macAddress',
            5   => 'networkAddress',
            6   => 'interfaceName',
            7   => 'local',
            default => (string)$value,
        };
    }



/* FORMAT LLDP PORT ID SUBTYPE
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Numerical value.
     * @return ?string String label.
     */
    public static function formatPortIdSubtype( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }

        return match( $value ) {
            1   => 'interfaceAlias',
            2   => 'portComponent',
            3   => 'macAddress',
            4   => 'networkAddress',
            5   => 'interfaceName',
            6   => 'agentCircuitId',
            7   => 'local',
            default => (string)$value,
        };
    }



/* FORMAT LLDP CAPABILITIES
----------------------------------------------------------------------------- */

    public static function formatLldpCapabilities( ?string $value ) : ?string
    {
        if( $value === null || $value === '' ) { return null; }

        $raw = ord( $value[0] );
        $capabilities = [];

        if( $raw & 0x01 ) { $capabilities[] = 'other'; }
        if( $raw & 0x02 ) { $capabilities[] = 'repeater'; }
        if( $raw & 0x04 ) { $capabilities[] = 'bridge'; }
        if( $raw & 0x08 ) { $capabilities[] = 'wlanAccessPoint'; }
        if( $raw & 0x10 ) { $capabilities[] = 'router'; }
        if( $raw & 0x20 ) { $capabilities[] = 'telephone'; }
        if( $raw & 0x40 ) { $capabilities[] = 'docsisDevice'; }
        if( $raw & 0x80 ) { $capabilities[] = 'stationOnly'; }

        return empty( $capabilities )
            ? null : implode( separator: ', ', array: $capabilities );
    }


/* FORMAT MAC STATUS
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Unformatted status integer.
     * @return ?string Formatted status label
     */
    public static function formatMacStatus( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1 => 'other',
            2 => 'invalid',
            3 => 'learned',
            4 => 'self',
            5 => 'mgmt',
            default => (string)$value,
        };
    }



/* FORMAT IP FORWARD TYPE
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Unformatted value.
     * @return ?string Formatted value.
     */
    public static function formatIpForwardType( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1 => 'other',
            2 => 'invalid',
            3 => 'direct',
            4 => 'indirect',
            default => (string)$value,
        };
    }



/* FORMAT IP FORWARD PROTOCOL
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Unformatted value.
     * @return ?string Formatted value.
     */
    public static function formatIpForwardProto( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1  => 'other',
            2  => 'local',
            3  => 'netmgmt',
            4  => 'icmp',
            8  => 'egp',
            9  => 'ggp',
            10 => 'hello',
            11 => 'rip',
            12 => 'isIs',
            13 => 'esIs',
            14 => 'ciscoIgrp',
            15 => 'bbnSpfIgp',
            16 => 'ospf',
            17 => 'bgp',
            18 => 'idpr',
            19 => 'ciscoEigrp',
            default => (string)$value,
        };
    }



/* FORMAT ROW STATUS
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Unformatted value.
     * @return ?string Formatted value.
     */
    public static function formatRowStatus( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1 => 'active',
            2 => 'notInService',
            3 => 'notReady',
            4 => 'createAndGo',
            5 => 'createAndWait',
            6 => 'destroy',
            default => (string)$value,
        };
    }



/* FORMAT STD PORT STATUS
----------------------------------------------------------------------------- */

    /**
     * @param ?int $value Raw integer value.
     * @return ?string String label.
     */
    public static function formatStpPortState( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1 => 'disabled',
            2 => 'blocking',
            3 => 'listening',
            4 => 'learning',
            5 => 'forwarding',
            6 => 'broken',
            default => (string)$value,
        };
    }



/* FORMAT BRIDGE ID
----------------------------------------------------------------------------- */

    /**
     * @param ?string $value Unformatted string.
     * @return ?string Formatted string.
     */
    public static function formatBridgeId( ?string $value ) : ?string
    {
        if( $value === null || strlen( $value ) !== 8 ) { return null; }

        $hex      = bin2hex( $value );
        $priority = hexdec( substr( string: $hex, offset: 0, length: 4 ) );
        $mac      = implode( separator: ':', array: str_split(
            string: substr( string: $hex, offset: 4 ), length: 2 )
        );

        return "{$priority}/{$mac}";
    }



/* FORMAT STP PORT ID
----------------------------------------------------------------------------- */

    /**
     * @param ?string $value Unformatted string.
     * @return ?string Formatted string.
     */
    public static function formatStpPortId( ?string $value ) : ?string
    {
        if( $value === null || strlen( $value ) !== 2 ) { return null; }

        $hex      = bin2hex( $value );
        $priority = hexdec( substr( string: $hex, offset: 0, length: 2 ) );
        $port     = hexdec( substr( string: $hex, offset: 2, length: 2 ) );

        return "{$priority}/{$port}";
    }



/* FORMAT STP PORT ENABLE
----------------------------------------------------------------------------- */

    public static function formatStpPortEnable( ?int $value ) : ?string
    {
        if( $value === null ) { return null; }
        return match( $value ) {
            1 => 'enabled',
            2 => 'disabled',
            default => (string)$value,
        };
    }
}