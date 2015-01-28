[![LeLivreScolaire](http://h2010.associationhec.com/images/news/logo-officiel-jpeg.jpg)](http://www.lelivrescolaire.fr)

# *AWS Bundle* [![Build Status](https://secure.travis-ci.org/lelivrescolaire/AWSBundle.png?branch=master)](http://travis-ci.org/lelivrescolaire/AWSBundle) [![Coverage Status](https://coveralls.io/repos/lelivrescolaire/AWSBundle/badge.png?branch=master)](https://coveralls.io/r/lelivrescolaire/AWSBundle?branch=master)

Communicate with your AWS infrastructure from inside your Symfony 2 application.

## Features

* Handle multiple AWS Identities
* Fit your need by installing services extensions

## Documentation

### Installation

```shell
$ composer require "lelivrescolaire/aws-bundle:dev-master"
```

AppKernel:

```php
public function registerBundles()
{
    $bundles = array(
        new LLS\Bundle\AWSBundle\LLSAWSBundle()
    );
}
```

### Configuration reference

```yml
llsaws:
    config_auto_discovery: false
    identities:
        lls_sqs_user:
            type:   user
            fields:
                key:    %aws_key%
                secret: %aws_secret%
    services:
        lls_sqs:
            type: sqs
            identity: lls_sqs_user
```

Read more documentation [here](./Resources/doc/index.md)

## Contribution

Feel free to send us [Pull Requests](https://github.com/lelivrescolaire/AWSBundle/compare) and [Issues](https://github.com/lelivrescolaire/AWSBundle/issues/new) with your fixs and features.

## Run test

### Unit tests

```shell
$ ./bin/atoum
```

### Coding standards

```shell
$ ./bin/coke
```