<?php 

$container->setParameter('fw_mongo_host', getenv('DB_PORT_27017_TCP_ADDR'));
$container->setParameter('fw_mongo_port', getenv('DB_PORT_27017_TCP_PORT'));