<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class IpAddrTable
{
    /**
     * @param ?string $address IP address.
     * @param ?int $interface Interface index ID.
     * @param ?string $netmask Subnet mask.
     * @param ?int $bcast Broadcast address bit.
     * @param ?int $reasmMaxSize Max datagram size for reassembly
     */
    public function __construct(
        public ?string $address      = null,
        public ?int    $interface    = null,
        public ?string $netmask      = null,
        public ?int    $bcast        = null,
        public ?int    $reasmMaxSize = null
    ) {}
}