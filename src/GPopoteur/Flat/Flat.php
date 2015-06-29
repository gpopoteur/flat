<?php 

namespace GPopoteur\Flat;

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

    /**
     * Run the migrations on the specified array of flats
     * @param  array $flats Array of flat names
     */
    public function migrate($flats = [])
    {
        foreach ($flats as $flat) {
            if ($this->schema->switchSchema($flat)) {
                $this->schema->migrate();
            }
        }
    }

    /**
     * Changes to the tenant Schema or Database
     * @param  string $flat Flat name
     * @return boolean      boolean with the success of the change
     */
    public function moveIn($flat)
    {
        if ($this->exists($flat)) {
            return $this->schema->switchSchema($flat);
        }

        return false;
    }

    /**
     * Creates a new Schema or Database
     * @param  string $flat Flat name
     * @return mixed
     */
    public function build($flat)
    {
        // validate is not prohibited
        if (in_array($flat, $this->reserved)) {
            return new FlatReservedException();
        }

        // validate it doesn't exists
        if ($this->exists($flat)) {
            throw new FlatAlreadyExistsException();
        }

        // create the Flat
        return $this->schema->create($flat);
    }

    /**
     * Checks if the Schema or Database already exists
     * @param  string $flat Flat name
     * @return boolean      true if schema name already exists, false if not
     */
    public function exists($flat)
    {
        return $this->schema->exists($flat);
    }
}
