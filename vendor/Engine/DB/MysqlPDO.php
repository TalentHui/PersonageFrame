<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - MysqlPDO.php
 * Author:    WuHui
 * Date:      2017-08-30 17:30
 * Desc:      MySQL PDO
 *            导出库：mysqldump -u root -p 库名 > 文件名.sql
 *
 *            导入库：mysqldump -u root -p 库名 > 文件名.sql
 *                  导入库的时候需要先建立库 -> create database 库名；
 *
 *            查看当前所在库： select database();
 *
 *            清空表数据:  TRUNCATE TABLE '表名';   notice: 相当于重置
 *******************************************************************************/

namespace Engine\DB;


class MysqlPDO
{
    /** @var string 选择的连接类型 */
    protected $connect_sql_type = 'mysql';

    /** @var string 连接的主机地址 */
    protected $connect_host_address = '127.0.0.1';

    /** @var string 选择的主机端口 */
    protected $connect_host_port = '3306';

    /** @var string 连接主机的用户 */
    protected $connect_host_user = '';

    /** @var string 连接主机的密码 */
    protected $connect_host_pwd = '';

    /** @var string 连接库名 */
    protected $connect_db_name = '';

    /** @var string 连接库的编码 */
    protected $connect_db_charset = 'utf8';

    /** @var string dsn模板 */
    protected $dsn_model = '%s:host=%s;port=%s;dbName=%s;';

    /** @var array PDO对象的opt属性 */
    protected $pdo_opt_attr = array();

    /** @var  \PDO */
    protected $PDO;

    /** @var  \PDOStatement */
    protected $PDOStatement;

    /** @var bool PDO链接状态 默认 false; true 链接成功 false 链接失败 */
    protected $PDO_connect_status = false;

    /** @var array PDO链接错误信息 */
    protected $pdo_connect_error_detail = array();

    public function __construct(array $sql_connect_conf = array())
    {
        $this->setBaseConnectInfo($sql_connect_conf);
        $this->makePdoConnect();
    }

    /**
     * @desc  设置基础连接信息
     * @param array $sql_connect_conf
     */
    protected function setBaseConnectInfo(array $sql_connect_conf = array())
    {
        if (!empty($sql_connect_conf['sql_type'])) {
            $this->connect_sql_type = $sql_connect_conf['sql_type'];
        }

        if (!empty($sql_connect_conf['host'])) {
            $this->connect_host_address = $sql_connect_conf['host'];
        }

        if (!empty($sql_connect_conf['port'])) {
            $this->connect_host_port = $sql_connect_conf['port'];
        }

        if (!empty($sql_connect_conf['db_name'])) {
            $this->connect_db_name = $sql_connect_conf['db_name'];
        }

        if (!empty($sql_connect_conf['user'])) {
            $this->connect_host_user = $sql_connect_conf['user'];
        }

        if (!empty($sql_connect_conf['pwd'])) {
            $this->connect_host_pwd = $sql_connect_conf['pwd'];
        }

        if (!empty($sql_connect_conf['charset'])) {
            $this->connect_db_charset = $sql_connect_conf['charset'];
        }
    }

    /**
     * @desc 获取dsn模板
     */
    protected function getDsnModel()
    {
        return sprintf(strtolower($this->dsn_model), $this->connect_sql_type, $this->connect_host_address, $this->connect_host_port, $this->connect_db_name);
    }

    /**
     * @desc  创建PDO持久链接
     */
    public function setPdoConnectKeepAlive()
    {
        $this->pdo_opt_attr[\PDO::ATTR_PERSISTENT] = true;
    }

    /**
     * @desc  建立PDO链接
     */
    protected function makePdoConnect()
    {
        try {
            $get_dsn_conf = $this->getDsnModel();
            $this->PDO = new \PDO($get_dsn_conf, $this->connect_host_user, $this->connect_host_pwd, $this->pdo_opt_attr);

            /** 设置字符集 */
            $this->pdoQuery(sprintf('set names %s', $this->connect_db_charset));
            $this->PDO_connect_status = true;
        } catch (\Exception $e) {

            $this->pdo_connect_error_detail = array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
    }

    /**
     * @desc   获取PDO的连接状态 true 成功 false 失败
     * @return bool
     */
    public function getPdoConnectStatus()
    {
        return $this->PDO_connect_status;
    }

    /**
     * @desc   获取PDO的错误信息
     * @return array
     */
    public function getPdoConnectErrorDetail()
    {
        return $this->pdo_connect_error_detail;
    }

    /**
     * @desc 事务 - 开启PDO的事务
     */
    public function pdoTransactionOpen()
    {
        $this->PDO->beginTransaction();
    }

    /**
     * @desc 事务 - 提交PDO的事务
     */
    public function pdoTransactionCommit()
    {
        $this->PDO->commit();
    }

    /**
     * @desc 事务 - 关闭PDO的事务
     */
    public function pdoTransactionClose()
    {
        $this->PDO->rollBack();
    }

    /**
     * @desc  在一个单独的函数调用中执行一条 SQL 语句，返回受此语句影响的行数。
     * @param string $sql
     * @return int
     */
    public function pdoExec($sql = '')
    {
        return $this->PDOStatement =  $this->PDO->exec($sql);
    }

    /**
     * @desc  返回PDOStatement对象,可以理解为结果集，成功返回PDOStatement对象，如果失败返回 FALSE
     * @param $sql
     * @return \PDOStatement | false
     * @notice 结果集当作二维数组，遍历输出
     */
    public function pdoQuery($sql)
    {
        return $this->PDOStatement = $this->PDO->query($sql);
    }

    /**
     * @desc 获取跟数据库句柄上一次操作相关的 SQLSTATE
     */
    public function pdoLastSqlErrCode()
    {
        return $this->PDO->errorCode();
    }

    /**
     * @desc 返回最后一次操作数据库的错误信息
     */
    public function pdoLastSqlErrInfo()
    {
        return $this->PDO->errorCode();
    }

    /**
     * @desc 返回一个表示最后插入数据库那一行的行ID的字符串
     * @return string
     */
    public function getPdoLastInsertId()
    {
        return $this->PDO->lastInsertId();
    }

    /**
     * @desc   预处理 - 添加一条预处理语句
     * @param  $sql
     * @return \PDOStatement 如果成功，PDO::prepare()返回PDOStatement对象，如果失败返回 FALSE 或抛出异常 PDOException
     */
    public function preparePdoSql($sql)
    {
        return $this->PDOStatement = $this->PDO->prepare($sql);
    }

    /**
     * @desc   预处理 - 执行预处理语句
     * @param  array $replace_data
     * @return bool 成功时返回 true，失败时返回 false
     * @notice 建议使用如下类型:
     *         'SELECT name, colour, calories FROM fruit WHERE calories < :calories AND colour = :colour'
     *         array(':calories' => $calories, ':colour' => $colour)
     */
    public function preparePdoSqlExecute(array $replace_data = array())
    {
        return $this->PDOStatement->execute($replace_data);
    }

    /**
     * @desc   预处理 - 打印一条 SQL 预处理命令
     * @notice 没有返回值。直接输出
     */
    public function preparePdoSqlParams()
    {
        $this->PDOStatement->debugDumpParams();
    }

    /**
     * @desc   预处理 - 返回结果集中的列数
     * @return int  返回由 PDOStatement 对象代表的结果集中的列数。如果没有结果集，则 PDOStatement::columnCount() 返回 0。
     */
    public function preparePdoSqlColumnCount()
    {
        return $this->PDOStatement->columnCount();
    }

    /**
     * @desc   预处理 - 关闭游标，使语句能再次被执行
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE。
     * @notice $stmt PDOStatement 对象返回多行，但应用程序只取第一行，让 PDOStatement 对象处于一个有未取行的状态(fetch)。
     *         为确保应用程序对所有数据库驱动都能正常运行，在执行 $otherStmt PDOStatement 对象前，$stmt 调用一次
     *         PDOStatement::closeCursor()
     */
    public function preparePdoSqlCloseCursor()
    {
        return $this->PDOStatement->closeCursor();
    }

    /**
     * @desc   预处理 - 获取预处理的错误码
     * @return string
     */
    public function getPreparePdoSqlErrCode()
    {
        return $this->PDOStatement->errorCode();
    }

    /**
     * @desc   预处理 - 获取预处理的错误信息
     * @return array
     */
    public function getPreparePdoSqlErrInfo()
    {
        return $this->PDOStatement->errorInfo();
    }

    /**
     * @desc   预处理 - 返回受上一个 SQL 语句影响的行数
     * @return int 返回行数。
     */
    public function getPreParePdoSqlEffectRows()
    {
        return $this->PDOStatement->rowCount();
    }

    /**
     * @desc   从结果集中获取下一行[第一行，一位数组]
     * @param  int $rel_type 1 索引 2 关联 3 索引和关联
     * @return mixed
     */
    public function getPdoSqlRelFetch($rel_type = 2)
    {
        $rel_type_list = array(
            '1' => \PDO::FETCH_NUM,
            '2' => \PDO::FETCH_ASSOC,
            '3' => \PDO::FETCH_BOTH,
        );

        return $this->PDOStatement->fetch($rel_type_list[$rel_type]);
    }

    /**
     * @desc   返回一个包含结果集中所有行的数组 [二维]
     * @param  int $rel_type 1 索引 2 关联 3 索引和关联
     * @return array
     */
    public function getPdoSqlRelFetchAll($rel_type = 2)
    {
        $rel_type_list = array(
            '1' => \PDO::FETCH_NUM,
            '2' => \PDO::FETCH_ASSOC,
            '3' => \PDO::FETCH_BOTH,
        );

        return $this->PDOStatement->fetchAll($rel_type_list[$rel_type]);
    }
}