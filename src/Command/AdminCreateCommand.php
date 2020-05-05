<?php

namespace App\Command;

use App\Security\InvitationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminCreateCommand extends Command
{
    public const ARG_EMAIL = 'email';
    public const ARG_ROLE = 'role';

    /**
     * @var InvitationManager
     */
    private $invitationManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(InvitationManager $invitationManager, ValidatorInterface $validator)
    {
        parent::__construct('app:admin:invite');
        $this->invitationManager = $invitationManager;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates an admin account')
            ->addArgument(self::ARG_EMAIL, InputArgument::REQUIRED, 'E-Mail')
            ->addArgument(self::ARG_ROLE, InputArgument::REQUIRED, 'Role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument(self::ARG_EMAIL);
        $role = $input->getArgument(self::ARG_ROLE);

        if (!$this->validateEmail($email, $io)) {
            return 1;
        }

        $this->invitationManager->invite($email, [$role]);

        $io->success(
            sprintf('Invitation sent to "%s"', $email)
        );

        return 0;
    }

    private function validateEmail(string $email, SymfonyStyle $io)
    {
        $violations = $this->validator->validate($email, new Email());
        if ($violations->count() > 0) {
            $violation = $violations->get(0);
            $io->error(
                sprintf('"%s" -> %s', $violation->getInvalidValue(), $violation->getMessage())
            );

            return false;
        }

        return true;
    }
}
