<?php

namespace Application\Model;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Sql;

class AdminMapper extends AbstractActionController
{
    protected $_adapter;
    protected $_serviceLocator;

    /**
     * Make the Adapter object avilable as local protected variable
     *
     * @param Adapter $adapter - DB PDO PgSQL conn
     */
    public function __construct(Adapter $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Function used to get instance of DB
     *
     * @return instance
     */
    public function dbConnection()
    {
        return $this->getServiceLocator()->get('db')->getDriver()->getConnection();
    }


    /**
     * @return result object
     */
    public function userLogin($dataArr)
    {
        $conn = $this->dbConnection();
        $uName = $dataArr['u_name'];
        $password = $dataArr['upw'];
        $password = htmlspecialchars($password, ENT_QUOTES);
        echo $password;
        exit();
        $sql = new Sql($this->_adapter);
        $select = $sql->select();
        $select->from('settings')->where(["status" => "Yes"]);
        $newadpater = $this->_adapter;
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $newadpater->query($selectString, $newadpater::QUERY_MODE_EXECUTE);
        $arr = [];
        $resultSet = new ResultSet();
        $resultSet->initialize($results);
        $resultSet->buffer();
        $count = $resultSet->count();
        if ($count > 0) {
            $arr = $resultSet->toArray();
            $resultSArr = $arr[0];
            $pwd_iteartions = $resultSArr['password_iterations'];
            $pwd_iteartion_length = $resultSArr['password_iteration_length'];
            //echo $ds;die;
            $errMessage = "";
                $queryClients = "SELECT user. * , user_role_tracker.group_id, `group`.group_name
                FROM user
                LEFT JOIN user_role_tracker ON user.u_id = user_role_tracker.u_id
                LEFT JOIN `group` ON `group`.group_id = user_role_tracker.group_id
                WHERE user.u_name = ? and user.status=?";
                $uName = htmlspecialchars($uName, ENT_QUOTES);
                $statements = $this->_adapter->createStatement($queryClients, [$uName,'Active']);
                $results = $statements->execute();
                $arr = [];
                $resultSet = new ResultSet();
                $resultSet->initialize($results);
                $resultSet->buffer();
                $count = @$resultSet->count();
                $resultsArr = [];
            if ($count > 0) {
                 $arr = $resultSet->toArray();
                if ($arr[0]['user_password'] == $this->createHash($password, $arr[0]['user_salt'], $pwd_iteartions, $pwd_iteartion_length)) {
                    session_start();
                    $udetailsArr = [];
                    $udetailsArr['u_id'] = $arr[0]['u_id'];
                    $udetailsArr['u_realname'] = $arr[0]['u_realname'];
                    $udetailsArr['u_name'] = $arr[0]['u_name'];
                    $udetailsArr['email'] = $arr[0]['email'];
                    $udetailsArr['group_id'] = $arr[0]['group_id'];
                    $udetailsArr['group_name'] = $arr[0]['group_name'];
                    $udetailsArr['user_type'] = $arr[0]['user_type'];
                    $_SESSION['user_details'] = $udetailsArr;
                    $_SESSION['u_id'] = $arr[0]['u_id'];
                    $_SESSION['u_realname'] = $arr[0]['u_realname'];
                    $_SESSION['user_type'] = $arr[0]['user_type'];
                    $this->accessTrackerGroups($arr[0]['u_id'], $arr[0]['group_id'], $arr[0]['user_type']);
                    $responseCode = 200;
                    $errMessage = "Login Success";
                } else {
                    $responseCode = 400;
                    $errMessage = 'Enter Correct Password';
                }
            } else {
                $responseCode = 400;
                $errMessage = "Please Contact Administrator";
            }
        } else {
            $responseCode = 400;
            $errMessage = 'Invalid User';
        }
        $resultsArr['statusCode'] = $responseCode;
        $resultsArr['responseMessage'] = $errMessage;
        return $resultsArr;
    }


    public function userLogout()
    {
    }
    public function createHash($password, $salt, $iterations, $length)
    {
        return bin2hex(hash_pbkdf2("sha256", $password, $salt, $iterations, $length, true));
    }
    public function getConfigData($scopeLevel = 'Global', $scopeId = null)
    {
        $query = "SELECT config_key,config_value FROM `config` WHERE scope_level = '$scopeLevel'";
        if ($scopeLevel != 'Global') {
            $query .= " AND scope_id = $scopeId";
        }
        $statements = $this->_adapter->createStatement($query);
        $results = $statements->execute();
        $arrGroupsSession = [];
        $resultSet = new ResultSet();
        $resultSet->initialize($results);
        $resultSet->buffer();
        $count = $resultSet->count();
        if ($count > 0) {
            $arr = $resultSet->toArray();
        }
        $res = array_column($arr, 'config_value', 'config_key');
        return $res;
    }
}
