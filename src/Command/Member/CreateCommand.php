<?php

namespace SilverLeague\Console\Command\Member;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Create a new member, and optionally add them to groups and roles.
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class CreateCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('member:create')
            ->setDescription('Create a new member, and optionally add them to groups')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address')
            ->addArgument('username', InputArgument::OPTIONAL, 'Username')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'First name')
            ->addArgument('surname', InputArgument::OPTIONAL, 'Surname');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            'Email'     => $this->getOrAskForArgument($input, $output, 'email', 'Email address: '),
            'Password'  => $this->getOrAskForArgument($input, $output, 'password', 'Password: '),
            'FirstName' => $this->getOrAskForArgument($input, $output, 'firstname', 'First name: '),
            'Surname'   => $this->getOrAskForArgument($input, $output, 'surname', 'Surname: ')
        ];
        if (empty($data['Email']) || empty($data['Password'])) {
            $output->writeln('<error>Please enter an email address and password.</error>');
            return;
        }

        // Check for existing member
        $member = Member::get()->filter(['Email' => $data['Email']])->first();
        if ($member) {
            $output->writeln('<error>Member already exists with email address: ' . $data['Email']);
            return;
        }

        $member = Member::create();
        foreach ($data as $key => $value) {
            $member->setField($key, $value);
        }
        $member->write();

        $output->writeln('<info>Member created.</info>');

        $setGroups = new Question('Do you want to assign groups now? ', 'yes');
        if ($this->getHelper('question')->ask($input, $output, $setGroups) === 'yes') {
            $command = $this->getApplication()->find('member:change-groups');
            $command->run(
                new ArrayInput([
                    'command' => 'member:change-groups',
                    'email'   => $data['Email']
                ]),
                $output
            );
        }
    }
}
