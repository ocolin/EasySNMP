<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class IfXTable
{
    /**
     * @param int $index Interface index ID.
     * @param ?string $name Interface name.
     * @param ?int $inMcast 32bit Multicast inbound packets.
     * @param ?int $outMcast 32bit Multicast outbound packets.
     * @param ?int $inBcast 32bit Broadcast inbound packets.
     * @param ?int $outBcast 32bit Broadcast outbound packets.
     * @param ?int $inHcOctets 64bit input byte counter, use
     * instead of inOctets on fast interfaces.
     * @param ?int $inHcUcast 64bit Ucast inbound packets.
     * @param ?int $inHcMcast 64bit Multicast inbound packets.
     * @param ?int $inHcBcast 64bit Broadcast inbound packets.
     * @param ?int $outHcOctets 64-bit output byte counter, use
     * instead of outOctets on fast interfaces.
     * @param ?int $outHcUcast 64bit Ucast outbound packets.
     * @param ?int $outHcMcast 64bit Multicast outbound packets.
     * @param ?int $outHcBcast 64bit Broadcast output packets.
     * @param ?int $highSpeed Interface speed in Mbps, use instead
     * of speed for high speed interfaces.
     * @param ?string $alias Interface alias/description set by
     * administrator
     */
    public function __construct(
        public     int $index,
        public ?string $name        = null,
        public    ?int $inMcast     = null,
        public    ?int $inBcast     = null,
        public    ?int $outMcast    = null,
        public    ?int $outBcast    = null,
        public    ?int $inHcOctets  = null,
        public    ?int $inHcUcast   = null,
        public    ?int $inHcMcast   = null,
        public    ?int $inHcBcast   = null,
        public    ?int $outHcOctets = null,
        public    ?int $outHcUcast  = null,
        public    ?int $outHcMcast  = null,
        public    ?int $outHcBcast  = null,
        public    ?int $highSpeed   = null,
        public ?string $alias       = null,
    ) {}
}