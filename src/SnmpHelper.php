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
            6   => 'ethernet',
            7   => 'iso88023Csmacd',
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
}