<?php

declare(strict_types=1);

namespace AwdEs\Meta\Entity\Reading;

use AwdEs\Meta\Entity\EntityMeta;

interface EntityMetaReader
{
    /**
     * @template TEntity of \AwdEs\Aggregate\Entity
     *
     * @param class-string<TEntity> $entityClass
     *
     * @return EntityMeta<TEntity>
     *
     * @throws \AwdEs\Meta\Entity\Exception\EntityMetaReadingError
     */
    public function read(string $entityClass): EntityMeta;
}
