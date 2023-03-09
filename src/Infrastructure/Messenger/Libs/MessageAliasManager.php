<?php

namespace Untek\Framework\Messenger\Infrastructure\Messenger\Libs;

class MessageAliasManager
{

    private static array $aliases = [];

    public function addAlias(string $alias, string $className): void
    {
        self::$aliases[$alias] = $className;
    }

    public function getClass(string $alias): string
    {
        return self::$aliases[$alias];
    }

    public function getAlias(string $className): string
    {
        $aliases = array_flip(self::$aliases);
        return $aliases[$className];
    }
}
