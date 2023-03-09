<?php

use Untek\Framework\Messenger\Presentation\Cli\Commands\ConsumeMessageCommand;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(ConsumeMessageCommand::class);
};
