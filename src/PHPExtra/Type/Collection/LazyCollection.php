<?php

namespace PHPExtra\Type\Collection;

use Closure;

/**
 * The LazyCollection class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class LazyCollection extends CollectionProxy implements \Serializable
{
    /**
     * @var Closure
     */
    protected $initializer;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @param Closure $initializer
     */
    public function __construct(Closure $initializer = null)
    {
        if ($initializer !== null) {
            $this->setInitializer($initializer);
        }
        parent::__construct(new Collection());
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function initialize()
    {
        if ($this->getInitializer() !== null && !$this->isInitialized) {

            $this->isInitialized = true;

            $unknownType = new UnknownType(call_user_func($this->getInitializer()));

            if(!$unknownType->isCollection()){
                throw new \RuntimeException(sprintf('Unexpected type given: %s', gettype($unknownType->getValue())));
            }

            foreach($unknownType->getAsCollection() as $element){
                $this->getCollection()->add($element);
            }

        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        if(!$this->isInitialized()){
            $this->initialize();
        }
        return parent::getCollection();
    }

    /**
     * @param Closure $initializer
     *
     * @return $this
     */
    public function setInitializer(Closure $initializer)
    {
        $this->initializer = $initializer;

        return $this;
    }

    /**
     * @return callable
     */
    public function getInitializer()
    {
        return $this->initializer;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->isInitialized;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->getCollection());
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $collection = unserialize($serialized);
        $this->__construct(function() use ($collection){
            return $collection;
        });
    }

    /**
     * @deprecated since 1.2
     * @return $this
     */
    public function getIterator()
    {
        return $this;
    }
}
