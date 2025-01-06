<?php

declare(strict_types=1);

namespace AwdEs\Event\Entity\Decomposing;

use AwdEs\Entity\Decomposing\EntityDecomposer;
use AwdEs\Entity\Persistence\Transaction\TransactionManager;
use AwdEs\Event\EventEmitter;
use AwdEs\Event\Storage\Recorder\EventRecorder;

final readonly class EventRecorderEntityDecomposer implements EntityDecomposer
{
    public function __construct(
        private TransactionManager $transactions,
        private EventRecorder $recorder,
    ) {}

    #[\Override]
    public function decompose(EventEmitter $eventEmitter): void
    {
        $this->transactions->begin();

        foreach ($eventEmitter->emitEvents() as $event) {
            try {
                $this->recorder->record($event);
            } catch (\Throwable $t) {
                $this->transactions->rollback();
                throw $t;
            }
        }

        $this->transactions->commit();
    }
}
