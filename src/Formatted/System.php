<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP\Formatted;

readonly class System
{

    /**
     * @param ?string $descr Description of device.
     * @param ?string $upTime System uptime in human-readable form..
     * @param ?string $contact Contact information.
     * @param ?string $name Name of device.
     * @param ?string $location Location of device.
     * @param ?string $oid Device OID.
     */
    public function __construct(
        public ?string $descr = null,
        public ?string $upTime = null,
        public ?string $contact = null,
        public ?string $name = null,
        public ?string $location = null,
        public ?string $oid = null,
    ) {}
}