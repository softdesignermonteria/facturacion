<?php

class AccessList extends ActiveRecord{
	
	
	protected $id;
	protected $role;
	protected $resource;
	protected $action;
	protected $allow;
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getRole(){
		return $this->role;
	}
	
	public function setRole($role){
		$this->role = $role;
	}
	
	
	public function getResource(){
		return $this->resource;
	}
	
	public function setResource($resource){
		$this->resource = $resource;
	}
	
	
	public function getAction(){
		return $this->action;
	}
	
	public function setAction($action){
		$this->action = $action;
	}
	
	public function getAllow(){
		return $this->allow;
	}
	
	public function setAllow($allow){
		$this->allow = $allow;
	}
	


}

?>