<?php namespace GPopoteur\Flat;

use GPopoteur\Flat\Contract\Flat as FlatInterface;
use GPopoteur\Flat\Exceptions\FlatAlreadyExistsException;
use GPopoteur\Flat\Exceptions\FlatReservedException;
use GPopoteur\Flat\Schemas\Schema;

class Flat implements FlatInterface
{
    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var array
     */
    private $reserved = ['public'];

    /**
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function migrate($flats = [])
    {
        foreach ($flats as $flat) {
            if($this->schema->switchSchema($flat)){
                $this->schema->migrate();
            }
        }
    }

    public function moveIn($flat)
    {
        if($this->exists($flat)) {
            return $this->schema->switchSchema($flat);
        }

        return false;
    }

    public function build($flat)
    {
        // validate is not prohibited
        if(in_array($flat, $this->reserved)){
            return new FlatReservedException();
        }

        // validate it doesn't exists
        if($this->exists($flat)){
            throw new FlatAlreadyExistsException();
        }

        // create the Flat
        return $this->schema->create($flat);
    }

    public function exists($flat)
    {
        return $this->schema->exists($flat);
    }
}