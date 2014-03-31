netrc
=====

Simple netrc files manager

[![Build Status](https://travis-ci.org/fduch/netrc.svg?branch=master)](https://travis-ci.org/fduch/netrc)

Installation
--------------
To install netrc manager into your project please use composer:

```sh
php composer.phar require fduch/netrc:0.0.2
```

You can also use current development version of the library by requiring 1.0.x-dev:

```sh
php composer.phar require fduch/netrc:1.0.x-dev
```

Usage
-----
Parsing system-wide or custom netrc is quite simple:

``` php
<?php

use Fduch\Netrc\Netrc;
use Fduch\Netrc\Exception\ParseException;

try {
    // you can specify path to netrc file as an argument of Netrc::parse() method
    $parsed = Netrc::parse();
    // dumps key-value array corresponding to machine.one entry
    var_dump($parsed['machine.one']);
} catch (ParseException $e) {
    //something is wrong with your netrc file
}

```
