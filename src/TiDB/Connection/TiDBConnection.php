<?php
namespace TiDB\Connection;

use Illuminate\Database\MySqlConnection;
use TiDB\Grammars\QueryGrammar;
use TiDB\Grammars\SchemaGrammar;

class TiDBConnection extends MySqlConnection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \TiDB\Grammars\QueryGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \TiDB\Grammars\SchemaGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }


    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public function statement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            if ($this->pretending()) {
                return true;
            }

            /** if no bindings, just run it. */
            if (!$bindings) {
                return $this->getPdo()->exec($query);
            }

            $statement = $this->getPdo()->prepare($query);
            $this->bindValues($statement, $this->prepareBindings($bindings));
            $this->recordsHaveBeenModified();

            return $statement->execute();
        });
    }
}
