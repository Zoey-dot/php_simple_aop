[![Build Status](https://travis-ci.com/eeliu/php_simple_aop.svg?branch=master)](https://travis-ci.com/eeliu/php_simple_aop)

##  How to Use 

### Import from github

```Json
    "require": {
        "eeliu/php_simple_aop": "0.2.3"
    }
```

### Write your plugins

```php
/// Placing "///@hook:" here: aop on function(method) on before,end and Exception
///@hook:app\AppDate::output
class CommonPlugin
{
    //$apId: The function(method) name
    //$who: If watching a method, $who is that instance
    //$args: array parameters $argv = $args[0]
    public function __construct($apId,$who,&...$args){
        // $this->argv = $args[0];
        // $this->funName =$apId;
        // $this->instance = $who;
    }
    // watching before
    ///@hook:app\DBcontrol::connectDb
    public function onBefore(){

    }

    // watching after
    ///@hook:app\DBcontrol::getData1 app\DBcontrol::\array_push
    public function onEnd(&$ret){

    }

    // Exception
    ///@hook:app\DBcontrol::getData2
    public function onException($e){
    }
}
```

> Example

https://github.com/naver/pinpoint-c-agent/tree/v0.2.2/PHP/pinpoint_php_example/Plugins

### Activate plugins 

``` php
<?php

require_once __DIR__."/../vendor/autoload.php";

// A writable path for caching AOP code
define('AOP_CACHE_DIR',__DIR__.'/Cache/');                       
// Your plugins directory: All plugins must be have a suffix "Plugin.php",as "CommonPlugin.php mysqlPlugin.php RPCPlugin.php"
define('PLUGINS_DIR',__DIR__.'/../Plugins/');
// since 0.2.3 support user filter when loader a class.
// define('USER_DEFINED_CLASS_MAP_IMPLEMENT','\Plugins\ClassMapInFile.php');
// Use php_simple_aop auto_pinpointed.php instead of vendor/autoload.php
require_once __DIR__. '/../vendor/eeliu/php_simple_aop/auto_pinpointed.php';

```


### How it works

php_simple_aop wrappers your class with an onBefore/onEnd/onException suite

![how it works](https://raw.githubusercontent.com/naver/pinpoint-c-agent/master/images/principle_v0.2.x.png)

More detail please goto lib/test/Comparison

> If you found a bug, please create an issue to us without any hesitate.

> If it could help you, please give us a star as a support!  Thanks!

## Copyright

```
Copyright 2020 NAVER Corp.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```
