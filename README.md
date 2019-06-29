netrc
=====

Simple netrc files manager

[![Build Status](https://travis-ci.org/fduch/netrc.svg?branch=master)](https://travis-ci.org/fduch/netrc)

Installation
--------------
To install netrc manager into your project please use composer:

```sh
php composer.phar require fduch/netrc
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
} catch (FileNotFoundException $e) {
     // cannot locate readable netrc file
} catch (ParseException $e) {
    // something is wrong with your netrc file
}

```
