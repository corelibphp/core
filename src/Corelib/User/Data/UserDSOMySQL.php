<?php
/**
 * MySQL implementation of UserDSOInterface
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */

namespace Corelib\User\Data;

/**
 * MySQL implementation of UserDSOInterface
 *
 * @since  2014-03-06
 * @author Patrick Forget <patforg@geekpad.ca>
 */
class UserDSOMySQL extends \Corelib\Data\StoreMySQL implements \Corelib\User\Data\UserDSOInterface
{

    /**
     * class constructor
     *
     * @since  2014-04-16
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function __construct(\PDO $dbWrite) {
        parent::__construct($dbWrite, '\Corelib\User\Model\UserBO');
    } // __construct()


    /**
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function save(\Corelib\User\Model\UserBO $user, $options=array()) {
        $options['processMap'] = array(
            'disabled' => array($this, 'boolean'),
            'locked' => array($this, 'boolean'),
        );
        $this->commonSave($user, 'user', $options);
    } //save()

    /**
     * @since  2014-03-06
     * @author Patrick Forget <patforg@geekpad.ca>
     */
    public function delete($id, $options=array()) {
        $this->commonDelete($id, 'user', $options);
    } //delete()
} //  UserDSOMySQL class
