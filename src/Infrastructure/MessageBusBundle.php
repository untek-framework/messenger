<?php

namespace Untek\Framework\Messenger\Infrastructure;

use Untek\Core\Kernel\Bundle\BaseBundle;

class MessageBusBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'message-bus';
    }

    public function boot(): void
    {
        if ($this->isCli()) {
            $this->configureFromPhpFile(__DIR__ . '/config/commands.php');
        }
    }
}
