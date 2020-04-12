<?php

namespace Application\Model\Helper;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Sql;

class ApplicationHelper
{
    protected $_adapter;

    /**
     * Make the Adapter object available as local protected variable
     *
     * @param Adapter $adapter - DB PDO PgSQL conn
     */
    public function __construct(Adapter $adapter)
    {
        $this->_adapter = $adapter;
    }
}
