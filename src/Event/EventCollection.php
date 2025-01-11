<?php

declare(strict_types=1);

namespace AwdEs\Event;

interface EventCollection extends \Countable
{
    /**
     * Appends events to the collection.
     */
    public function attach(EntityEvent ...$events): void;

    /**
     * Removes provided event from the collection if it exists.
     */
    public function detach(EntityEvent ...$events): void;

    /**
     * Returns sorted events by date of occurrence, newest first.
     *
     * @return iterable<EntityEvent>
     */
    public function sorted(): iterable;
}
