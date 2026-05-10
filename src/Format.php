<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

use Ocolin\EasySNMP\DTO\System;
use Ocolin\EasySNMP\DTO\IfEntry;
use Ocolin\EasySNMP\DTO\ArpEntry;
use Ocolin\EasySNMP\DTO\LldpRemEntry;
use Ocolin\EasySNMP\DTO\MacEntry;
use Ocolin\EasySNMP\DTO\IpForwardEntry;
use Ocolin\EasySNMP\DTO\StpPortEntry;

use Ocolin\EasySNMP\Formatted\System AS FormattedSystem;
use Ocolin\EasySNMP\Formatted\IfEntry as FormattedIfEntry;
use Ocolin\EasySNMP\Formatted\ArpEntry as FormattedArpEntry;
use Ocolin\EasySNMP\Formatted\LldpRemEntry as FormattedLldpRemEntry;
use Ocolin\EasySNMP\Formatted\MacEntry as FormattedMacEntry;
use Ocolin\EasySNMP\Formatted\IpForwardEntry as FormattedIpForwardEntry;
use Ocolin\EasySNMP\Formatted\StpPortEntry as FormattedStpPortEntry;

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
     * @param IfEntry[] $ifTable Array of unformatted Interfaces.
     * @return FormattedIfEntry[] Array of formatted Interfaces.
     */
    public static function IfTable( array $ifTable ) : array
    {
        $output = [];

        foreach( $ifTable as $ifEntry )
        {
            $output[] = self::IfEntry( $ifEntry );
        }

        return $output;
    }



/* FORMAT IF TABLE ENTRY
----------------------------------------------------------------------------- */

    /**
     * @param IfEntry $ifEntry Unformatted Interface table.
     * @return FormattedIfEntry Formatted Interface table/
     */
    public static function IfEntry( IfEntry $ifEntry ) : FormattedIfEntry
    {
        return new FormattedIfEntry(
                  index: $ifEntry->index,
            description: $ifEntry->description,
                   type: SnmpHelper::formatIfType( $ifEntry->type ),
                    mtu: $ifEntry->mtu,
                  speed: SnmpHelper::formatSpeed( $ifEntry->speed ),
             macAddress: SnmpHelper::formatMacAddress( $ifEntry->macAddress ),
            adminStatus: SnmpHelper::formatAdminStatus( $ifEntry->adminStatus ),
             operStatus: SnmpHelper::formatOperStatus( $ifEntry->operStatus ),
             lastChange: $ifEntry->lastChange,
               inOctets: $ifEntry->inOctets,
              outOctets: $ifEntry->outOctets,
               inErrors: $ifEntry->inErrors,
              outErrors: $ifEntry->outErrors,
        );
    }



/* FORMAT ARP TABLE
----------------------------------------------------------------------------- */

    /**
     * @param ArpEntry[] $arpTable List of unformatted ARP tables.
     * @return FormattedArpEntry[] List of formatted ARP tables.
     */
    public static function ArpTable( array $arpTable ) : array
    {
        $output = [];
        foreach( $arpTable as $arpEntry )
        {
            $output[] = self::ArpEntry( $arpEntry );
        }

        return $output;
    }



/* FORMAT ARP TABLE ENTRY
----------------------------------------------------------------------------- */

    /**
     * @param ArpEntry $arpEntry Unformatted ARP entry.
     * @return FormattedArpEntry Formatted ARP entry.
     */
    public static function ArpEntry( ArpEntry $arpEntry ) : FormattedArpEntry
    {
        return new FormattedArpEntry(
            interface: $arpEntry->interface,
                  mac: SnmpHelper::formatMacAddress( $arpEntry->mac ),
            ipAddress: $arpEntry->ipAddress,
                 type: SnmpHelper::formatArpType( $arpEntry->type )
        );
    }



/* FORMAT LLDP REMOTE TABLE
----------------------------------------------------------------------------- */

    /**
     * @param LldpRemEntry[] $lldpRemTable List of unformatted tables
     * @return FormattedLldpRemEntry[] List of formatted tables.
     */
    public static function LldpRemTable( array $lldpRemTable ) : array
    {
        $output = [];
        foreach( $lldpRemTable as $lldpRemEntry )
        {
            $output[] = self::LldpRemEntry( $lldpRemEntry );
        }

        return $output;
    }


/* FORMAT LLDP REMOTE TABLE ENTRY
----------------------------------------------------------------------------- */

    /**
     * @param LldpRemEntry $lldpRemEntry Unformatted LLDP remote entry.
     * @return FormattedLldpRemEntry Formatting LLDP remote entry.
     */
    public static function LldpRemEntry( LldpRemEntry $lldpRemEntry ) : FormattedLldpRemEntry
    {
        return new FormattedLldpRemEntry(
                localPort: $lldpRemEntry->localPort,
            chassisIdType: SnmpHelper::formatLldpIdSubtype( $lldpRemEntry->chassisIdType ),
                chassisId: $lldpRemEntry->chassisIdType === 4
                ? SnmpHelper::formatMacAddress( $lldpRemEntry->chassisId )
                : $lldpRemEntry->chassisId,
               portIdType: SnmpHelper::formatPortIdSubtype( $lldpRemEntry->portIdType ),
                   portId: $lldpRemEntry->portId,
                 portDesc: $lldpRemEntry->portDesc,
                  sysName: $lldpRemEntry->sysName,
                  sysDesc: $lldpRemEntry->sysDesc,
             capSupported: SnmpHelper::formatLldpCapabilities( $lldpRemEntry->capSupported ),
               capEnabled: SnmpHelper::formatLldpCapabilities( $lldpRemEntry->capEnabled ),
        );
    }



/* FORMAT MAC TABLES
----------------------------------------------------------------------------- */

    /**
     * @param MacEntry[] $macTable Unformatted MAC table.
     * @return FormattedMacEntry[] Formatted MAC table.
     */
    public static function MacTable( array $macTable ) : array
    {
        $output = [];
        foreach( $macTable as $macEntry )
        {
            $output[] = self::MacEntry( macEntry: $macEntry );
        }

        return $output;
    }



/* FORMAT MAC ENTRY
----------------------------------------------------------------------------- */

    /**
     * @param MacEntry $macEntry Unformatted MAC entry.
     * @return FormattedMacEntry Formatted MAC entry.
     */
    public static function MacEntry( MacEntry $macEntry ) : FormattedMacEntry
    {
        return new FormattedMacEntry(
                  mac: SnmpHelper::formatMacAddress( $macEntry->mac ),
               bridge: $macEntry->bridge,
               status: SnmpHelper::formatMacStatus( $macEntry->status ),
            interface: $macEntry->interface
        );
    }



/* FORMAT IP FORWARD TABLE
----------------------------------------------------------------------------- */

    /**
     * @param IpForwardEntry[] $ipForwardTable Unformatted table.
     * @return FormattedIpForwardEntry[] Formatted table.
     */
    public static function IpForwardTable( array $ipForwardTable ) : array
    {
        $output = [];
        foreach( $ipForwardTable as $ipForwardEntry )
        {
            $output[] = self::IpForwardEntry( ipForwardEntry: $ipForwardEntry );
        }

        return $output;
    }



/* CONVERT IP FORWARD ROW TO FORMATTED VERSION
----------------------------------------------------------------------------- */

    /**
     * @param IpForwardEntry $ipForwardEntry Unformatted row.
     * @return FormattedIpForwardEntry Formatted row.
     */
    public static function IpForwardEntry(
        IpForwardEntry $ipForwardEntry
    ) : FormattedIpForwardEntry
    {
        return new FormattedIpForwardEntry(
            destination: $ipForwardEntry->destination,
                   mask: $ipForwardEntry->mask,
                 policy: $ipForwardEntry->policy,
                nextHop: $ipForwardEntry->nextHop,
              interface: $ipForwardEntry->interface,
                   type: SnmpHelper::formatIpForwardType( $ipForwardEntry->type ),
               protocol: SnmpHelper::formatIpForwardProto( $ipForwardEntry->protocol ),
                    age: $ipForwardEntry->age,
                   info: $ipForwardEntry->info,
              nextHopAs: $ipForwardEntry->nextHopAs,
                metric1: $ipForwardEntry->metric1,
                metric2: $ipForwardEntry->metric2,
                metric3: $ipForwardEntry->metric3,
                metric4: $ipForwardEntry->metric4,
                metric5: $ipForwardEntry->metric5,
                 status: SnmpHelper::formatRowStatus( $ipForwardEntry->status ),
        );
    }



/* CONVERT STP PORT TABLE TO FORMATTED VERSION
----------------------------------------------------------------------------- */

    /**
     * @param StpPortEntry[] $stpPortTable Unformatted table.
     * @return FormattedStpPortEntry[] Formatted table.
     */
    public static function StpPortTable( array $stpPortTable ) : array
    {
        $output = [];
        foreach( $stpPortTable as $stpPortEntry )
        {
            $output[] = self::StpPortEntry( stpPortEntry: $stpPortEntry );
        }

        return $output;
    }



/* CONVERT STP PORT ROW TO FORMATTED VERSION
----------------------------------------------------------------------------- */

    /**
     * @param StpPortEntry $stpPortEntry Unformatted table row.
     * @return FormattedStpPortEntry Formatted table row.
     */
    public static function StpPortEntry( StpPortEntry $stpPortEntry ) : FormattedStpPortEntry
    {
        return new FormattedStpPortEntry(
               bridge: $stpPortEntry->bridge,
             priority: $stpPortEntry->priority,
                state: SnmpHelper::formatStpPortState( $stpPortEntry->state ),
               enable: SnmpHelper::formatStpPortEnable( $stpPortEntry->enable ),
             pathCost: $stpPortEntry->pathCost,
              desRoot: SnmpHelper::formatBridgeId( $stpPortEntry->desRoot ),
              desCost: $stpPortEntry->desCost,
            desBridge: SnmpHelper::formatBridgeId( $stpPortEntry->desBridge ),
             desPort: SnmpHelper::formatStpPortId( $stpPortEntry->desPort ),
        );
    }
}