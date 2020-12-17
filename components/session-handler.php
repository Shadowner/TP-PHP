<?php

    class SessionH {
        public static $mySession;
        public $sessionStarted = false;
        public $sessionID;

        function startNewSession($id=null){
            if($this->sessionStarted)return;
            if ($id) session_id($id);
            $this->sessionStarted=session_start();
            $this->sessionID = session_id();
        }

        function addToSession($param, $obj){
            if (!$this->sessionStarted) return;
            if(!array_key_exists($param, $_SESSION) or !is_array($_SESSION[$param])) $_SESSION[$param] = [];
            $_SESSION[$param][md5(json_encode($obj))] = $obj;
        }

        function getFromSession($param) {
            if (!$this->sessionStarted) return;
            if (!isset($_SESSION[$param]) or !is_array($_SESSION[$param])) return [];
            if($param ==="*")return $_SESSION;
            return unserialize(serialize($_SESSION[$param]));
        }

        function removeFromSession($param, $obj){
            if (!$this->sessionStarted) return;

            //Euh alors je trouverais une solution plus tard ;)
            $_SESSION[$param][md5(json_encode($obj))] = null;
            $_SESSION[$param] = array_filter($_SESSION[$param], function($a){return $a ? true:false;});
            // J'ai trouvé mais c'est peut-être un peut overkill
            // Bien sûr cette solution ne permet pas de gérer les stocks
            // Mais par chance nous n'avons pas à les gérers dans ce TP 
        }

        function endSession(){
            if (!$this->sessionStarted) return;
            session_unset();
            session_reset();
            session_destroy();
            $this->sessionStarted = false;
            echo ("Destroying the magnifique session");
        }
    }
    SessionH::$mySession = new SessionH();
    SessionH::$mySession->startNewSession();
?>
