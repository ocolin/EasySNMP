<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

class ArpEntry
{
    /**
     * @param ?int $interface Interface ID.
     * @param ?string $mac Raw MAC address.
     * @param ?string $ipAddress IP address.
     * @param ?int $type ARP type numeric value.
     */
    public function __construct(
        public    ?int $interface = null,
        public ?string $mac       = null,
        public ?string $ipAddress = null,
        public    ?int $type      = null,
    ) {}
}