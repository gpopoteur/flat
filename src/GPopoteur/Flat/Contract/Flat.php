<?php namespace GPopoteur\Flat\Contract;

interface Flat
{
    public function migrate($flats = []);

    public function moveIn($flat);

    public function build($flat);

    public function exists($flat);
}