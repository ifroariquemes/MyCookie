<?php

use lib\util\module;

return module\Module::createInstance()
                ->setName('Index')
                ->setDescription('')
                ->addAuthor(module\Author::createInstance()
                        ->setName('Natanael Simões')
                        ->setEmail('natanael.simoes@ifro.edu.br'))
                ->setVersion('1.0')
                ->setCreationDate('2012-06-15')
                ->setLastReleaseDate('2014-04-20')
                ->setHome(module\Home::createInstance()
                        ->setControl('IndexController')
                        ->setAction('ShowPage'))
                ->addController(module\Controller::createInstance()
                        ->setName('IndexController'));
