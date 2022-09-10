<?php
declare(strict_types=1);
namespace Moviao\Http\Route;
class Request {
    private $attributes = null;    
    private $parameters = null;    
    public function __construct(array $attributes, array $parameters) {
        $this->attributes = $attributes;
        $this->parameters = $parameters;
    }            
    public function getAttribute(string $name) : ?string {
        if (null !== $this->attributes && isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }
    public function getAttributes() : array {
        return $this->attributes;
    }
    public function getParameter(string $name)  : ?string {
        if (null !== $this->parameters && isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }   
    public function getParameters() : array{
        return $this->parameters;
    }        
}