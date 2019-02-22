<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Annotation as ORM;

/**
 * user
 * 
 * @ORM\Table(name="auth_user")
 * @ORM\Entity
 **/
class User implements \JsonSerializable {
    /** @ORM\Id @ORM\Column(type="guid") */
    private $id;
    public function getId() {
        return $this->id;
    }

    /** @ORM\Column(type="string",length=255) */
    private $salt;
    public function getSalt() { return $this->salt; }
    public function setSalt($salt) { $this->salt = $salt; }
    
    /** @ORM\Column(type="string",length=255) */
    private $username;
    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; }
    
    /** @ORM\Column(type="string",length=255) */
    private $password;
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    
    /** @ORM\Column(type="string",length=255) */
    private $email;
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    /** @ORM\Column(type="integer") */
    private $salutation;
    public function getSalutationn() { return $this->salutation; }
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
    private $securecode;
    public function getSecurecode() { return $this->securecode; }
    public function setSecurecode($securecode) { $this->securecode = $securecode; }
    
    /** @ORM\Column(type="datetime") */
    private $securecode_created;
    public function getSecurecodeCreated() { return $this->securecode_created; }
    public function setSecurecodeCreated($securecode_created) { $this->securecode_created = $securecode_created; }
    
    /** @ORM\Column(type="smallint") */
    private $active;
    public function getActive() { return $this->active; }
    public function setActive($active) { $this->active = $active; }
    
    public function get_password($salt, $password) {
        return hash("sha512", $salt . $password, false);
    }

    public function initSalt() {
        $this->salt = \Alfenory\Auth\V1\Lib\Guid::guid(); 
    }

    function __construct() {
        $this->id = \Alfenory\Auth\V1\Lib\Guid::guid(); 
    }

    public function check_password($password) {
        global $entityManager;
        $salt = $this->salt;
        if ($salt == "" || $salt == null) {
            if ($this->password == md5($password)) {
                $this->salt = \Alfenory\Auth\V1\Guid::guid();
                $this->password = $this->get_password($this->salt, $password);
                $entityManager->persist($this);
                $entityManager->flush();
                return true;
            }
        } else {
            if ($this->get_password($this->salt, $password) == $this->password) {
                return true;
            }
        }
        return false;
    }

    public function get_email_salutation() {
        if ($this->salutation * 1 === 1) {
            return "Hallo Herr " . $this->getLastname() . "!";
        }
        return "Hallo Frau " . $this->getLastname() . "!";
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);
        unset($vars["securecode"]);
        unset($vars["securecode_created"]);
        unset($vars["salt"]);
        unset($vars["password"]);
        return $vars;
    }

}
