<?php

declare( strict_types = 1 );

namespace Ocolin\EasySNMP;

class SnmpObject
{
    /**
     * @var string Original SNMP entry row.
     */
    public string $origin;

    /**
     * @var string Row data type.
     */
    public string $type;

    /**
     * @var string|int|float Value of row.
     */
    public string|int|float $value;

    /**
     * @var string OID of row.
     */
    public string $oid;
}