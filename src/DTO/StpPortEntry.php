<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class StpPortEntry
{

    /**
     * @param ?int $bridge Bridge port number. Matches $bridge in MacTable.
     * @param ?int $priority Port priority (0-255). Default is 128.
     * @param ?int $state STP port state integer value.
     * Use SnmpHelper::formatStpPortState().
     * @param ?int $enable Port enabled status.
     * Use SnmpHelper::formatStpPortEnable().
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
        public    ?int $state     = null,
        public    ?int $enable    = null,
        public    ?int $pathCost  = null,
        public ?string $desRoot   = null,
        public    ?int $desCost   = null,
        public ?string $desBridge = null,
        public ?string $desPort   = null,
    ) {}
}