# EasySNMP

## About

A bare-bones basic SNMP client. It can be configured either by usein constructor arguments, or using environment variables.

## Constructor Arguments

- $ip - IP address of device to query.
- $community - Community string of device to query.
- $version - SNMP version of device. Defaults to 2. 3 is not yet supported.
- $prefix - Environment variable prefix. Used when you may be using multiple devices with different environment variable.

## Environment Variables

### Default Variables 

- SNMP_IP - IP address of device to query.
- SNMP_COMMUNITY - Community string of device to query.
- SNMP_VERSION - SNMP version of device. Defaults to 2. 3 is not yet supported.

### Prefixed Variables

Example with the prefix value 'PREFIX':

- PREFIX_SNMP_IP - IP address of device to query.
- PREFIX_SNMP_COMMUNITY - Community string of device to query.
- PREFIX_SNMP_VERSION - SNMP version of device. Defaults to 2. 3 is not yet supported.

## Examples

### Basic Constructor Arguments

```php
$snmp = new \Ocolin\EasySNMP\SNMP(
           ip: '192.168.1.1',
    community: 'public',
      version: 2
);
```

### Environment Constructor
```php
$_ENV['SNMP_IP'] = '192.168.1.1';
$_ENV['SNMP_COMMUNITY'] = 'public';
$_ENV['SNMP_VERSION'] = 2;

$snmp = new \Ocolin\EasySNMP\SNMP();
```

### Environment Prefix Constructor
```php
$_ENV['MY_SNMP_IP'] = '192.168.1.1';
$_ENV['MY_SNMP_COMMUNITY'] = 'public';
$_ENV['MY_SNMP_VERSION'] = 2;

$snmp = new \Ocolin\EasySNMP\SNMP( prefix: 'MY_' );
```

### SNMP Get
```php
$output = $snmp->get(
        oid: '.1.3.6.1.2.1.1.1.0',
    numeric: false
);
```

### SNMP Get Next
```php
$output = $snmp->getnext(
        oid: '.1.3.6.1.2.1.1.1.0',
);
```

### SNMP Walk
```php
$output = $snmp->walk();
```

### SNMP Bulk Walk
```php
$output = $snmp->walk(
              oid: '.1.3.6.1.2.1.1.1.0',
             bulk: true,
        enumerate: true
);
```