<?php

namespace SilverLeague\Console\Command\Member;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides a base for member related commands, lookups etc
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
abstract class AbstractMemberCommand extends SilverStripeCommand
{
    /**
     * Get a member by the provided email address, output an error message if not found
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return Member|false
     */
    protected function getMember(InputInterface $input, OutputInterface $output)
    {
        $email = $this->getOrAskForArgument($input, $output, 'email', 'Enter email address: ');
        if (empty($email)) {
            $output->writeln('<error>Please enter an email address.</error>');
            return false;
        }

        /** @var Member $member */
        $member = Member::get()->filter('email', $email)->first();
        if (!$member) {
            $output->writeln('<error>Member with email "' . $email . '" was not found.');
            return false;
        }

        return $member;
    }
}

