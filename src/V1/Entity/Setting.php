<?php

namespace Alfenory\Auth\V1\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * setting
 * 
 * @ORM\Table(name="auth_setting")
 * @ORM\Entity
 **/
class setting {
    /** @ORM\Id @ORM\Column(type="guid") @ORM\GeneratedValue(strategy="UUID") */
    private $id;
    public function getId() {
        return $this->id;
    }

    /** @ORM\Column(type="string",length=255) */
    private $key;
    public function getKey() { return $this->key; }
    public function setKey($key) { $this->key = $key; }
    
    /** @ORM\Column(type="string",length=255) */
    private $value;
    public function getValue() { return $this->value; }
    public function setValue($value) { $this->value = $value; }
    
    
    /** @ORM\Column(type="string",length=255) */
    private $user_id;
    public function getUserId() { return $this->user_id; }
    public function setUserId($usergroup_id) { $this->user_id = $usergroup_id; }
    
}