<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Presenter;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class CollectionPresenter implements \Iterator
{
    private $collection;
    private $position = 0;
    private $presenterFactory;

    public function __construct(
        $collection,
        PresenterFactoryInterface $presenterFactory
    ) {
        if (is_array($collection)) {
            $this->collection = new ArrayCollection($collection);
        } elseif ($collection instanceof \ArrayAccess) {
            $this->collection = $collection;
        } else {
            throw new \InvalidArgumentException('$collection is expected to be of type array or \ArrayAccess');
        }
        $this->position = 0;
        $this->presenterFactory = $presenterFactory;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->presenterFactory->createPresenter($this->collection[$this->position]);
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->collection[$this->position]);
    }
}
