Corelib
=======

Corelib is mainly a Object Relational Mapper (ORM) for PHP. It focuses on flexibility and ease of use.

Example
--------

```
<?php
$userDAO = \Corelib\User\UserFactory::getUserDAO();
$userDSO = \Corelib\User\UserFactory::getUserDSO();

$userCollection = $userDAO->search("email LIKE '%@gmail.com' AND emailConfirmed = {$userDAO->boolean(true)}");

foreach ($userCollection as $userBO) {
    echo "{$userBO->getName()} <{$userBO->getEmail()}>";
    
    $userBO->setLocked(true);
    
    try {
        $userDSO->save($userBO);
    } catch (Exception $e)  {
        echo "Problem Saving User {$userBO->getName()";
    }
}
```

Installing
----------

Structures
----------

Corelib has several structures it uses here are the main ones.

* Models

    Objects that hold data they represent a single instance.

* Collections

    Objects that hold several Models of a given type.

* Data Access Objects

    Objects used to read and retrieve objects using an SQL like syntax.

* Data Store Objects

    Objects used to persist Models to a data store.

* Relationships

    Objects that define how Models are related to each other.
