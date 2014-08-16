<?php

namespace PHPExtra\Type\Collection;

use Closure;
use PHPExtra\Sorter\SorterInterface;

/**
 * The CollectionProxy class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class CollectionProxy implements CollectionInterface
{
    /**
     * @var CollectionInterface
     */
    private $collection;

    /**
     * @param CollectionInterface $collection
     */
    function __construct(CollectionInterface $collection)
    {
        $this->setCollection($collection);
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function setCollection(CollectionInterface $collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return CollectionInterface
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * {@inheritdoc}
     */
    public function add($entity)
    {
        $this->getCollection()->add($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->getCollection()->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $c)
    {
        return $this->getCollection()->filter($c);
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $c)
    {
        $this->getCollection()->forAll($c);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->getCollection()->first();
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->getCollection()->last();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset = 0, $length = null)
    {
        return $this->getCollection()->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function sort(SorterInterface $sorter)
    {
        $this->getCollection()->sort($sorter);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->getCollection()->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->getCollection()->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->getCollection()->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->getCollection()->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->getCollection()->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->getCollection()->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->getCollection()->offsetGet($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->getCollection()->offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->getCollection()->offsetUnset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->getCollection()->count();
    }
}