<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class IfTable
{
    /**
     * @param int $index Interface index ID.
     * @param ?string $description Interface description string.
     * @param ?int $type IANA interface type integer value.
     * see formatIfType().
     * @param ?int $mtu maximum transmission unit in bytes.
     * @param ?int $speed Interface speed in bits per second (bps).
     * @param ?string $macAddress Raw SNMP MAC address value.
     * @param ?int $adminStatus Administrative status (up/down/testing).
     * @param ?int $operStatus Operational status
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
        public    ?int $type        = null,
        public    ?int $mtu         = null,
        public    ?int $speed       = null,
        public ?string $macAddress  = null,
        public    ?int $adminStatus = null,
        public    ?int $operStatus  = null,
        public    ?int $lastChange  = null,
        public    ?int $inOctets    = null,
        public    ?int $outOctets   = null,
        public    ?int $inErrors    = null,
        public    ?int $outErrors   = null,
    ) {}
}