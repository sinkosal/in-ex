<?php

/**
 * database loadable object
 * - useful starting point for objects that are loadable from database
 * @author Evan
 *
 */

namespace Aedea\Core\Database;

use \Aedea\Core\Database;

abstract class StdObject extends StdClass {
	
	protected
		$id,
		$created,
		$modified,
		$status,
		$createBy,
		$createDate,
		$updateBy,
		$modifiesBy
	;
	
	/**
	 * Optional Params:
	 * - (resource) db_link
	 * - (int) id
	 */
	public function __construct( $params = array() ){
		foreach( $params as $k => $v ){
			switch( $k ){
				case 'db_link':
					$this->setDbRes($v);
					break;
						
				case 'id':
					$this->setId($v);
					break;
					
				case 'properties':					
					$this->setProperties($v);
					break;
			}
		}
		
		parent::__construct($params);
	}
	
	
	/**
	 * returns an array of the object's properties
	 * 
	 * Expected Params:
	 * 	- (array) include
	 * 
	 * Optional Params:
	 * 	- (bool) getters_not_required
	 */
	public function toArray( $params = array() ){
		$array = array();
		
		foreach( $params['include'] as $k ){
			$property = \Util::snakeToCamel($k);
			$getter = 'get' . ucfirst($property);
			
			// first try getter
			if( method_exists($this, $getter) ){
				$val = call_user_func(array($this, $getter));
				
				// descendants of StdCollection convert to array..
				if(
					is_object( $val )
						&&
					in_array(
						__NAMESPACE__ . '\StdCollection',
						class_parents($val)
					)
				){
					$array[$k] = $val->getElementsAsArray();
				} else {
					$array[$k] = $val;
				}
					
				
				continue;
			}
			
			// fall back to class property if allowed
			elseif( 
				isset($params['getters_not_required']) 
					&& 
				$params['getters_not_required'] 
			){
				if( property_exists($this, $property) ){
					$array[$k] = $this->$property;
					continue;
				}
			}
			
			
			throw new \Exception(
				"unrecognised property [$property]"
			);
			
			
		}
		
		return $array;
	}
	
	
	/**
	 * sets properties based on array
	 * 
	 * Expected Params:
	 * 	- (array) properties
	 * 
	 * Optional Params:
	 * 	- (bool) setters_not_required
	 */
	public function fromArray( $params = array() ){
		foreach( $params['properties'] as $k => $v ){
			$property = \Util::snakeToCamel($k);
			$setter = 'set' . ucFirst($property);
			
			// first try setter
			if( method_exists($this, $setter) ){
				call_user_func_array(
					array(
						$this, 
						$setter
					),
					array(
						$v
					)
				);
				continue;
			}
				
			// fall back to class property if allowed
			elseif(
				isset($params['setters_not_required'])
					&&
				$params['setters_not_required']
			){
				if( property_exists($this, $property) ){
					$this->$property = $v;
					continue;
				}
			}
		
			throw new \Exception(
				"unrecognised property [$property]"
			);
		}
	}
	
	
	abstract public function load( $params = array() );
	
	
	public function setProperties( $properties = array() ){
		foreach( $properties as $k => $v ){
			$k = \Util::snakeToCamel($k);
			
			if( property_exists($this, $k) ){
				$setter = 'set' . ucfirst($k);
				if( method_exists($this, $setter) ){
					call_user_func(array(
						$this,
						$setter
					), $v);
				} else {
					throw new \Exception(
						"no setter for property [$k]"
					);
				}
			} else {
				throw new \Exception(
					"unknown property [$k]"
				);
			}
		}
	}
	
	
	public function getId() {
		return $this->id;
	}
	
	public function setId( $id ) {
		
		//echo "id is $id <br />";
		$id = (int)$id;
		
		if( $id > 0 ) {
			$this->id = $id;
			return;
		}
		
		throw new \Exception(" id must be a positive integer value ");
		
	}
	
	protected function setCreated( $mixed ){
		$this->created = $mixed;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	protected function setModified( $mixed ){
		$this->modified = $mixed;
	}
	
	public function getModified() {
		return $this->modified;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus( $id ) {
		$this->status = (int)$id;
	}

	public function getModifiesBy() {
		return $this->modifiesBy;
	}

	public function setModifiesBy( $id ) {
		$this->modifiesBy = $id;
	}

	public function getCreateBy() {
		return $this->createBy;
	}
	public function setCreateBy( $id ) {
		$this->createBy = $id;
	}

	public function getCreateDate() {
		return $this->createDate;
	}
	public function setCreateDate( $date ) {
		$this->createDate = $date;
	}

	public function getUpdateBy() {
		return $this->updateBy;
	}
	public function setUpdateBy( $id ) {
		$this->updateBy = $id;
	}
	
}
