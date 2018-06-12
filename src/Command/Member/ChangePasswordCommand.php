<?php

namespace SilverLeague\Console\Command\Member;

use SilverStripe\ORM\ValidationResult;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Change a member's password
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChangePasswordCommand extends AbstractMemberCommand
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

        $member = $this->getMember($input, $output);
        if (!$member) {
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
