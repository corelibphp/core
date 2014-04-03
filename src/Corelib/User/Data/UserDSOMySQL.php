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
