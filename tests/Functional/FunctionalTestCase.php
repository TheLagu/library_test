<?php

namespace Tests\Functional;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Yaml\Yaml;

class FunctionalTestCase extends TestCase
{
    protected static ?App $app = null;
    protected ?EntityManagerInterface $entityManager = null;
    protected ?ContainerInterface $container = null;
    protected ?array $fixtures = [];

    public function request(string $requestMethod, string $requestUri, ?array $requestData = []): ResponseInterface
    {
        $envData = [
            'REQUEST_METHOD' => $requestMethod,
            'REQUEST_URI' => $requestUri,
        ];
        if (isset($requestData['environment'])) {
            $envData = array_merge($envData, $requestData['environment']);
        }
        // Create a mock environment for testing with
        $environment = Environment::mock($envData);

        $request = Request::createFromEnvironment($environment);

        if (isset($requestData['headers'])) {
            foreach ($requestData['headers'] as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        if (isset($requestData['body'])) {
            $request = $request->withParsedBody($requestData['body']);
        }

        if (isset($requestData['queryParams'])) {
            $request = $request->withQueryParams($requestData['queryParams']);
        }

        if (isset($requestData['files'])) {
            $request = $request->withUploadedFiles($requestData['files']);
        }

        $response = new Response();
        $response = self::$app->process($request, $response);

        $this->entityManager->clear();

        return $response;
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::initApp();
    }

    protected function startTransaction(): void
    {
        if (is_null($this->entityManager)) {
            $this->entityManager = $this->getContainer()->get(EntityManager::class);
        }
        $this->entityManager->getConnection()->beginTransaction();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // iniciamos una transacción
        $this->startTransaction();
    }

    protected function tearDown(): void
    {
        if (!is_null($this->entityManager)) {
            // hacemos rollback de la transacción
            $this->entityManager->getConnection()->rollBack();

            // limpiamos la cache de doctrine por si queda
            // alguna operación pendiente de ejecutar
            $this->entityManager->clear();
        }

        // Eliminamos todos los mocks en los tearDown para menor consumo de memoria

        (function (): void {
            foreach ((new \ReflectionObject($this))->getProperties() as $prop) {
                if ($prop->isStatic() || strpos($prop->getDeclaringClass()->getName(), 'PHPUnit\\') === 0) {
                    continue;
                }

                unset($this->{$prop->getName()});
            }
        })->call($this);

        parent::tearDown();
    }

    protected function getContainer(): ContainerInterface
    {
        if ($this->container instanceof ContainerInterface) {
            return $this->container;
        }

        if (!self::$app instanceof App) {
            $this->initApp();
        }

        $this->container = self::$app->getContainer();

        return $this->container;
    }

    private static function initApp(): void
    {
        if (self::$app instanceof App) {
            return;
        }

        // Use the application settings
        $settings = require __DIR__ . '/../../app/settings.php';

        // Instantiate the application
        $app = new App($settings);

        // Set up Dependencies
        require __DIR__ . '/../../app/dependencies.php';

        // Register middleware
        require __DIR__ . '/../../app/middlewares.php';

        // Register routes
        require __DIR__ . '/../../app/routes.php';

        self::$app = $app;
    }


    /**
     * @param mixed $fixturesName
     */
    protected function loadFixtures($fixturesName): void
    {
        if (!is_array($fixturesName)) {
            $fixturesName = [$fixturesName];
        }

        $content = '';
        foreach ($fixturesName as $fixtureName) {
            if (!is_file(__DIR__ . '/../Fixtures/' . $fixtureName . '.yml')) {
                throw new Exception("Fixture {$fixtureName} not found");
            }

            $content .= file_get_contents(__DIR__ . '/../Fixtures/' . $fixtureName . '.yml') . "\n";
        }

        $fixtures = Yaml::parse(
            $content,
        );

        // inicializamos el mt_srand para un mejor random
        mt_srand(microtime(true));
        foreach ($fixtures as $testName => $entityClass) {
            $table = $entityClass['table_name'];

            $valuesToInsert = $entityClass;

            unset($valuesToInsert['table_name']);

            $this->entityManager->getConnection()->insert(
                $table,
                $valuesToInsert,
            );

            if (!isset($entityClass['id'])) {
                $sequence = $this->entityManager->getConnection()->fetchOne('SELECT to_regclass(\'' . $table . '_id_seq\')');
                if (!is_null($sequence)) {
                    $entityClass['id'] = $this->entityManager->getConnection()->fetchOne('SELECT last_value from ' . $sequence);
                }
            }

            $fixtures[$testName] = $entityClass;
            $this->fixtures[$testName] = $entityClass;
        }
    }
}