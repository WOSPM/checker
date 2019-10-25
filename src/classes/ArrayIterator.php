<?php
namespace WOSPM\Checker;

/**
 * Array iterator class extending the original
 */
class ArrayIterator extends \ArrayIterator
{
    /**
     * Get next element
     *
     * @return void
     */
    public function getNext()
    {
        $this->next();
        return $this->current();
    }
}