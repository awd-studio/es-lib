<?php

declare(strict_types=1);

namespace AwdEs\Entity\Persistence\Transaction;

interface TransactionManager
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
