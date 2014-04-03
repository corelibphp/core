<?php
/**
 * Builds social related classes
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social;

use Corelib\Database;

/**
 * Builds social related classes
 *
 * @since  2014-02-03
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class SocialFactory
{

    /**
     * Build and retreive facebook adapter
     *
     * @since  2014-02-03
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function getFacebook() {
        static $fb = null;
        if ($fb === null) {
            $configDAO = \Corelib\Config\ConfigFactory::getConfigDAO();
            $socialConfig = $configDAO->getElementEnvValuesById('social');

            $dataStore = \Corelib\DataStore\DataStoreFactory::getDataStoreByKey('session', array('namespace' => 'corelib-fb'));

            $fb = new Adapter\Facebook($socialConfig['facebook'], $dataStore);

        } //if

       return $fb; 
    } // getFacebook()


    /**
     * returns default implementation of Data\FacebookUserMapDAOInterface
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function getFacebookUserMapDAO() {

        $db = Database\DatabaseFactory::getPDO('read');

        $dao = new Data\FacebookUserMapDAOMySQL($db);

        $relationships = array(
            new Relationship(
                Relationship::TYPE_ONE_TO_ONE, 
                'user',
                \Corelib\User\UserFactory::getUserDAO()
            )
        );

        $dao->setRelationships($relationships);

        return $dao;
    } // getFacebookUserMapDAO()

    /**
     * returns default implementation of Data\FacebookUserMapDSOInterface
     *
     * @since  2014-03-08
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public static function getFacebookUserMapDSO() {
        $db = Database\DatabaseFactory::getPDO('write');

        $dso = new Data\FacebookUserMapDAOMySQL($db);

        return $dso;
    } // getFacebookUserMapDSO()

} //  SocialFactory class
