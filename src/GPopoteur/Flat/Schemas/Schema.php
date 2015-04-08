<?php namespace GPopoteur\Flat\Schemas;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;

abstract class Schema
{
    /**
     * @var Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * @var Artisan
     */
    private $artisan;

    /**
     * @var Current Schema
     */
    protected $currentSchema;

    public function __construct(DatabaseManager $db, Kernel $artisan)
    {
        $this->db = $db;
        $this->artisan = $artisan;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function create($name)
    {
        return $this->db->statement("CREATE SCHEMA {$name}");
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
     * @param array $args
     * @return boolean
     */
    public function migrate($args = [])
    {
        if (!$this->hasTable('migrations')) {
            $this->artisan->call('migrate:install');
        }
        return $this->artisan->call('migrate', $args);
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function refresh($args = [])
    {
        return $this->artisan->call('migrate:refresh', $args);
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function reset($args = [])
    {
        return $this->artisan->call('migrate:reset', $args);
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function rollback($args = [])
    {
        return $this->artisan->call('migrate:rollback', $args);
    }

    /**
     * Determines if the Schema has a specific table
     *
     * @param $tableName
     * @return mixed
     */
    abstract protected function hasTable($tableName);

    /**
     * Determines if the Schema exists
     *
     * @param $flat
     * @return mixed
     */
    abstract public function exists($schema);

}