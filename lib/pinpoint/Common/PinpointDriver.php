<?php
/**
 * Copyright 2019 NAVER Corp.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 5:14 PM
 */

namespace pinpoint\Common;
use pinpoint\Common\OrgClassParse;
use pinpoint\Common\AopClassLoader;
use pinpoint\Common\ClassMap;
use pinpoint\Common\PluginParser;

class PinpointDriver
{
    protected static $instance;
    protected $clAr;
    protected $classMap;

    /**
     * @return mixed
     */
    public function getClassMap()
    {
        return $this->classMap;
    }

    public static function getInstance(){

        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->clAr = [];
    }

    public function init()
    {
        /// checking the cached file exist, if exist load it
        if(file_exists(AOP_CACHE_DIR.'/__class_index_table' ))
        {
            $this->classMap =  new ClassMap(AOP_CACHE_DIR.'/__class_index_table');
            AopClassLoader::init($this->classMap->classMap);
            return ;
        }

        /// scan user plugins which suffix is Plugin.php
        $this->classMap =  new ClassMap();
        $pluFiles = glob(PLUGINS_DIR."/*Plugin.php");
        $pluParsers = [];
        foreach ($pluFiles as $file)
        {
            $pluParsers[] = new PluginParser($file,$this->clAr);
        }

        foreach ($this->clAr as $cl=> $info)
        {
            $fullPath = Util::findFile($cl);
            if(!$fullPath)
            {
                continue;
            }
//            echo "$cl -> $fullPath \n";
            $osr = new OrgClassParse($fullPath,$cl,$info);
            foreach ($osr->classIndex as $clName=>$path)
            {
                $this->classMap->insertMapping($clName,$path);
            }
        }

//        $this->classMap->persistenceClassMapping(AOP_CACHE_DIR.'/__class_index_table');

        AopClassLoader::init($this->classMap->classMap);

    }


}
