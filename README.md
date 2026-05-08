![Packagist Version](https://img.shields.io/packagist/v/ocolin/easysnmp)
![PHP Version](https://img.shields.io/packagist/dependency-v/ocolin/easysnmp/php)
![License](https://img.shields.io/packagist/l/ocolin/easysnmp)
![Downloads](https://img.shields.io/packagist/dt/ocolin/easysnmp)

# ocolin/easysnmp

A PHP SNMP client that provides formatted, DTO-backed access to standard MIB-II
data without requiring SNMP knowledge. Pass in your authentication credentials,
call a method, get structured data back.

Built on top of [FreeDSx SNMP](https://github.com/FreeDSx/SNMP).

---

## Requirements

- PHP 8.4+
- [FreeDSx SNMP](https://github.com/FreeDSx/SNMP) `^0.5.1`
- [ocolin/global-type](https://packagist.org/packages/ocolin/global-type) `^2.0`

---

## Installation

```bash
composer require ocolin/easysnmp
```

---

## Configuration

Configuration is handled by the `Config` class. Credentials can be passed
explicitly or read automatically from environment variables.

### Explicit configuration

```php
use Ocolin\EasySNMP\Config;
use Ocolin\EasySNMP\EasySNMP;

$config = new Config(
    host:      '10.0.0.1',
    community: 'public'
);

$snmp = new EasySNMP( config: $config );
```

### Environment variables

If no explicit values are passed, `Config` falls back to environment variables
using the `EASY_SNMP_` prefix by default:

```env
EASY_SNMP_HOST=10.0.0.1
EASY_SNMP_COMMUNITY=public
EASY_SNMP_PORT=161
EASY_SNMP_VERSION=2
EASY_SNMP_TIMEOUT_CONNECT=5
EASY_SNMP_TIMEOUT_READ=10
EASY_SNMP_USER=
EASY_SNMP_AUTH_PWD=
EASY_SNMP_PRIV_PWD=
EASY_SNMP_PRIV_MECH=
EASY_SNMP_AUTH_MECH=
EASY_SNMP_ENGINE_ID=
EASY_SNMP_CONTEXT_NAME=
EASY_SNMP_USE_AUTH=
EASY_SNMP_USE_PRIV=
```

```php
// Reads from EASY_SNMP_* environment variables
$snmp = new EasySNMP( config: new Config() );
// Or alternatively
$snmp = new EasySNMP();
```

### Multiple devices with prefixes

When monitoring multiple devices, use the `$prefix` parameter to namespace
your environment variables:

```env
JUNIPER_SNMP_HOST=10.0.0.1
JUNIPER_SNMP_COMMUNITY=public

MIKROTIK_SNMP_HOST=10.0.0.2
MIKROTIK_SNMP_COMMUNITY=private
```

```php
$juniper  = new EasySNMP( config: new Config( prefix: 'JUNIPER' ) );
$mikrotik = new EasySNMP( config: new Config( prefix: 'MIKROTIK' ) );
```

Prefix values are case-insensitive — `'juniper'` and `'JUNIPER'` both resolve
to `JUNIPER_SNMP_*`.

---

## Usage

### System information

```php
$system = $snmp->getSystem();

echo $system->name;      // Router-01
echo $system->descr;     // Linux Router 5.15.0
echo $system->location;  // Server Room A
echo $system->contact;   // admin@example.com
echo $system->upTime;    // TimeTicks integer
echo $system->objectId;  // 1.3.6.1.4.1.14988.1
```

### Interface table (ifTable)

```php
$interfaces = $snmp->getIfTable();

foreach( $interfaces as $interface ) {
    echo $interface->index;        // Interface index
    echo $interface->description;  // Interface description
    echo $interface->speed;        // Speed in bps
    echo $interface->adminStatus;  // Raw integer (1=up, 2=down, 3=testing)
    echo $interface->operStatus;   // Raw integer (1=up, 2=down, etc.)
    echo $interface->macAddress;   // Raw MAC address
}
```

Fetch only specific columns to reduce SNMP calls:

```php
$interfaces = $snmp->getIfTable( columns: ['description', 'operStatus'] );
```

### Extended interface table (ifXTable)

```php
$interfaces = $snmp->getIfXTable();

foreach( $interfaces as $interface ) {
    echo $interface->name;        // Interface name
    echo $interface->alias;       // Administrator-set alias
    echo $interface->highSpeed;   // Speed in Mbps
    echo $interface->inHcOctets;  // 64-bit input byte counter
    echo $interface->outHcOctets; // 64-bit output byte counter
}
```

### ARP table

```php
$arp = $snmp->getArpTable();

foreach( $arp as $entry ) {
    echo $entry->interface;  // Interface index
    echo $entry->ipAddress;  // IP address
    echo $entry->mac;        // Raw MAC address
    echo $entry->type;       // Raw integer (1=other, 2=invalid, 3=dynamic, 4=static)
}
```

---

## Formatting helpers

Raw integer values can be converted to human-readable labels using `SnmpHelper`:

```php
use Ocolin\EasySNMP\SnmpHelper;

// Interface status
SnmpHelper::formatOperStatus( $interface->operStatus );
// "up", "down", "testing", "unknown", "dormant", "notPresent", "lowerLayerDown"

SnmpHelper::formatAdminStatus( $interface->adminStatus );
// "up", "down", "testing"

// Interface type
SnmpHelper::formatIfType( $interface->type );
// "ethernet", "softwareLoopback", "tunnel", "ieee8023adLag", etc.

// MAC address formatting
SnmpHelper::formatMacAddress( $interface->macAddress );
// "00:11:22:33:44:55"

SnmpHelper::formatMacAddress( $entry->mac );
// "00:11:22:33:44:55"

// ARP entry type
SnmpHelper::formatArpType( $entry->type );
// "other", "invalid", "dynamic", "static"
```

All helper methods accept `null` and return `null`, so they are safe to call
directly on nullable DTO properties.

---

## Extending for vendor-specific devices

`EasySNMP` is designed to be extended. Vendor-specific functionality can be
added by extending the base class and adding traits for proprietary OID trees:

```php
namespace Ocolin\Hyconext;

use Ocolin\EasySNMP\EasySNMP;
use Ocolin\Hyconext\Traits\PoeTrait;

class HyconextSNMP extends EasySNMP
{
    use PoeTrait;
}
```

The extending class inherits all standard MIB-II methods and gains access to
`bulkWalk()`, `getColumn()`, and `getCompositeColumn()` for building
vendor-specific trait methods.

---

## PHP 8.4 compatibility

FreeDSx SNMP was written for PHP 7.4 and contains a nullable type deprecation
that triggers on PHP 8.4. This is handled internally within `EasySNMP` and does
not affect consuming code. A fix has been submitted upstream via pull request.

---

## Property Lists

### IfTable properties

| Property | Type | Description |
|----------|------|-------------|
| `$index` | `int` | Interface index |
| `$description` | `?string` | Interface description string |
| `$type` | `?int` | IANA interface type. Use `SnmpHelper::formatIfType()` |
| `$mtu` | `?int` | Maximum transmission unit in bytes |
| `$speed` | `?int` | Interface speed in bits per second |
| `$macAddress` | `?string` | Raw MAC address. Use `SnmpHelper::formatMacAddress()` |
| `$adminStatus` | `?int` | Administrative status. Use `SnmpHelper::formatAdminStatus()` |
| `$operStatus` | `?int` | Operational status. Use `SnmpHelper::formatOperStatus()` |
| `$lastChange` | `?int` | Time of last status change in TimeTicks |
| `$inOctets` | `?int` | 32-bit input byte counter |
| `$outOctets` | `?int` | 32-bit output byte counter |
| `$inErrors` | `?int` | Input error counter |
| `$outErrors` | `?int` | Output error counter |

### IfXTable properties

| Property | Type | Description |
|----------|------|-------------|
| `$index` | `int` | Interface index — matches `IfTable` index |
| `$name` | `?string` | Interface name |
| `$inMcast` | `?int` | 32-bit multicast inbound packets |
| `$outMcast` | `?int` | 32-bit multicast outbound packets |
| `$inBcast` | `?int` | 32-bit broadcast inbound packets |
| `$outBcast` | `?int` | 32-bit broadcast outbound packets |
| `$inHcOctets` | `?int` | 64-bit input byte counter, use instead of `inOctets` on fast interfaces |
| `$inHcUcast` | `?int` | 64-bit unicast inbound packets |
| `$inHcMcast` | `?int` | 64-bit multicast inbound packets |
| `$inHcBcast` | `?int` | 64-bit broadcast inbound packets |
| `$outHcOctets` | `?int` | 64-bit output byte counter, use instead of `outOctets` on fast interfaces |
| `$outHcUcast` | `?int` | 64-bit unicast outbound packets |
| `$outHcMcast` | `?int` | 64-bit multicast outbound packets |
| `$outHcBcast` | `?int` | 64-bit broadcast outbound packets |
| `$highSpeed` | `?int` | Speed in Mbps, use instead of `speed` on fast interfaces |
| `$alias` | `?string` | Administrator-set interface alias |

### System properties

| Property | Type | Description |
|----------|------|-------------|
| `$descr` | `?string` | Full description of the device |
| `$upTime` | `?int` | System uptime in TimeTicks |
| `$contact` | `?string` | Contact information |
| `$name` | `?string` | Device name |
| `$location` | `?string` | Physical location |
| `$objectId` | `?string` | Vendor enterprise OID |

### ArpTable properties

| Property | Type | Description |
|----------|------|-------------|
| `$interface` | `?int` | Interface index — matches `IfTable` index |
| `$mac` | `?string` | Raw MAC address. Use `SnmpHelper::formatMacAddress()` |
| `$ipAddress` | `?string` | IP address of ARP entry |
| `$type` | `?int` | Entry type. Use `SnmpHelper::formatArpType()` |

---

## License

MIT