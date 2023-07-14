<?php

namespace MingyuKim\MoreCommand\Traits;

/**
 * 클래스를 쉽게 싱글톤으로 만들어주는 trait
 */
trait SingletonTrait
{
    private static $instanse = null;

    /**
     *
     * @return self
     */
    static function getInstance()
    {
        if (self::$instanse == null) {
            self::$instanse = new self;
        }

        return self::$instanse;
    }
}