<?php

namespace Alfenory\Auth\V1\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * user
 * 
 * @ORM\Table(name="auth_user")
 * @ORM\Entity
 **/
class User {
    /** @ORM\Id @ORM\Column(type="guid") @ORM\GeneratedValue(strategy="UUID") */
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
    
    function get_guid() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function get_password($salt, $password) {
        return hash("sha512", $salt . $password, false);
    }

    public function check_password($password) {
        global $entityManager;
        $salt = $this->salt;
        if ($salt == "" || $salt == null) {
            if ($this->password == md5($password)) {
                $this->salt = $this->get_guid();
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

    public static function login($username, $password) {
        $user = user::get_from_instance(new user(), array("username", "active"), array($username, "1"));
        if ($user->check_password($password)) {
            return session::create_session($user->id);
        } else {
            return null;
        }
    }

    public static function get_user_with_securecode($securecode) {
        global $config;
        $user = user::get_from_instance(new user(), array("securecode"), array($securecode));
        if ($user === null) {
            return null;
        }
        $date = strtotime($user->securecode_created);
        $date1 = strtotime(date("Y-m-j G:i:s"));
        $diff = $date1 - $date;
        if ($diff - $config["confirmation_timeout"] * 1 < 0) {
            return $user;
        }
        return null;
    }

    public static function get_user_with_username($username) {
        return user::get_from_instance(new user(), array("username"), array($username));
    }

    public static function get_user_with_email($email) {
        return user::get_from_instance(new user(), array("email"), array($email));
    }

    public static function logout($session_id) {
        $session = session::get_from_instance(new session(), array("session_id"), array($session_id));
        $session->duetime = date("Y-m-j G:i:s", strtotime("-60 minutes", time()));
        $session->update();
    }

    public static function get_user($session_id) {
        global $entityManager;
        $session = session::get_session($session_id);
        if ($session->is_alive()) {
            $users = $entityManager->getRepository('user')->findBy(array('id' => $session->getUserId()));
            if(count($users) > 0) {
                return $users[0]; 
            }
        } 
        return null;
    }

    public static function get_user_from_usergroup($usergroup_id) {
        return user::get_list_from_instance(new user(), array("usergroup_id"), array($usergroup_id));
    }

    public static function get_user_wrapper_from_usergroup($usergroup_id) {
        $list = user::get_user_from_usergroup($usergroup_id);
        $wrapper_list = array();
        for ($i = 0; $i < count($list); $i++) {
            $wrapper = $list[$i]->to_wrapper();
            $wrapper->plattform_access_list = plattform_access::get_from_user_id($list[$i]->id);
            $wrapper->plattform_list = plattform::get_list_from_instance(new plattform());
            $wrapper_list[] = $wrapper;
        }
        return $wrapper_list;
    }

    public function to_wrapper() {
        return new user_wrapper($this);
    }

    public function get_email_salutation() {
        if ($this->salutation * 1 === 1) {
            return "Hallo Herr " . $this->last_name . "!";
        }
        return "Hallo Frau " . $this->last_name . "!";
    }

    public function get_open_link() {
        return "Klicken Sie auf den Link oder für den Fall, dass dies nicht funktioniert, kopieren Sie den Link in die Adressleiste Ihres Browsers.";
    }

    public function get_welcome_content() {
        global $config;
        return "<b>Willkommen auf " . $config["title_add"] . " " . $config["title"] . "!</b> Jetzt sind es nur noch wenige Schritte bis zur " . $config["description"] . ".<br/><br/>Um mit der Plattform arbeiten zu können, müssen Sie Ihren Zugang innerhalb von <b>24 h</b> bestätigen - in dem Sie folgenden Link in Ihrem Browser öffnen. " . $this->get_open_link();
    }

    public function get_reset_password_content() {
        global $config;
        return "<b>Neues Passwort setzen für " . $config["title_add"] . " " . $config["title"] . "!</b><br/><br/>Um ein neues Passwort für die Plattform zu setzen, müssen Sie innerhalb von 24 h den folgenden Link im Browser öffnen. " . $this->get_open_link();
    }

    public function get_setpassword_link() {
        global $config;
        return $config["plattform_url"] . "/API.php?object=user&action=confirm&p=false&securecode=" . $this->securecode;
    }

    public function get_resetpassword_link() {
        global $config;
        return $config["plattform_url"] . "/API.php?object=user&action=confirm&p=true&securecode=" . $this->securecode;
    }

    public function get_confirmemail_content_html() {
        global $config;
        $emailtext = "<p><b>" . $this->get_email_salutation() . "</b></p>";
        $emailtext .= "<p>" . $this->get_welcome_content() . "</p>";
        $url = $this->get_setpassword_link();
        $emailtext .= "<p><a href=\"$url\">$url</a></p>";
        $emailtext .= "<p>Sie gelangen automatisch auf die Anmeldeseite.</p>";
        $emailtext .= "<p>Bitte vergeben Sie zuerst ein individuelles Passwort und bestätigen Sie dieses. Danach werden Sie gebeten einen Benutzernamen und Ihr neues Passwort für die Anmeldung einzugeben.</p>";
        $emailtext .= "<p>Ihr Benutzername lautet: <b>" . $this->username . "</b></p>";
        $emailtext .= "<p>Nach der ersten Anmeldung haben Sie die Möglichkeit über die „Passwort vergessen“ Funktion Ihr Passwort jederzeit zu ändern. Die Nutzungsbedingungen müssen vor der ersten Fallbearbeitung von Ihnen bestätigt werden.</p>";
        $emailtext .= "<p>Wir wünschen Ihnen viel Erfolg mit der Vergleichsplattform. Für Fragen im Zusammenhang mit der Plattform stehen wir Ihnen gerne zur Verfügung.</p>";
        $emailtext .= "<p>" . str_replace("\n", "<br/>", $config["email"]["footer"]) . "</p>";
        return $emailtext;
    }

    public function get_reset_password_content_html() {
        global $config;
        $emailtext = "<p><b>" . $this->get_email_salutation() . "</b></p>";
        $emailtext .= "<p>" . $this->get_reset_password_content() . "</p>";
        $url = $this->get_resetpassword_link();
        $emailtext .= "<p><a href=\"$url\">$url</a></p>";
        $emailtext .= "<p>" . str_replace("\n", "<br/>", $config["email"]["footer"]) . "</p>";
        return $emailtext;
    }
    
    public function get_usergroup_membership() {
        global $config, $entityManager;
        
    }

}
