<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Framework;

use BuzzingPixel\Queue\QueueHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueueConsumeNextSymfonyCommand extends Command
{
    public function __construct(private readonly QueueHandler $queueHandler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('buzzingpixel-queue:consume-next');

        $this->addOption(
            'queue-name',
            '',
            InputOption::VALUE_OPTIONAL,
            'The queue name to consume',
            'default',
        );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        /** @phpstan-ignore-next-line */
        $queueName = (string) $input->getOption('queue-name');

        $this->queueHandler->consumeNext($queueName);

        return 0;
    }
}
