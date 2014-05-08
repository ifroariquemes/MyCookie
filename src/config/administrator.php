<?php

use lib\util\module;

return module\Module::createInstance()
                ->setName('Administrator')
                ->setDescription('')
                ->addAuthor(module\Author::createInstance()
                        ->setName('Natanael Simões')
                        ->setEmail('natanael.simoes@ifro.edu.br'))
                ->setVersion('1.0')
                ->setCreationDate('2012-05-03')
                ->setLastReleaseDate('2014-04-21')
                ->setHome(module\Home::createInstance()
                        ->setControl('AdministradorController')
                        ->setAction('ShowPage'))
                ->addController(module\Controller::createInstance()
                        ->setName('AdministratorController'));
