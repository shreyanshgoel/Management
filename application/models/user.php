<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class User extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label full name
     */
    protected $_full_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label mobile number
     */
    protected $_mobile;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @uindex
     * 
     * @validate required
     * @label email address
     */
    protected $_email;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     * 
     * @validate required
     * @label password
     */
    protected $_password;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @uindex
     *
     * @label shop name
     */
    protected $_shop_name = NULL;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @uindex
     *
     * @label shop name
     */
    protected $_shop_document = NULL;

    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_shop = false;
    
    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_admin = false;

}
