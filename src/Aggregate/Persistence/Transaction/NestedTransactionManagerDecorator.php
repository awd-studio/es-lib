<?php

declare(strict_types=1);

namespace AwdEs\Aggregate\Persistence\Transaction;

final class NestedTransactionManagerDecorator implements TransactionManager
{
    private int $level = 0;

    public function __construct(private readonly TransactionManager $manager) {}

    #[\Override]
    public function begin(): void
    {
        if (0 === $this->level) {
            $this->manager->begin();
        }

        ++$this->level;
    }

    #[\Override]
    public function commit(): void
    {
        --$this->level;

        if (0 === $this->level) {
            $this->manager->commit();
        }
    }

    #[\Override]
    public function rollback(): void
    {
        $this->level = 0;
        $this->manager->rollback();
    }
}
