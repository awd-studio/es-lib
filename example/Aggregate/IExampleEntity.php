<?php

declare(strict_types=1);

namespace Example\Aggregate;

use Awd\ValueObject\IDateTime;

interface IExampleEntity
{
    public function change(IDateTime $modifiedAt): void;
}
