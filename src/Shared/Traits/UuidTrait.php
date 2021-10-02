<?php

namespace Library\Shared\Traits;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    public function genUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
