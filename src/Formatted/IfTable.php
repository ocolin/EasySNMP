<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class IfTable
{
    /**
     * @param int $index Interface index ID.
     * @param ?string $description Interface description string.
     * @param ?string $type Formatted IF type.
     * @param ?int $mtu maximum transmission unit in bytes.
     * @param ?string $speed Interface speed in bits per second (bps).
     * @param ?string $macAddress Raw SNMP MAC address value.
     * @param ?string $adminStatus Administrative status (up/down/testing).
     * @param ?string $operStatus Operational status
     * (up/down/testing/unknown/dormant/notPresent/lowerLayerDown).
     * @param ?int $lastChange Time of last status change in hundredths
     * of a second (TimeTicks).
     * @param ?int $inOctets 32-bit input byte counter, wraps at ~4GB.
     * @param ?int $outOctets 32-bit output byte counter, wraps at ~4GB.
     * @param ?int $inErrors Input error counter.
     * @param ?int $outErrors Output error counter.
     */
    public function __construct(
        public     int $index,
        public ?string $description = null,
        public ?string $type        = null,
        public    ?int $mtu         = null,
        public ?string $speed       = null,
        public ?string $macAddress  = null,
        public ?string $adminStatus = null,
        public ?string $operStatus  = null,
        public    ?int $lastChange  = null,
        public    ?int $inOctets    = null,
        public    ?int $outOctets   = null,
        public    ?int $inErrors    = null,
        public    ?int $outErrors   = null,
    ) {}
}