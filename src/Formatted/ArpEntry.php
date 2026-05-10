<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

class ArpEntry
{
    /**
     * @param ?int $interface ID of interface.
     * @param ?string $mac Formatted MAC address.
     * @param ?string $ipAddress IP Address.
     * @param ?string $type Formatted type of ARP.
     */
    public function __construct(
        public    ?int $interface = null,
        public ?string $mac       = null,
        public ?string $ipAddress = null,
        public ?string $type      = null,
    ) {}
}