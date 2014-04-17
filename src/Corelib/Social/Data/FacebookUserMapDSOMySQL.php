<?php
/**
 * MySQL implementation of FacebookUserMapDSOInterface
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\Social\Data;

/**
 * MySQL implementation of FacebookUserMapDSOInterface
 *
 * @since  2014-04-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class FacebookUserMapDSOMySQL extends \Corelib\Data\StoreMySQL implements \Corelib\Social\Data\FacebookUserMapDSOInterface
{

    /**
     * class constructor
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct(\PDO $dbWrite) {
        parent::__construct($dbWrite, '\Corelib\Social\Model\FacebookUserMapBO');
    } // __construct()

    /**
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function save(\Corelib\Social\Model\FacebookUserMapBO $item, $options = array()) {
        $options['processMap'] = array(
            'disabled' => array($this, 'boolean'),
            'locked' => array($this, 'boolean'),
        );
        $this->commonSave($item, 'facebook_user_map', $options);
    } // getById()

    /**
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function delete($id, $options = array()) {
        return $this->commonDelete($id, 'facebook_user_map',  $options);
    } // search()

} //  FacebookUserMapDAOMySQL class
