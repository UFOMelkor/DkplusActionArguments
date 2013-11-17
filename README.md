# DkplusActionArguments

[![Build Status](https://travis-ci.org/UFOMelkor/DkplusActionArguments.png?branch=master)](https://travis-ci.org/UFOMelkor/DkplusActionArguments)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/UFOMelkor/DkplusActionArguments/badges/quality-score.png?s=a9e9cbfa015fd67828ac146e9cffdb9609db0ecd)](https://scrutinizer-ci.com/g/UFOMelkor/DkplusActionArguments/)
[![Coverage Status](https://coveralls.io/repos/UFOMelkor/DkplusActionArguments/badge.png?branch=master)](https://coveralls.io/r/UFOMelkor/DkplusActionArguments)
[![Total Downloads](https://poser.pugx.org/dkplus/action-arguments/downloads.png)](https://packagist.org/packages/dkplus/action-arguments)
[![Latest Stable Version](https://poser.pugx.org/dkplus/action-arguments/v/stable.png)](https://packagist.org/packages/dkplus/action-arguments)
[![Latest Unstable Version](https://poser.pugx.org/dkplus/action-arguments/v/unstable.png)](https://packagist.org/packages/dkplus/action-arguments)
[![Dependency Status](https://www.versioneye.com/user/projects/52719dd6632bac71fe000146/badge.png)](https://www.versioneye.com/user/projects/52719dd6632bac71fe000146)

 - [Features](#features)
 - [Examples](#examples)
 - [Installation](#installation)
 - [ToDo](#todo)

## Features

 - Provides named arguments from route match. ([Example 1](#named-scalar-arguments))
 - Can convert scalar arguments into classes.
 - Has built in support for Doctrine ORM
   ([Example 2](#simplest-converting-by-using-doctrine-objectrepositoryfind),
   [Example 3](#converting-by-using-a-custom-doctrine-objectrepository-method)),
   but also usable with every other mapping solution by using callbacks
   ([Example 4](#converting-by-using-a-callback), [Example 5](#converting-by-using-a-custom-converter)).
 - Supports optional arguments.
 - When one argument could not be mapped into an entity, a 404 error page could be shown.
 - If your assertions can be retrieved from the service locator, the arguments could be injected into your assertions; that
   way you could improve your controller-/route-guards with better assertions.

### Examples

#### Named scalar arguments

```php
use DkplusActionArguments\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Route looks like /user/:id
     */
    public function viewAction($id)
    {
        return array('user' => $this->mapper->find($id));
    }
}
```

#### Simplest converting by using Doctrine ObjectRepository::find

```php
use DkplusActionArguments\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Route looks like /user/:user
     */
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }
}
```

#### Converting by using a custom Doctrine ObjectRepository method

```php
use DkplusActionArguments\Annotation\MapParam;
use DkplusActionArguments\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Route looks like /user/:name
     * @MapParam(from="name", to="user", using="findOneByName")
     */
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }
}
```

#### Converting by using a callback

```php
use DkplusActionArguments\Annotation\MapParam;
use DkplusActionArguments\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Route looks like /user/:user
     * @MapParam(to="user", using={"sm_key", "method"})
     */
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }
}
```

#### Converting by using a custom converter

```php
use DkplusActionArguments\Annotation\MapParam;
use DkplusActionArguments\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * Route looks like /user/:user
     * @MapParam(to="user", using="my_converter_sm_key")
     */
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }
}
```

## Installation

Installation of this module uses composer. For composer documentation, please refer to
[http://getcomposer.org/](getcomposer.org).

`php composer.phar require dkplus/action-arguments`

When asked for a version to install, type `dev-master`. You can then enable it in your `config/application.config.php`
by adding `DkplusActionArguments` to your modules.

After installing copy `config/dkplus-action-arguments.global.php.dist` to
`application/autoload/dkplus-action-arguments.global.php`.

## ToDo

 - [x] Init named arguments
 - [x] Add Argument converter
 - [x] Support for BjyAuthorize, SpiffyAuthorize, ZfcRbac
 - [ ] Add tests for BjyAuthorize-, SpiffyAuthorize and ZfcRbac-Support
 - [ ] Find proper class names
 - [ ] Reduce code complexity
 - [ ] Clear cache via console.
