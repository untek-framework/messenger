<?php

namespace Untek\Framework\Messenger\Infrastructure;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Kernel\Bundle\BaseBundle;

DeprecateHelper::hardThrow();

class MessageBusBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'message-bus';
    }

}
