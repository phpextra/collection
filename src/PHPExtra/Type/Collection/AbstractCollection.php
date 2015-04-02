<?php

namespace PHPExtra\Type\Collection;

use Closure;
use PHPExtra\Sorter\SorterInterface;

/**
 * The AbstractCollection class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $isIteratorValid = false;

    /**
     * @var \ArrayIterator
     */
    protected $iterator = null;

    /**
     * @var array|mixed[]
     */
    protected $entities = array();

    /**
     * @param array $entities
     */
    public function __construct(array $entities = array())
    {
        foreach ($entities as $entity) {
            $this->add($entity);
        }
    }

    /**
     * @param boolean $readOnly
     *
     * @return $this
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * @deprecated use isReadOnly
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->isReadOnly();
    }

    /**
     * {@inheritdoc}
     */
    public function add($entity)
    {
        if ($this->isReadOnly()) {
            throw new \RuntimeException('Collection is read only');
        }
        $this->entities[] = $entity;
        $this->isIteratorValid = false;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if(!$this->iterator || !$this->isIteratorValid){
            $this->regenerateIterator();
            $this->isIteratorValid = true;
        }
        return $this->iterator;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfRangeException(sprintf('Index out of range: "%s"', $offset));
        }

        return $this->entities[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($this->isReadOnly()) {
            throw new \RuntimeException('Collection is read only');
        }

        if ( ! isset($offset)) {
            return $this->add($value);
        }else{
            $this->entities[$offset] = $value;
        }

        $this->isIteratorValid = false;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->isReadOnly()) {
            throw new \RuntimeException('Collection is read only');
        }
        unset($this->entities[$offset]);
        $this->isIteratorValid = false;
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->count() > 0 ? $this->offsetGet(0) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->count() > 0 ? $this->offsetGet($this->count() - 1) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->getIterator()->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->getIterator()->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->getIterator()->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->getIterator()->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->getIterator()->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $c)
    {
        $filtered = new Collection();
        $this->rewind();
        foreach ($this as $offset => $element) {
            if ($c($element, $offset) === true) {
                $filtered->add($element);
            }
        }
        $this->rewind();
        return $filtered;
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $c)
    {
        foreach($this->entities as $offset => $entity){
            $c($offset, $entity, $this);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset = 0, $length = null)
    {
        if($this->entities instanceof CollectionInterface){
            $collection = $this->entities->slice($offset, $length);
        }else{
            $slice = array_slice($this->entities, $offset, $length);
            $collection = new Collection($slice);
        }

        $collection->setReadOnly($this->isReadOnly());
        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function sort(SorterInterface $sorter)
    {
        $this->rewind();
        $this->entities = $sorter->sort($this->entities);
        return $this;
    }

    /**
     * Regenerate iterator after any change of the entities array
     */
    private function regenerateIterator()
    {
        $this->iterator = new \ArrayIterator($this->entities);
    }
}
