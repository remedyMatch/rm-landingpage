<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\AccountCreationException;
use App\Exception\KeycloakException;
use App\Repository\AccountRepository;
use App\Service\AccountManager;
use App\Service\KeycloakRestApiServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class migrateUsers extends Command
{
    protected static $defaultName = 'app:migrate-users';

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var KeycloakRestApiServiceInterface
     */
    private $keycloakRestApi;

    /**
     * @var AccountManager
     */
    private $accountManager;

    public function __construct(
        AccountRepository $accountRepository,
        AccountManager $accountManager,
        KeycloakRestApiServiceInterface $keycloakRestApi
    ) {
        $this->accountRepository = $accountRepository;
        $this->accountManager = $accountManager;
        $this->keycloakRestApi = $keycloakRestApi;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Migrates all  user to new Keycloak instance.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to migrate all users to a new Keycloak instance...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Migration',
            '============',
            '',
        ]);

        foreach ($this->accountRepository->findAll() as $account) {
            $output->writeln([
               'User '.$account->getEmail().' will be migrate',
               '============',
           ]);
            try {
                $this->keycloakManager->createAccount($account);
            } catch (KeycloakException $exception) {
                $message = 'User could not be registered due to keycloak problems';
                $this->logger->error($message, [
                   'account' => $account,
               ]);
                throw new AccountCreationException($message, 4820244);
            }
        }

        return 0;
    }
}
