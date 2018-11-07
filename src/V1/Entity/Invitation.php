<?php

namespace Alfenory\Auth\V1\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * invitation
 * 
 * @ORM\Table(name="auth_invitation")
 * @ORM\Entity
 **/
class Invitation implements \JsonSerializable {
    /** @ORM\Id @ORM\Column(type="guid") @ORM\GeneratedValue(strategy="UUID") */
    private $id;
    public function getId() {
        return $this->id;
    }

    /** @ORM\Column(type="datetime") */
    private $creationdate;
    public function getCreationdate() { return $this->creationdate; }
    public function setCreationdate($creationdate) { $this->creationdate = $creationdate; }

    /** @ORM\Column(tye="string", length=255) */
    private $username;
    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; }


    /** @ORM\Column(type="string",length=255) */
    private $email;
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    /** @ORM\Column(type="integer") */
    private $salutation;
    public function getSalutation() { return $this->salutation; }
    public function setSalutation($salutation) { $this->salutation = $salutation; }
    
    /** @ORM\Column(type="string",length=255) */
    private $firstname;
    public function getFirstName() { return $this->firstname; }
    public function setFirstName($first_name) { $this->firstname = $first_name; }
    
    /** @ORM\Column(type="string",length=255) */
    private $lastname;
    public function getLastName() { return $this->lastname; }
    public function setLastName($last_name) { $this->lastname = $last_name; }

    /** @ORM\Column(type="string",length=255) */
    private $role_id;
    public function getRoleId() { return $this->role_id; }
    public function setRoleId($role_id) { $this->role_id = $role_id; }

    /** @ORM\Column(type="string",length=255) */
    private $usergroup_id;
    public function getUsergroupId() { return $this->usergroup_id; }
    public function setUsergroupId($usergroup_id) { $this->usergroup_id = $usergroup_id; }

    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}