<?php

namespace GPopoteur\Flat\Schemas;


use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Console\Kernel as Artisan;

class SqliteSchema extends Schema
{
    /**
     * Filesystem Manager
     * @var Storage
     */
    private $storage;

    /**
     * Database Config Manager
     * @var $config
     */
    private $config;

    /**
     * Database Path
     */
    private $directoryPath;

    public function __construct(DatabaseManager $db, Artisan $artisan)
    {
        parent::__construct($db, $artisan);

        $this->directoryPath = 'flat-sqlite-db';
    }

    /**
     * Sets the filesystem manager
     *
     * @param $storage
     */
    public function setStorage(Filesystem $storage)
    {
        $this->storage = $storage;

        $this->checkDirectoryExists();
    }

    /**
     * Sets the Configuration Manager
     *
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Gets the database file path
     */
    private function getDatabaseFilePath($file)
    {
        return storage_path($this->directoryPath . '/' . $file . '.sqlite');
    }
    
    /**
     * @param $name
     * @return mixed
     */
    public function create($name)
    {
        $dbCreated = $this->storage->put(
            $this->getDatabaseFilePath($name)
        , '');

        return $dbCreated !== false;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function switchSchema($name = 'public')
    {
        $this->currentSchema = $this->getDatabaseFilePath($name);
        $this->config->set('database.connections.sqlite.database', $this->currentSchema);

        return $this->db->reconnect('sqlite')->getDatabaseName() === $this->currentSchema;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function drop($name)
    {
        return $this->storage->delete(
            $this->getDatabaseFilePath($name)
        );
    }

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
                $this->db->table('sqlite_master')
                    ->where('type', 'table')
                    ->where('name', $tableName)
                    ->get()
            );

            return $count > 0;
        } catch (Exception $e) {
            if(get_class($e) === 'Illuminate\Database\QueryException'){
                return false;
            }
        }
    }

    /**
     * Determines if the Schema exists
     *
     * @param $flat
     * @return bool
     */
    public function exists($flat)
    {
        return $this->storage->exists(
            $this->getDatabaseFilePath($flat)
        );
    }

    /**
     * Checks if the Database Directory exists,
     * if not, directory will be created.
     */
    private function checkDirectoryExists()
    {
        $directory = storage_path($this->directoryPath);
        if( ! $this->storage->exists($directory)){
            $this->storage->makeDirectory($directory);
        }
    }
}