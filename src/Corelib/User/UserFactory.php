<?php
/**
 * Builds user related classes
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User;

use Corelib\Database;

/**
 * Builds user related classes
 *
 * @since  2014-03-08
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class UserFactory
{
    /**
     * returns default implementation of Data\UserDAOInterface
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getUserDAO() {

        $db = Database\DatabaseFactory::getPDO('read');

        $dao = new Data\UserDAOMySQL($db);

        return $dao;
    } // getUserDAO()

    /**
     * returns default implementation of Data\UserDSOInterface
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function getUserDSO() {
        $db = Database\DatabaseFactory::getPDO('write');

        $dso = new Data\UserDSOMySQL($db);

        return $dso;
    } // getUserDSO()

} //  UserFactory class
