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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
trait PresenterTrait
{
    public function __get($property)
    {
        $getter = 'get'.ucfirst($property);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        $getter = 'is'.ucfirst($property);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \Exception('Cannot access property $'.$property);
    }
}
