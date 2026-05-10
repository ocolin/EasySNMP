<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class MacEntry
{
    /**
     * @param ?string $mac MAC address formatted
     * @param ?int $bridge Bridge ID.
     * @param ?string $status MAC status formatted.
     * @param ?int $interface Interface ID.
     * Null indicates the MAC is associated with the bridge
     */
    public function __construct(
        public ?string $mac    = null,
        public    ?int $bridge = null,
        public ?string $status = null,
        public    ?int $interface = null
    ) {}
}