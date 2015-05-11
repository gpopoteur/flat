<?php namespace GPopoteur\Flat\Schemas;

class PostgresSchema extends Schema
{
    /**
     * Determines if the Schema has a specific table
     *
     * @param $tableName
     * @return mixed
     */
    protected function hasTable($tableName)
    {
        try {
            $count = count(
                $this->db->select('SELECT * FROM information_schema.tables WHERE table_schema = ? and table_name = ?',
                    [$this->currentSchema, $tableName])
            );

            return $count > 0;
        } catch (Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    /**
     * Determines if the Schema exists
     *
     * @param $schema
     * @return mixed
     */
    public function exists($schema)
    {
        try {

            $count = count(
                $this->db->select("SELECT schema_name FROM information_schema.schemata WHERE schema_name = '{$schema}';")
            );

            return $count > 0;
        } catch (Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function switchSchema($name = 'public')
    {
        $this->currentSchema = $name;
        return $this->db->statement("SET search_path TO {$name}");
    }

    /**
     * @param $name
     * @return mixed
     */
    public function drop($name)
    {
        return $this->db->statement("DROP SCHEMA {$name}");
    }

    /**
     * @param $name
     * @return mixed
     */
    public function create($name)
    {
        return $this->db->statement("CREATE SCHEMA {$name}");
    }
}