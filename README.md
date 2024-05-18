# EasySNMP

This is a bare bones SNMP client that is very simple to use at the cost of functionality. It was designed for grabbing data from Mikrotik routers. However, that does not mean it is limited to tht use. 

## CREATING SNMP CLIENT

```
$client = new EasySNMP(
    ip: '1.2.3.4',
    version: 2,
    community: 'public'
    local: true
)
```
### Parameters:

- **IP** - IPv4 address of the device
- **Version** - SNMP version int 1 or 2. v3 is not supported. Optional parameter. It will default to version 2 if not specified. Or it will use environment variable **SNMP_COMMUNITY** if it is set and no parameters is given.
- **Community** - SNMP community string. Will default to **public** if not provided. May also use environment variable **SNMP_VERSION** if set and no parameters is given.
- **Local** - Tells client to load environment variables from .env file in root of EasySNMP directory. This is useful if the module is being used as a stand-alone library and not in a Composer project.

### Client using defaults:

```
$client = new EasySNMP( ip: '1.2.3.4' );
```

## SNMP Walk

```
$client->walk(
    oid: '1.1.1.1',
    bulk: true,
    numeric: false
);
```

### Parameters

- **OID** - SNMP OID of tree to walk. If left blank it will use default SNMP tree.
- **Bulk** - Defaults to true. Us snmpbulkwalk instead of snmpwalk command.
- **Numeric** - Defaults to false. Return numerical OID names only.
- RETURN - Array of objects

### Walk using defaults:

```
$client->walk( oid: '1.1.1' );
```

## SNMP Get

```
$client->get(
    oid: '1.2.3',
    numeric: false
);
```

### Parameters

- OID - SNMP OID to query
- Numeric - Defaults to false. Output only numerical OID names.
- RETURN - A data object

### Get using defaults:

``` 
$client->get( oid: '1.2.3' );
```

## Data Object

Depending on the command used, the output will be an array of objects, a single object, or null if nothing is found. This is the object structure.

- **origin** - The original string of the unaltered row
- **type**   - The value type of the row
- **value**  - The data value of the row
- **index**  - Integer representing the index of the row
- **name**   - OID name of the row
