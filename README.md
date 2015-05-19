# MyCookie 0.3b

MyCookie is a framework made for PHP that works with highly modularized structures and abstraction level. It has some interesting features to developers looking for a fast programming without despise the application performance.

## About the structure

Build over a legible sintax for newbie eyes, highly learneable, PHP programming was never easier as with MyCookie. 

Want some explanation? Every file created at MyCookie belongs to a namespace. Because it uses [PSR-0 standard](http://petermoulding.com/php/psr) there is no need of <code>require()</code> or <code>include()</code> pointing the way every-holy-time. Just use something like <code>use model\user\MyUser</code> and have fun! Better then that, if you have any helper/function/class made for another framework, you can use it here (since it uses PSR-0 standard too).

Simple, isn't? All this and much more without losing performance.

What differs it from others frameworks is the building procedure, as happens in desktop languagens (e.g. Java, .Net, Delphi). It is NOT ABOUT verifying your project lexically or semantically, is something like happens at .Net (ASPX) building: THE BUNDLE.

## The building procedure

The building procedure captures all JavaScript and CSS in source and generate an uglyfied file, one for each respectively, so you just need to reference the bundle on your HTML and everything will load faster. 

It does more 2 activities (a) cache cleaning, so every cached page will be deleted every new building, and (b) object-relational mapping, so model classes will be mapped into database tables.

### Object-relational mapping

MyCookie uses [Doctrine](http://doctrine-project.org/) as ORM. This tools implements annotations as in Hibernate, so when the building procedure is running, a database schema will be generated as to create as to update the existing database. 

An model class sample:

```php
<?php

namespace model\user;

use lib\util\Object;

/**
 * @Entity
 * @Table(name="user")
 */
class User extends Object {

    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** Gets and Sets and stuff *//
}

?>
```

## Modularity

The modularity of MyCookie is also a very strong point. The whole architecture was developed with this purpose: that the URL `http://mywebsite.com/news/list` could both load an entire website page or be just a single news list as return for a HTTP request sent by AJAX. The possibilities are uncountable.

Furthermore, the folder structure + PSR-0 also provides modularity, so you can easily copy-paste any MyCookie module from web or even migrate to this framework. 

## Some really good (maybe useless) functions

As not every CMS is made with just a good core, MyCookie stands out for the many awesome features that it offers containing a huge auxiliar classes library, to calculate the distance between two points in a cartesian plane even search a value in a vector of n-dimensions.

Some features you will find at MyCookie:

* Arithmetic
* Date, image, text and vector handling
* HTML generator
* ORM (in this version with all databases, thanks to Doctrine!)

## Collaborate!

The future plans for MyCookie are widely ambitious and yet there is lot of ideas that need to be designed to reach better results. Therefore, we need your collaboration to boost this project to obtain a stable version as fast as possible. For this, clone this master branch, see the source, read the documentation and source comments, understand its functioning, ask if you have doubts and GIVE YOUR SUGGESTIONS.

Already we thank you too much for you dedication with open-source!

## Contact

**Natanael Simões**

- **Email:** natanael.simoes@ifro.edu.br
- **Twitter:** [@natanaelsimoes](http://twitter.com/natanaelsimoes)
- **Facebook:** http://www.facebook.com/natanaelsimoes