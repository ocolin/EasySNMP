<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class LldpRemTable
{
    /**
     * @param ?int $localPort Local port index neighbor was discovered on.
     * Matches ifIndex in ifTable.
     * @param ?int $chassisIdType Chassis ID subtype integer value.
     * Use SnmpHelper::formatChassisIdSubtype().
     * @param ?string $chassisId Chassis identifier. Format depends on
     * chassisIdType. Use SnmpHelper::formatMacAddress() when
     * chassisIdType is 4 (macAddress).
     * @param ?int $portIdType Port ID subtype integer value.
     * Use SnmpHelper::formatPortIdSubtype().
     * @param ?string $portId Remote port identifier. Format depends
     * on portIdType.
     * @param ?string $portDesc Remote port description.
     * @param ?string $sysName Remote system name.
     * @param ?string $sysDesc Remote system description.
     * @param ?string $capSupported Raw capability bitmask of supported
     * capabilities. Use SnmpHelper::formatLldpCapabilities().
     * @param ?string $capEnabled Raw capability bitmask of enabled
     * capabilities. Use SnmpHelper::formatLldpCapabilities().
     */
    public function __construct(
        public    ?int $localPort      = null,
        public    ?int $chassisIdType  = null,
        public ?string $chassisId      = null,
        public    ?int $portIdType     = null,
        public ?string $portId         = null,
        public ?string $portDesc       = null,
        public ?string $sysName        = null,
        public ?string $sysDesc        = null,
        public ?string $capSupported   = null,
        public ?string $capEnabled     = null,
    ) {}
}