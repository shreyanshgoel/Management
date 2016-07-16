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
     * 
     * @label mobile number
     */
    protected $_designation;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label mobile number
     */
    protected $_company_name;

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
     * @type boolean
     */
    protected $_email_confirm = false;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label mobile number
     */
    protected $_email_confirm_string;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label password
     */
    protected $_password;

    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_admin = false;

}
