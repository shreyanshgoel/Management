<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Inventory extends \Shared\Model {

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
    protected $_s;

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
    protected $_unit;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_stock_quantity;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_stock_sold;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_sales_price;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_bulk_quantity;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_sale_discount;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_item_description;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label password
     */
    protected $_item_valuation;


}
