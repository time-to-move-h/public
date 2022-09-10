<?php
declare(strict_types=1);
namespace Moviao\Security;

/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author HD
 *
 */
class CSRF_Protect
{
    /**
     * The namespace for the session variable and form inputs
     * @var string
     */
    private $namespace;

    /**
     * Initializes the session variable name, starts the session if not already so,
     * and initializes the token
     * 
     * @param string $namespace
     */
    public function __construct(string $namespace = '_csrf')
    {
        $this->namespace = $namespace;
        $this->setToken();
    }

    /**
     * Return the token from persistent storage
     * 
     * @return string
     */
    public function getToken() : string
    {
        return $this->readTokenFromStorage();
    }

    /**
     * Verify if supplied token matches the stored token
     * 
     * @param string $userToken
     * @return boolean
     */
    public function isTokenValid(string $userToken) : bool
    {
        return ($userToken === $this->readTokenFromStorage());
    }

    /**
     * Echoes the HTML input field with the token, and namespace as the
     * name of the field
     */
    public function echoInputField() : string
    {
        $token = $this->getToken();
        return "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
    }

    /**
     * Verifies whether the post token was set, else dies with error
     */
    public function verifyRequest(string $token) : bool
    {
        if ($this->isTokenValid($token))
        {
            return true; //die("CSRF validation failed.");
        }
        return false;
    }

    /**
     * Generates a new token value and stores it in persisent storage, or else
     * does nothing if one already exists in persisent storage
     */
    private function setToken() : void
    {
        $storedToken = $this->readTokenFromStorage();

        if ($storedToken === '')
        {
            $token =  hash('sha256', uniqid((string) mt_rand(), true)); //md5(uniqid($rnd, TRUE)); //$rnd = (string) rand();
            $this->writeTokenToStorage($token);
        }
    }

    /**
     * Reads token from persistent sotrage
     * @return string
     */
    private function readTokenFromStorage() : string
    {
        if (isset($_SESSION[$this->namespace]))
        {
            return $_SESSION[$this->namespace];
        }
        else
        {
            return '';
        }
    }

    /**
     * Writes token to persistent storage
     */
    private function writeTokenToStorage(string $token) : void
    {
        $_SESSION[$this->namespace] = $token;
    }
}