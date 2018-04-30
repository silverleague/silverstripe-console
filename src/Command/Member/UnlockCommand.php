<?php

namespace SilverLeague\Console\Command\Member;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Unlock a user
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class UnlockCommand extends AbstractMemberCommand
{
    protected function configure()
    {
        $this
            ->setName('member:unlock')
            ->setDescription('Unlock a member account')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $member = $this->getMember($input, $output);
        if (!$member) {
            return;
        }

        $member->LockedOutUntil = null;
        $member->FailedLoginCount = 0;
        $member->write();

        $output->writeln('Member <info>' . $member->Email . '</info> unlocked.');
    }
}

