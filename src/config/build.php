<?php

use lib\util\module;

return module\Module::createInstance()
                ->setName('Build')
                ->setDescription('')
                ->addAuthor(module\Author::createInstance()
                        ->setName('Natanael Simões')
                        ->setEmail('natanael.simoes@ifro.edu.br'))
                ->setVersion('1.0')
                ->setCreationDate('2014-04-21')
                ->setLastReleaseDate('2014-04-21')
                ->setHome(module\Home::createInstance()
                        ->setControl('BuildController')
                        ->setAction('build'))
                ->addController(module\Controller::createInstance()
                        ->setName('BuildController'));
