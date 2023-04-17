<?php

namespace Untek\Framework\Messenger\Infrastructure;

use Untek\Core\Kernel\Bundle\BaseBundle;

class MessengerBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'messenger';
    }
}
