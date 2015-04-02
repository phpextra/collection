<?php
use PHPExtra\Type\Collection\Collection;
use PHPExtra\Type\Collection\LazyCollection;

/**
 * The LazyCollectionTest class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class LazyCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function collectionProvider()
    {
        return array(array(
            new LazyCollection(function(){
                return new Collection(array('test1', 'test2', 'test3'));
            })
        ));
    }

    public function testCreateEmptyCollectionInstance()
    {
        $collection = new LazyCollection();
        $this->assertTrue($collection->isEmpty());
        $this->assertEquals(0, $collection->count());
        $this->assertEquals(0, count($collection));
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSerializeCollectionInitializesAndSerializesCollection(LazyCollection $collection)
    {
        $serialized = serialize($collection);

        /** @var LazyCollection $unserialized */
        $unserialized = unserialize($serialized);

        $this->assertEquals(3, $unserialized->count());
        $this->assertEquals('test1', $unserialized[0]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInitializerReturnsWrongTypeThrowsRuntimeException()
    {
        $collection = new LazyCollection(function(){return 'asd';});
        $collection->count();
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testLazyCollectionInitializeOnCount(LazyCollection $collection)
    {
        $this->assertEquals(3, $collection->count());
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testLazyCollectionInitializeOnOffsetGet(LazyCollection $collection)
    {
        $this->assertEquals($collection[1], 'test2');
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testLazyCollectionInitializeOnOffsetExists(LazyCollection $collection)
    {
        $this->assertTrue(isset($collection[1]));
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testLazyCollectionInitializeOnIfEmpty(LazyCollection $collection)
    {
        $this->assertFalse($collection->isEmpty());
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testGetIteratorReturnsIterator(LazyCollection $collection)
    {
        $this->assertInstanceOf('\Iterator', $collection->getIterator());
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testForeachTriggersInitialize(LazyCollection $collection)
    {
        $entries = 0;
        foreach($collection as $entry){
            $entries++;
        }

        $this->assertEquals(3, $entries);
    }

    public function testSerializeAndUnserializeNestedCollectionsWorksProperly()
    {
        $collection = new LazyCollection(function(){
            return new LazyCollection(function(){
                return new Collection(array(1, 'zażółćgęśląjaźń', '\'\"\'', 4));
            });
        });

        $serialized = serialize($collection);
        $unserializedCollection = unserialize($serialized);

        $this->assertInstanceOf('PHPExtra\Type\Collection\LazyCollection', $unserializedCollection);

        $this->assertEquals('1', $unserializedCollection[0]);
        $this->assertEquals('4', $unserializedCollection[3]);
    }

    public function testUnsetFirstOffsetAndDoAForeachLoop()
    {
        $collection = new LazyCollection(function(){
            return new Collection(array(
                'one', 'two', 'three'
            ));
        });

        unset($collection[0]);

        $loops = 0;
        foreach($collection as $element){
            $loops++;
        }

        $this->assertEquals(2, $loops, 'Foreach on non-empty collection is not working');
    }
}