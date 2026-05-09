<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Ocolin\EasySNMP\DTO\System;
use Ocolin\EasySNMP\DTO\IfTable;
use Ocolin\EasySNMP\DTO\IfXTable;
use Ocolin\EasySNMP\DTO\ArpTable;

use Ocolin\EasySNMP\Formatted\System AS FormattedSystem;
use Ocolin\EasySNMP\Formatted\IfTable as FormattedIfTable;
use Ocolin\EasySNMP\Formatted\IfXTable as FormattedIfXTable;
use Ocolin\EasySNMP\Formatted\ArpTable as FormattedArpTable;

class Format
{

/*
----------------------------------------------------------------------------- */

    /**
     * @param System $system Unformatted System object.
     * @return FormattedSystem Formatted System object.
     */
    public static function System( System $system ) : FormattedSystem
    {
        return new FormattedSystem(
               descr: $system->descr,
              upTime: SnmpHelper::formatUpTime( ticks: $system->upTime ),
             contact: $system->contact,
                name: $system->name,
            location: $system->location,
                 oid: $system->oid,
        );
    }


/*
----------------------------------------------------------------------------- */

    /**
     * @param IfTable[] $ifTables Array of unformatted Interfaces.
     * @return FormattedIfTable[] Array of formatted Interfaces.
     */
    public static function IfTables( array $ifTables ) : array
    {
        $output = [];

        foreach( $ifTables as $ifTable )
        {
            $output[] = self::IfTable( $ifTable );
        }

        return $output;
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param IfTable $ifTable Unformatted Interface table.
     * @return FormattedIfTable Formatted Interface table/
     */
    public static function IfTable( IfTable $ifTable ) : FormattedIfTable
    {
        return new FormattedIfTable(
                  index: $ifTable->index,
            description: $ifTable->description,
                   type: SnmpHelper::formatIfType( $ifTable->type ),
                    mtu: $ifTable->mtu,
                  speed: SnmpHelper::formatSpeed( $ifTable->speed ),
             macAddress: SnmpHelper::formatMacAddress( $ifTable->macAddress ),
            adminStatus: SnmpHelper::formatAdminStatus( $ifTable->adminStatus ),
             operStatus: SnmpHelper::formatOperStatus( $ifTable->operStatus ),
             lastChange: $ifTable->lastChange,
               inOctets: $ifTable->inOctets,
              outOctets: $ifTable->outOctets,
               inErrors: $ifTable->inErrors,
              outErrors: $ifTable->outErrors,
        );
    }



/*
----------------------------------------------------------------------------- */

    /**
     * @param ArpTable[] $arpTables List of unformatted ARP tables.
     * @return FormattedArpTable[] List of formatted ARP tables.
     */
    public static function ArpTables( array $arpTables ) : array
    {
        $output = [];
        foreach( $arpTables as $arpTable )
        {
            $output[] = self::ArpTable( $arpTable );
        }

        return $output;
    }

/*
----------------------------------------------------------------------------- */

    /**
     * @param ArpTable $arpTable Unformatted ARP entry.
     * @return FormattedArpTable Formatted ARP entry.
     */
    public static function ArpTable( ArpTable $arpTable ) : FormattedArpTable
    {
        return new FormattedArpTable(
            interface: $arpTable->interface,
                  mac: SnmpHelper::formatMacAddress( $arpTable->mac ),
            ipAddress: $arpTable->ipAddress,
                 type: SnmpHelper::formatArpType( $arpTable->type )
        );
    }
}