<?php
/**
/ Content of this file will ovewrite parameters.yml
**/

/* Database parameters */
$container->setParameter('database_host', getenv('SEED_MEDIA_DATABASE_HOST'));
$container->setParameter('database_port', getenv('SEED_MEDIA_DATABASE_PORT'));
$container->setParameter('database_name', getenv('SEED_MEDIA_DATABASE_NAME'));
$container->setParameter('database_user', getenv('SEED_MEDIA_DATABASE_USER'));
$container->setParameter('database_password', getenv('SEED_MEDIA_DATABASE_PASSWORD'));
