<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class MacTable
{
    /**
     * @param ?string $mac MAC address
     * @param ?int $bridge Bridge ID.
     * @param ?int $status MAC status.
     * @param ?int $interface Interface ID.
     * Null indicates the MAC is associated with the bridge
     */
    public function __construct(
        public ?string $mac    = null,
        public    ?int $bridge = null,
        public    ?int $status = null,
        public    ?int $interface = null
    ) {}
}