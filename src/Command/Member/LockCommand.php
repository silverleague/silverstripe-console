<?php

namespace SilverLeague\Console\Command\Member;

use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lock a user
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LockCommand extends AbstractMemberCommand
{
    protected function configure()
    {
        $this
            ->setName('member:lock')
            ->setDescription('Lock a member account')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $member = $this->getMember($input, $output);
        if (!$member) {
            return;
        }

        $lockoutMins = Member::config()->get('lock_out_delay_mins');
        $member->LockedOutUntil = date('Y-m-d H:i:s', DBDatetime::now()->getTimestamp() + $lockoutMins * 60);
        $member->FailedLoginCount = 0;
        $member->write();

        $output->writeln('Member <info>' . $member->Email . '</info> locked for <info>' . $lockoutMins . ' mins.</info>');
    }
}

