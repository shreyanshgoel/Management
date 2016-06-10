<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Column extends \Shared\Model {

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
     * @label mobile number
     */
    protected $_table_number;

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
    protected $_table_name;

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
    protected $_column1_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column2_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column3_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column4_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column5_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column6_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column7_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column8_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column9_name;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_column10_name;


}
