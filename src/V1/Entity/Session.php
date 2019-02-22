<?php
namespace Alfenory\Auth\V1\Entity;
use Doctrine\ORM\Annotation as ORM;

/**
 * session
 * 
 * @ORM\Table(name="auth_session")
 * @ORM\Entity
 **/
class Session {
    /** @ORM\Id @ORM\Column(type="guid") */
    private $id;
    public function getId() {
        return $this->id;
    }
    
    /** @ORM\Column(type="string") **/
    private $user_id;
    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    
    /** @ORM\Column(type="datetime") **/
    private $duetime;
    public function getDuetime() { return $this->duetime; }
    public function setDuetime($duetime) { $this->duetime = $duetime; }
    
    function __construct() {
        $this->duetime = new \DateTime("now");
        $this->id = \Alfenory\Auth\V1\Lib\Guid::guid(); 
    }
    
    public static function create_session($user_id) {
        global $entityManager;
        $session = new session();
        $session->setUserId($user_id);
        $session->setDuetime(new \DateTime("now"));
        
        $entityManager->persist($session);
        $entityManager->flush();
                
        return $session->getId(); 
    }
    
    public function is_alive() {
        $date1 = new \DateTime();
        $date1->add( new \DateInterval('PT3600S'));
        $diff = $this->getDuetime()->getTimestamp() - $date1->getTimestamp();
        if($diff - 3600 < 0) {
            return true;
        }
        return false;
    }
    
    public static function get_session($session_id) {
        global $entityManager;
        $sessions = $entityManager->getRepository("\Alfenory\Auth\V1\Entity\Session")->findBy(array('id' => $session_id));
        if(count($sessions) > 0) {
            return $sessions[0]; 
        }
        return null;
    }
    
    public function update_session() {
        global $entityManager;
        $this->setDuetime(new \DateTime());
        $entityManager->persist($this);
        $entityManager->flush();
    }   
}