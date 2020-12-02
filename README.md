# Laravel TiDB

TiDB is a distributed database that supports MySQL Protocol. It already works with most of mysql-compatible libs/tools. 
However it does not support some MySQL features like foreign keys, xml and json queries etc. 

The goal is to change queries on the fly to make them compatible with TiDB before hitting to database.

> it is currently under development and it just supports DDLs (so it works well for your migrations) 
> 
> if you are using TiDB with laravel in production and you already faced some of them and fixed, awesome, just open a PR!

## Installa using Composer
```
composer require tufanbarisyildirim/laravel-tidb
```

## What you need to change for better support
-- Todo: explain how should a developer write migrations and rollbacks.

## Current Progress
This library aims to provide some workarounds to keep your laravel app working with TiDB. But a single developer can't guarantee to get the all done. 
I will solve every situation I face in my own projects.

### DDL Incompatibility.
In TiDB, all supported DDL changes are performed online. Compared with DDL operations in MySQL, the DDL operations in TiDB have the following major restrictions:

- [x] Multiple operations cannot be completed in a single ALTER TABLE statement. For example, it is not possible to add multiple columns or indexes in a single statement. Otherwise, the Unsupported multi schema change error might be output.
- [wontfix] Different types of indexes `(HASH|BTREE|RTREE|FULLTEXT)` are not supported, and will be parsed and ignored when specified.
- [wontfix] Adding/Dropping the primary key is unsupported unless alter-primary-key is enabled.
- [ ] Changing the field type to its superset is unsupported. For example, TiDB does not support changing the field type from `INTEGER` to `VARCHAR`, or from `TIMESTAMP` to DATETIME. Otherwise, the error information Unsupported modify column: type `%d` not match origin `%d `might be output.
- [ ] Change/Modify data type does not currently support "lossy changes", such as changing from `BIGINT` to `INT`.
- [ ] Change/Modify decimal columns does not support changing the precision.
- [ ] Change/Modify integer columns does not permit changing the UNSIGNED attribute.
- [ ] The `ALGORITHM={INSTANT,INPLACE,COPY}` syntax functions only as an assertion in TiDB, and does not modify the ALTER algorithm. See ALTER TABLE for further details.
- [wontfix] Table Partitioning supports Hash, Range, and Add/Drop/Truncate/Coalesce. The other partition operations are ignored. The Warning: Unsupported partition type, treat as normal table error might be output. The following Table Partition syntaxes are not supported:
- [ ] PARTITION BY LIST
- [ ] PARTITION BY KEY
- [ ] SUBPARTITION
- [ ] {`CHECK|EXCHANGE|TRUNCATE|OPTIMIZE|REPAIR|IMPORT|DISCARD|REBUILD|REORGANIZE`} PARTITION



### Limitations of SELECT syntax
- [ ] The SELECT ... INTO @variable syntax is not supported.
- [ ] The SELECT ... GROUP BY ... WITH ROLLUP syntax is not supported.




# Some more unsupported features

[TiDB Mysql Compatibility](https://docs.pingcap.com/tidb/v3.0/mysql-compatibility)

This lib does not solve the following unsupported features since TiDB does not support yet. 

- Stored procedures and functions
- Triggers
- Events
- User-defined functions
- FOREIGN KEY constraints #18209
- Temporary tables #1248
- FULLTEXT/SPATIAL functions and indexes #1793
- Character sets other than utf8, utf8mb4, ascii, latin1 and binary
- SYS schema
- Optimizer trace
- XML Functions
- X-Protocol #1109
- Savepoints #6840
- Column-level privileges #9766
- XA syntax (TiDB uses a two-phase commit internally, but this is not exposed via an SQL interface)
- CREATE TABLE tblName AS SELECT stmt syntax #4754
- CHECK TABLE syntax #4673
- CHECKSUM TABLE syntax #1895
- GET_LOCK and RELEASE_LOCK functions #14994