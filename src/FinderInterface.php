<?php

declare(strict_types=1);

namespace Borodulin\Finder;

interface FinderInterface extends \IteratorAggregate
{
    public function addPath(string $path): self;
}
