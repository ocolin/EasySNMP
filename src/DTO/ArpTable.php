<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

class ArpTable
{
    public function __construct(
        public    ?int $interface = null,
        public ?string $mac       = null,
        public ?string $ipAddress = null,
        public    ?int $type      = null,
    ) {}
}