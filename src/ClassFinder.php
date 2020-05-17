<?php

declare(strict_types=1);

namespace Borodulin\Finder;

use Borodulin\Finder\Exception\ParseException;

class ClassFinder implements FinderInterface
{
    /**
     * @var FinderInterface
     */
    private $fileFinder;
    /**
     * @var callable|null
     */
    private $classExtractor;

    public function __construct(FinderInterface $fileFinder = null, callable $classExtractor = null)
    {
        $this->fileFinder = $fileFinder ?: new FileFinder();
        $this->classExtractor = $classExtractor ?: new ClassExtractor();
    }

    public function addPath(string $path): FinderInterface
    {
        $this->fileFinder->addPath($path);

        return $this;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->fileFinder as $fileName) {
            if ('.php' === substr($fileName, -4)) {
                try {
                    $class = \call_user_func($this->classExtractor, $fileName);
                    if ($class) {
                        yield $class;
                    }
                } catch (ParseException $exception) {
                    // skip files with parse error
                }
            }
        }
    }
}
