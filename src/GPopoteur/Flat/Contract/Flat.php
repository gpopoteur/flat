<?php 

namespace GPopoteur\Flat\Contract;

interface Flat
{
    /**
     * Run the migrations on the specified array of flats
     * @param  array $flats Array of flat names
     */
    public function migrate($flats = []);

    /**
     * Changes to the tenant Schema or Database
     * @param  string $flat Flat name
     * @return boolean      boolean with the success of the change
     */
    public function moveIn($flat);

    /**
     * Creates a new Schema or Database
     * @param  string $flat Flat name
     * @return mixed
     * @throws FlatAlreadyExistsException
     */
    public function build($flat);

    /**
     * Checks if the Schema or Database already exists
     * @param  string $flat Flat name
     * @return boolean      true if schema name already exists, false if not
     */
    public function exists($flat);
}
