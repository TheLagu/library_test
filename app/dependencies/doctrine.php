<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\Setup;
use Slim\Container;

$container[EntityManager::class] = function (Container $c) {
    //TODO Esta variable debería depender de, por ejemplo, una variable de entorno o de configuración
    $isDevMode = true;

    $paths = array("/path/to/yml-mappings");
    //$config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);

    $config = Setup::createConfiguration(
        $isDevMode,
        sys_get_temp_dir() . '/doctrine-proxies',
        !$isDevMode ? new \Doctrine\Tests\Common\Cache\ArrayCache() : null
    );
    $namespaces = [
        __DIR__ . '/../../src/Books/Entities/orm' => 'Library\Books\Entities',
    ];
    $driver = new SimplifiedYamlDriver($namespaces);
    $config->setMetadataDriverImpl($driver);

    //alias
    $config->setEntityNamespaces([
        'Books' => 'Library\Books\Entities',
    ]);

    $conn = [
        'driver' => 'pdo_pgsql',
        'user' => $c->settings['db']['user'],
        'password' => $c->settings['db']['pass'],
        'dbname' => $c->settings['db']['dbname'],
        'host' => $c->settings['db']['host'],
        'port' => $c->settings['db']['port'],
    ];

    $entityManager = EntityManager::create($conn, $config);
    //$configuration = $entityManager->getConfiguration();

    //TODO para mejorar la búsqueda por título, se podría añadir la función de full_text (habría que implementarla)
    // $configuration->addCustomStringFunction('FULL_TEXT', function ($name) use ($entityManager) {
    //    return new FullText($name, $entityManager);
    //});

    if ($isDevMode) {
//        $configuration->setSQLLogger($c->get(\....\SqlLogger::class));
        $validator = new SchemaValidator($entityManager);
        $errors = $validator->validateMapping();
        if (!empty($errors)) {
            throw new MappingException(json_encode($errors));
        }
    }

    return $entityManager;
};
