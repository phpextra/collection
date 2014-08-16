<?php

namespace PHPExtra\Type\Collection;

use Closure;
use PHPExtra\Sorter\SortableInterface;
use PHPExtra\Sorter\SorterInterface;

/**
 * The CollectionInterface interface
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
interface CollectionInterface extends \Countable, \ArrayAccess, \Iterator, SortableInterface
{
    /**
     * @param mixed $entity
     *
     * @return $this
     */
    public function add($entity);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Return collection of elements that matched the filter
     * Filter takes two arguments - the element and its index.
     *
     * @param  Closure             $c
     * @return CollectionInterface
     */
    public function filter(Closure $c);

    /**
     * Perform given operation on all collection elements
     *
     * @param Closure $c
     *
     * @return CollectionInterface
     */
    public function forAll(Closure $c);

    /**
     * Get first element from the collection
     *
     * @since 1.0.1
     * @return mixed
     */
    public function first();

    /**
     * Get last element from the collection
     *
     * @since 1.0.1
     * @return mixed
     */
    public function last();

    /**
     * Extract a slice of the collection
     *
     * @param int $offset
     * @param int $length
     *
     * @return $this
     */
    public function slice($offset = 0, $length = null);

    /**
     * {@inheritdoc}
     */
    public function sort(SorterInterface $sorter);
}
