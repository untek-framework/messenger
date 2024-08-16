<?php

namespace Untek\Framework\Messenger\Infrastructure;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Kernel\Bundle\BaseBundle;

DeprecateHelper::hardThrow();

class MessengerBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'messenger';
    }
}
