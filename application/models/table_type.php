<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Table_Type extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label wdwdw
     */
    protected $_table_id;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @validate required
     * @label 1 = text, 2 = number, 3 = date/time
     */
    protected $_type1;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type2;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type3;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type4;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type5;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type6;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type7;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type8;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type9;

    /**
     * @column
     * @readwrite
     * @type integer
     * 
     * @label password
     */
    protected $_type10;


}
