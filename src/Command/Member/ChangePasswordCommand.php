<?php

namespace SilverLeague\Console\Command\Member;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Change a member's password
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChangePasswordCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('member:change-password')
            ->setDescription("Change a member's password")
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address')
            ->addArgument('password', InputArgument::OPTIONAL, 'New password');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $this->getOrAskForArgument($input, $output, 'email', 'Enter email address: ');
        $password = $this->getOrAskForArgument($input, $output, 'password', 'Enter password: ');
        if (empty($email) || empty($password)) {
            $output->writeln('<error>Please enter an email address and a new password.</error>');
            return;
        }

        /** @var Member $member */
        $member = Member::get()->filter('email', $email)->first();
        if (!$member) {
            $output->writeln('<error>Member with email "' . $email . '" was not found.');
            return;
        }

        /** @var ValidationResult $result */
        $result = $member->changePassword($password);
        if ($result->isValid()) {
            $output->writeln('<info>Password updated.</info>');
            return;
        }

        $output->writeln('<error>Failed to save the new password.</error>');
        foreach ($result->getMessages() as $messageDetails) {
            $output->writeln('<error> * ' . $messageDetails['message'] . '</error>');
        }
    }
}
