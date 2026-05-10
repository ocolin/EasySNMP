<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class IpForwardEntry
{

    /**
     * @param ?string $destination Destination network address
     * * @param ?string $mask Destination network mask
     * * @param ?int $policy Policy for this route, typically 0
     * * @param ?string $nextHop Next hop IP address
     * * @param ?int $interface Interface index — matches ifTable index
     * * @param ?int $type Route type. Use SnmpHelper::formatIpForwardType()
     * * @param ?int $protocol Routing protocol. Use SnmpHelper::formatIpForwardProto()
     * * @param ?int $age Seconds since route was last updated
     * * @param ?string $info Routing protocol specific info, rarely populated
     * * @param ?int $nextHopAs Next hop autonomous system number
     * * @param ?int $metric1 Primary routing metric
     * * @param ?int $metric2 Secondary routing metric
     * * @param ?int $metric3 Tertiary routing metric
     * * @param ?int $metric4 Fourth routing metric
     * * @param ?int $metric5 Fifth routing metric
     * * @param ?int $status Row status
 */
    public function __construct(
        public ?string $destination = null,
        public ?string $mask        = null,
        public    ?int $policy      = null,
        public ?string $nextHop     = null,
        public    ?int $interface   = null,
        public    ?int $type        = null,
        public    ?int $protocol    = null,
        public    ?int $age         = null,
        public ?string $info        = null,
        public    ?int $nextHopAs   = null,
        public    ?int $metric1     = null,
        public    ?int $metric2     = null,
        public    ?int $metric3     = null,
        public    ?int $metric4     = null,
        public    ?int $metric5     = null,
        public    ?int $status      = null,
    ){}
}