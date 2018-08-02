<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * user
 * 
 * @ORM\Table(name="auth_role")
 * @ORM\Entity
 **/
class Role {
    /** @ORM\Id @ORM\Column(type="guid") @ORM\GeneratedValue(strategy="UUID") */
    private $id;
    public function getId() {
        return $this->id;
    }
    
    /** @ORM\Column(type="string", length=255) */
    private $name;
    public function getName() {
        return $name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    
    /** @ORM\Column(type="integer") **/
    private $usergroup_id;
    public function getUsergroupId() {
        return $this->usergroup_id;
    }
    public function setUsergroupId($usergroup_id) {
        $this->usergroup_id = $usergroup_id;
    }
}
