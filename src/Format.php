<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Ocolin\EasySNMP\DTO\System;
use Ocolin\EasySNMP\DTO\IfTable;
use Ocolin\EasySNMP\DTO\IfXTable;
use Ocolin\EasySNMP\DTO\ArpTable;
use Ocolin\EasySNMP\DTO\LldpRemTable;

use Ocolin\EasySNMP\Formatted\System AS FormattedSystem;
use Ocolin\EasySNMP\Formatted\IfTable as FormattedIfTable;
use Ocolin\EasySNMP\Formatted\IfXTable as FormattedIfXTable;
use Ocolin\EasySNMP\Formatted\ArpTable as FormattedArpTable;
use Ocolin\EasySNMP\Formatted\LldpRemTable as FormattedLldpRemTable;

class Format
{

/* FORMAT SYSTEM OBJECT
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


/* FORMAT IF TABLE
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



/* FORMAT IF TABLE ENTRY
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



/* FORMAT ARP TABLE
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



/* FORMAT ARP TABLE ENTRY
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



/* FORMAT LLDP REMOTE TABLE
----------------------------------------------------------------------------- */

    /**
     * @param LldpRemTable[] $lldpRemTables List of unformatted tables
     * @return FormattedLldpRemTable[] List of formatted tables.
     */
    public static function LldpRemTables( array $lldpRemTables ) : array
    {
        $output = [];
        foreach( $lldpRemTables as $lldpRemTable )
        {
            $output[] = self::LldpRemTable( $lldpRemTable );
        }

        return $output;
    }


/* FORMAT LLDP REMOTE TABLE ENTRY
----------------------------------------------------------------------------- */

    /**
     * @param LldpRemTable $lldpRemTable Unformatted LLDP remote entry.
     * @return FormattedLldpRemTable Formatting LLDP remote entry.
     */
    public static function LldpRemTable( LldpRemTable $lldpRemTable ) : FormattedLldpRemTable
    {
        return new FormattedLldpRemTable(
                localPort: $lldpRemTable->localPort,
            chassisIdType: SnmpHelper::formatLldpIdSubtype( $lldpRemTable->chassisIdType ),
                chassisId: $lldpRemTable->chassisIdType === 4
                ? SnmpHelper::formatMacAddress( $lldpRemTable->chassisId )
                : $lldpRemTable->chassisId,
               portIdType: SnmpHelper::formatPortIdSubtype( $lldpRemTable->portIdType ),
                   portId: $lldpRemTable->portId,
                 portDesc: $lldpRemTable->portDesc,
                  sysName: $lldpRemTable->sysName,
                  sysDesc: $lldpRemTable->sysDesc,
             capSupported: SnmpHelper::formatLldpCapabilities( $lldpRemTable->capSupported ),
               capEnabled: SnmpHelper::formatLldpCapabilities( $lldpRemTable->capEnabled ),
        );
    }
}