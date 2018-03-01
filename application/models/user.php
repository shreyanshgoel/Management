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
     * @type boolean
     *
     * @label email address
     */
    protected $_mobile_confirm = false;

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label email address
     */
    protected $_mobile_confirm_string;

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
     * 
     * @label mobile number
     */
    protected $_country;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label mobile number
     */
    protected $_state;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label mobile number
     */
    protected $_address;

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
     * 
     * @label change email
     */
    protected $_tmp_email;

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
     * @label mobile number
     */
    protected $_last_purchase_invoice_id = 0;

     /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label mobile number
     */
    protected $_last_sales_invoice_id = 0;

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
     * @type text
     * 
     * @label 
     */
    protected $_logo_ext;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label 
     */
    protected $_theme_color = "default";

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label 
     */
    protected $_wallet_balance = 0;

    /**
     * @column
     * @readwrite
     * @type array
     * 
     * @label sdsdsd
     */
    protected $_creative = [];

    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_admin = false;

}
