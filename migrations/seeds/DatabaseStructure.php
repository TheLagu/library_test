<?php

use Phinx\Seed\AbstractSeed;

class DatabaseStructure extends AbstractSeed
{
    public function run(): void
    {
        $this->execute(file_get_contents(__DIR__ . '/../sql/structure.sql'));
    }
}