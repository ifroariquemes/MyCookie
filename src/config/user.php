<?php

use lib\util\module;

return module\Module::createInstance()
                ->setName('Users')
                ->setDescription('')
                ->addAuthor(module\Author::createInstance()
                        ->setName('Natanael Simões')
                        ->setEmail('natanael.simoes@ifro.edu.br'))
                ->setVersion('1.0')
                ->setCreationDate('2012-06-15')
                ->setLastReleaseDate('2014-04-20')
                ->setTile(module\Tile::createInstance()
                        ->setColor('concrete')
                        ->setIcon('fa-user'))
                ->setHome(module\Home::createInstance()
                        ->setControl('UserController')
                        ->setAction('Manage'))
                ->addController(module\Controller::createInstance()
                        ->setName('UserController'));
