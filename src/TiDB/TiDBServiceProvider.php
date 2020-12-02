<?php

namespace TiDB;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\MySqlConnector;
use TiDB\Connection\TiDBConnection;

class TiDBServiceProvider extends ServiceProvider
{
    /**
     * Create a new service TiDB provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */

    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Register TiDB Serivce Provider to support auto-discover.
     *
     * @return void
     */
    public function register()
    {
        Connection::resolverFor('tidb', function ($connection, $database, $prefix, $config) {
            $connector = new MySqlConnector();
            $connection = $connector->connect($config);
            return new TiDBConnection($connection, $database, $prefix, $config);
        });
    }
}
