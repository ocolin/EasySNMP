<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class LldpRemTable
{
    /**
     * @param ?int $localPort Local port index neighbor was discovered on.
     * Matches ifIndex in ifTable.
     * @param ?string $chassisIdType Chassis ID subtype.
     * @param ?string $chassisId Chassis identifier.
     * @param ?string $portIdType Port ID subtype integer value.
     * Use SnmpHelper::formatPortIdSubtype().
     * @param ?string $portId Remote port identifier. Format depends
     * on portIdType.
     * @param ?string $portDesc Remote port description.
     * @param ?string $sysName Remote system name.
     * @param ?string $sysDesc Remote system description.
     * @param ?string $capSupported Supported capabilities.
     * @param ?string $capEnabled Enabled capabilities.
     */
    public function __construct(
        public    ?int $localPort      = null,
        public ?string $chassisIdType  = null,
        public ?string $chassisId      = null,
        public ?string $portIdType     = null,
        public ?string $portId         = null,
        public ?string $portDesc       = null,
        public ?string $sysName        = null,
        public ?string $sysDesc        = null,
        public ?string $capSupported   = null,
        public ?string $capEnabled     = null,
    ) {}
}