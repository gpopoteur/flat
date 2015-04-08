<?php namespace GPopoteur\Flat\Schemas;

class PostgresSchema extends Schema
{

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
}