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
class Presenter
{
    protected $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function __call($name, array $params)
    {
        return $this->subject->$name(...$params);
    }

    public function __get($property)
    {
        $getter = 'get'.ucfirst($property);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        if (method_exists($this->subject, $getter)) {
            return $this->subject->$getter();
        }
        $getter = 'is'.ucfirst($property);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        if (method_exists($this->subject, $getter)) {
            return $this->subject->$getter();
        }

        throw new \Exception('Cannot access property $'.$property);
    }
}
