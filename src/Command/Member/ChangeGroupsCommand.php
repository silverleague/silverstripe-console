<?php

namespace SilverLeague\Console\Command\Member;

use SilverStripe\Security\Group;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Change a member's assigned groups
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChangeGroupsCommand extends AbstractMemberCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('member:change-groups')
            ->setDescription("Change a member's groups")
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $member = $this->getMember($input, $output);
        if (!$member) {
            return;
        }

        if ($member->Groups()->count()) {
            $output->writeln(
                'Member <info>' . $member->Email . '</info> is already in the following groups (will be overwritten):'
            );
            $output->writeln('   ' . implode(', ', $member->Groups()->column('Code')));
            $output->writeln('');
        }

        $allGroups = Group::get()->column('Code');
        $question = new ChoiceQuestion('Select the groups to add this Member to', $allGroups);
        $question->setMultiselect(true);

        $newGroups = $this->getHelper('question')->ask($input, $output, $question);

        $output->writeln('Adding <info>' . $member->Email . '</info> to groups: ' . implode(', ', $newGroups));
        // $member->Groups()->removeAll();
        foreach ($newGroups as $group) {
            $member->addToGroupByCode($group);
        }

        $output->writeln('<info>Groups updated.</info>');
    }
}
