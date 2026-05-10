<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class StpPortEntry
{

    /**
     * @param ?int $bridge Bridge port number. Matches $bridge in MacTable.
     * @param ?int $priority Port priority (0-255). Default is 128.
     * @param ?string $state STP port state label.
     * @param ?string $enable Port enabled status.
     * @param ?int $pathCost STP path cost for this port.
     * @param ?string $desRoot Designated root bridge ID (8 bytes).
     * Use SnmpHelper::formatBridgeId().
     * @param ?int $desCost Designated path cost to root bridge.
     * @param ?string $desBridge Designated bridge ID (8 bytes).
     * Use SnmpHelper::formatBridgeId().
     * @param ?string $desPort Designated port ID (2 bytes).
     * Use SnmpHelper::formatStpPortId().
     */

    public function __construct(
        public    ?int $bridge    = null,
        public    ?int $priority  = null,
        public ?string $state     = null,
        public ?string $enable    = null,
        public    ?int $pathCost  = null,
        public ?string $desRoot   = null,
        public    ?int $desCost   = null,
        public ?string $desBridge = null,
        public ?string $desPort   = null,
    ) {}
}