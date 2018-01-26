<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - MongoDbPHP5.php
 * Author:    WuHui
 * Date:      2017-08-30 16:09
 * Desc:      MongoDbPHP5 类
 *            参考: http://www.runoob.com/mongodb/mongodb-php.html
 *******************************************************************************/

namespace Engine\DB;

use \mongo;

class MongoDbPHP5
{

    protected $mongo;
    protected $collection;

    /**
     * MongoDbPHP5 constructor.
     * @param  $info
     * @throws \Exception
     */
    public function __construct($info)
    {
        $this->mongo = $this->instanceMongo($info);

        if (isset($info['db'], $info['collection'])) {
            $this->collection = $this->selectCollection($info['collection'], $info['db']);
        }

        return $this->mongo;
    }

    public function instanceMongo($info)
    {
        $connect_info = $this->getConnectionInfo($info);
        return new mongo($connect_info);
    }

    public function getConnectionInfo($info)
    {
        $connect_string = 'mongodb://%s:%s';

        $info['host'] = empty($info['host']) ? '127.0.0.1' : $info['host'];
        $info['port'] = empty($info['port']) ? '27017' : $info['port'];

        return sprintf($connect_string, $info['host'], $info['port']);
    }

    /**
     * @param  $collection
     * @param  $dbs
     * @param  null $mongo
     * @return \MongoCollection
     * @throws \Exception
     */
    public function selectCollection($collection, $dbs, $mongo = NULL)
    {
        $mongo = $mongo == NULL ? $this->mongo : $mongo;

        return $mongo->selectCollection($dbs, $collection);
    }

    public function checkUpdateArray($info)
    {
        if (count($info) < 1) {

            return false;

        }

        return array('$set' => $info);
    }

    public function infoToString($info)
    {
        foreach ($info as &$fields) {

            $fields = strval($fields);

            if (!json_encode($fields)) {
                $fields = '';
            }

        }

        return $info;
    }
}