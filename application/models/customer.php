<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Customer extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label full name
     */
    protected $_user_id;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label password
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_phone;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_country;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_state;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_address;


}
