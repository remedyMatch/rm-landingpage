<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\KeycloakException;
use App\Repository\AccountRepository;
use App\Service\AccountManager;
use App\Service\KeycloakManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrateAccountsToKeycloakCommand extends Command
{
    protected static $defaultName = 'app:keycloak:migrate-local-accounts';

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var KeycloakManager
     */
    private $keycloakManager;

    /**
     * @var AccountManager
     */
    private $accountManager;

    public function __construct(
        AccountRepository $accountRepository,
        AccountManager $accountManager,
        KeycloakManager $keycloakManager
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountManager = $accountManager;
        $this->keycloakManager = $keycloakManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrates all  user to new Keycloak instance.')
            ->setHelp('This command allows you to migrate all users to a new Keycloak instance...');
    }

    /**
     * @throws KeycloakException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Migrating local accounts to Keycloak');

        $accounts = $this->accountRepository->findAll();
        $accountCount = count($accounts);
        $io->progressStart($accountCount);
        foreach ($accounts as $account) {
            try {
                $this->keycloakManager->createAccount($account);
            } catch (KeycloakException $exception) {
                $io->writeln(PHP_EOL); // Newline after progressbar
                $io->error(sprintf('Failed to migrate: "%s"', $account->getEmail()));

                throw  $exception;
            }

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success(sprintf('Migration finished; Migrated %d accounts', $accountCount));

        return 0;
    }
}
