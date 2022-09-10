<?php
declare(strict_types=1);
namespace Moviao\Session;
class SessionClass {
    public function __construct(string $name) {
        if ($this->is_session_started() === true && ! empty($name)) {
            $previous_name = session_name($name);                    
        }    
    }
    public function startSession(): void {
        if (!session_id()) {
            session_start();
        }
    }
    public function startSession_id(string $id): void {
        session_id($id);
        $this->startSession();
    }
    public function sessionID(): string {
        return session_id();    
    }
    public function sessionName(): string {
        return session_name();    
    }
    public function sessionExist(string $name) : bool {
        return isset($_SESSION[$name]);
    }
    public function getData(string $name) {
        return $_SESSION[$name] ?? null;
    }
    public function setData(string $name,$value) : void {
        //if (isset($_SESSION[$name])) {
            $_SESSION[$name] = $value;    
        //}
    }
    public function stopSession(): void {
        session_write_close();    
    }
    public function destroySession(): void {
        session_unset();
        session_destroy();    
    }
    public function is_session_started() : bool {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}