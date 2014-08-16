<?php
use PHPExtra\Type\Collection\Collection;
use PHPExtra\Type\Collection\CollectionInterface;

/**
 * The CollectionTest class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function collectionProvider()
    {
        $collection = new Collection();
        $collection->add('test1');
        $collection->add('test2');
        $collection->add('test3');
        $collection->add('test4');
        $collection->add('test5');
        $collection->add('test6');
        $collection->add('test7');

        return array(array(
            $collection
        ));
    }

    public function testCreateEmptyCollectionInstance()
    {
        $collection = new Collection();
        $this->assertTrue($collection->isEmpty());
        $this->assertEquals(0, $collection->count());
        $this->assertEquals(0, count($collection));
    }

    public function testAddElementToCollectionIncreasesElementCounter()
    {
        $collection = new Collection();
        $collection->add(new stdClass());

        $this->assertFalse($collection->isEmpty());
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(1, count($collection));
    }

    public function testGetIteratorReturnsIterator()
    {
        $collection = new Collection();
        $collection->add(new stdClass());

        $this->assertInstanceOf('\Iterator', $collection->getIterator());
        $this->assertEquals(1, count($collection->getIterator()));
    }

    public function testOffsetExistsReturnsTrueForValidOffset()
    {
        $collection = new Collection();
        $collection->add(new stdClass());

        $this->assertTrue($collection->offsetExists(0));
    }

    public function testOffsetExistsReturnsFalseForInvalidOffset()
    {
        $collection = new Collection();
        $collection->add(new stdClass());

        $this->assertFalse($collection->offsetExists(1));
    }

    public function testGetValidOffsetReturnsEntity()
    {
        $collection = new Collection();
        $collection->add(new stdClass());

        $this->assertInstanceOf('stdClass', $collection->offsetGet(0));
    }



    /**
     * @expectedException OutOfRangeException
     */
    public function testGetInvalidOffsetThrowsOutOfRangeException()
    {
        $collection = new Collection();
        $collection->add(new stdClass());
        $collection->offsetGet(15);
    }

    public function testSetOffsetSetsGivenOffset()
    {
        $collection = new Collection();
        $collection->offsetSet(15, new stdClass());

        $this->assertInstanceOf('stdClass', $collection->offsetGet(15));
    }

    public function testUnsetOffsetRemovesGivenOffset()
    {
        $collection = new Collection();
        $collection->offsetSet(15, new stdClass());
        $collection->offsetUnset(15);

        $this->assertFalse($collection->offsetExists(15));
    }

    public function testReadOnlyFlag()
    {
        $collection = new Collection();
        $this->assertFalse($collection->isReadOnly());

        $collection->setReadOnly(true);
        $this->assertTrue($collection->isReadOnly());
    }

    public function testIteratorIterateOverElements()
    {
        $collection = new Collection();
        $collection->add('test1');
        $collection->add('test2');
        $collection->add('test3');

        $this->assertEquals('test1', $collection->current());

        $collection->next();
        $this->assertEquals('test2', $collection->current());

        $collection->next();
        $this->assertEquals('test3', $collection->current());

        $collection->rewind();
        $this->assertEquals('test1', $collection->current());
    }

    public function testIteratorReturnsNullOnEmptyCollection()
    {
        $collection = new Collection();

        $this->assertNull($collection->current());
    }

    public function testIteratorReturnsNullOnPositionOutsideOfCollectionBounds()
    {
        $collection = new Collection();
        $collection->add('123');

        $collection->next();

        $this->assertNull($collection->current());
    }


    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Collection is read only
     */
    public function testAddElementThrowsExceptionAsCollectionIsReadOnly()
    {
        $collection = new Collection();
        $collection->setReadOnly(true);
        $collection->add('test');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Collection is read only
     */
    public function testRemoveElementThrowsExceptionAsCollectionIsReadOnly()
    {
        $collection = new Collection();
        $collection->setReadOnly(false);
        $collection->add('test');
        $collection->setReadOnly(true);

        unset($collection[0]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingPositiveLengthAndZeroOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(0, 2);

        $this->assertEquals(2, $slice->count());
        $this->assertEquals('test1', $slice[0]);
        $this->assertEquals('test2', $slice[1]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingPositiveLengthAndOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(2, 3);

        $this->assertEquals(3, $slice->count());
        $this->assertEquals('test3', $slice[0]);
        $this->assertEquals('test4', $slice[1]);
        $this->assertEquals('test5', $slice[2]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingPositiveLengthAndNegativeOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(-1, 2);

        $this->assertEquals(1, $slice->count());
        $this->assertEquals('test7', $slice[0]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingNegativeLengthAndZeroOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(0, -2);

        $this->assertEquals(5, $slice->count());
        $this->assertEquals('test1', $slice[0]);
        $this->assertEquals('test2', $slice[1]);
        $this->assertEquals('test3', $slice[2]);
        $this->assertEquals('test4', $slice[3]);
        $this->assertEquals('test5', $slice[4]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingNegativeLengthAndNegativeOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(-4, -2);

        $this->assertEquals(2, $slice->count());
        $this->assertEquals('test4', $slice[0]);
        $this->assertEquals('test5', $slice[1]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingNegativeLengthAndPositiveOffset(CollectionInterface $collection)
    {
        $slice = $collection->slice(4, -2);

        $this->assertEquals(1, $slice->count());
        $this->assertEquals('test5', $slice[0]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSliceCollectionUsingNegativeLengthAndPositiveOffsetOutOfBounds(CollectionInterface $collection)
    {
        $slice = $collection->slice(7, -2);
        $this->assertEquals(0, $slice->count());
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testSerializeAndUnserializeNestedCollectionsWorksProperly(CollectionInterface $collection)
    {
        $sub1 = new Collection(array(
            new Collection(array(
                new Collection(array(
                    '1', '2', '3'
                ))
            ))
        ));

        $sub2 = new Collection(array(
            new Collection(array(
                new Collection(array(
                    '5', '4', '5'
                ))
            ))
        ));

        $collection->add($sub1)->add($sub2);


        $serialized = serialize($collection);
        $unserializedCollection = unserialize($serialized);


        $this->assertInstanceOf('PHPExtra\Collection\CollectionInterface', $unserializedCollection);
        $this->assertInstanceOf('PHPExtra\Collection\CollectionInterface', $unserializedCollection[7]);
        $this->assertInstanceOf('PHPExtra\Collection\CollectionInterface', $unserializedCollection[8]);
        $this->assertEquals(9, $unserializedCollection->count());
        $this->assertEquals(1, $unserializedCollection[7]->count());
        $this->assertEquals(3, $unserializedCollection[7][0][0]->count());

    }

    /**
     * @dataProvider collectionProvider
     */
    public function testFilterCollectionReturnsFilteredCollection(CollectionInterface $collection)
    {
        $filter = function($element, $index){

            if(is_string($element)){
                switch($element){
                case 'test1':
                case 'test3':
                case 'test5':
                return true;
                default:
                return false;
                }
            }
            return false;

        };

        $newCollection = $collection->filter($filter);

        $this->assertEquals(3, $newCollection->count());
        $this->assertEquals('test1', $newCollection->offsetGet(0));
        $this->assertEquals('test3', $newCollection->offsetGet(1));
        $this->assertEquals('test5', $newCollection->offsetGet(2));

    }

    /**
     * @dataProvider collectionProvider
     */
    public function testPerformForAllOperationModifiesAllEntries(CollectionInterface $collection)
    {
        $callable = function($offset, $element, CollectionInterface $collection){
            $collection->offsetSet($offset, 1);
        };

        $collection->forAll($callable);

        $this->assertEquals(1, $collection[0]);
        $this->assertEquals(1, $collection[2]);
        $this->assertEquals(1, $collection[6]);
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testGetLastElementOnNonEmptyCollectionReturnsEntity(Collection $collection)
    {
        $this->assertNotNull($collection->last());
    }

    /**
     * @dataProvider collectionProvider
     */
    public function testGetFirstElementOnNonEmptyCollectionReturnsEntity(Collection $collection)
    {
        $this->assertNotNull($collection->first());
    }

    public function testGetLastElementOnEmptyCollectionReturnsNull()
    {
        $collection = new Collection();

        $this->assertNull($collection->last());
    }

    public function testGetFirstElementOnEmptyCollectionReturnsNull()
    {
        $collection = new Collection();

        $this->assertNull($collection->first());
    }


}