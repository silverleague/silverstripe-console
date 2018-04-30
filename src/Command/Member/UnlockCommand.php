<?php

namespace SilverLeague\Console\Command\Member;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Unlock a user
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class UnlockCommand extends SilverStripeCommand
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
        $email = $this->getOrAskForArgument($input, $output, 'email', 'Enter email address: ');
        if (empty($email)) {
            $output->writeln('<error>Please enter an email address.</error>');
            return;
        }

        /** @var Member $member */
        $member = Member::get()->filter('email', $email)->first();
        if (!$member) {
            $output->writeln('<error>Member with email "' . $email . '" was not found.');
            return;
        }

        $member->LockedOutUntil = null;
        $member->FailedLoginCount = 0;
        $member->write();

        $output->writeln('Member <info>' . $email . '</info> unlocked.');
    }
}

