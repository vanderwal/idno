<?php

    /**
     * Base entity class
     * 
     * This is designed to be inherited by anything that needs to be an
     * object in the idno system
     * 
     * @package idno
     * @subpackage core
     */

	namespace Idno\Common {
	
	    class Entity {
		
		// Store the entity's attributes
		    private $attributes = array();
		    
		/**
		 * Overloading the entity property read function, so we
		 * can simply check $entity->$foo for any non-empty value
		 * of $foo for any property of this entity.
		 */
		    
		    function __get($name) {
			if (isset($this->attributes[$name])) return $this->attributes[$name];
			return null;
		    }
		    
		/**
		 * Overloading the entity property write function, so
		 * we can simply set $entity->$foo = $bar for any
		 * non-empty value of $foo for any property of this entity.
		 */
		    
		    function __set($name, $value) {
			$this->attributes[$name] = $value;
		    }
		    
		/**
		 * Saves this entity - either creating a new entry, or
		 * overwriting the existing one.
		 */
		    
		    function save() {
			if (empty($this->created)) {
			    $this->created = time();
			}
			$this->updated = time();
			$result = \Idno\Core\site()->db->saveObject($this);
			if ($result instanceof MongoId) {
			    $this->_id = $result->id;
			    return $this->_id;
			} else if (!empty($result)) {
			    $this->_id = $result;
			    return $this->_id;
			} else {
			    return false;
			}
		    }
		    
		/**
		 * Can a specified user (either an explicitly specified user ID
		 * or the currently logged-in user if this is left blank) edit
		 * this entity?
		 * 
		 * @param string $user_id
		 * @return true|false
		 */
		    
		    function canEdit($user_id = '') {
		    }
		    
		/**
		 * Can a specified user (either an explicitly specified user ID
		 * or the currently logged-in user if this is left blank) view
		 * this entity?
		 * 
		 * @param string $user_id
		 * @return true|false
		 */
		    
		    function canRead($user_id = '') {
		    }
		    
		/**
		 * Returns the database collection that this object should be 
		 * saved as part of
		 * 
		 * @return type 
		 */
		    function getCollection() {
			return 'entities';
		    }
		    
		/**
		 * Populate the attributes of this object from an array
		 * 
		 * @param array $array 
		 */
		    function loadFromArray($array) {
			if (!empty($array) && is_array($array)) {
			    foreach($array as $key => $value) {
				$this->attributes[$key] = $value;
			    }
			}
		    }
		    
		/**
		 * Store this object's attributes and class information as
		 * an array
		 * 
		 * @return array
		 */
		    
		    function saveToArray() {
			
			$array = $this->attributes;
			$array['entity_subtype'] = get_class($this);
			return $array;
			
		    }
		
	    }
	    
	}