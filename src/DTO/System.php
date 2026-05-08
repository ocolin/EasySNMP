<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\DTO;

readonly class System
{

    /**
     * @param ?string $descr Description of device.
     * @param ?int $upTime System uptime in ticks.
     * @param ?string $contact Contact information.
     * @param ?string $name Name of device.
     * @param ?string $location Location of device.
     * @param ?string $oid Device OID.
     */
    public function __construct(
        public ?string $descr = null,
        public    ?int $upTime = null,
        public ?string $contact = null,
        public ?string $name = null,
        public ?string $location = null,
        public ?string $oid = null,
    ) {}
}