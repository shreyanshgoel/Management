<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Purchase_Sales_Invoice extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label full name
     */
    protected $_invoice_id;

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
    protected $_sc_id;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label 1 for purchase, 2 for sales
     */
    protected $_type;

    /**
     * @column
     * @readwrite
     * @type array
     * 
     * @validate required
     * @label password
     */
    protected $_item_id = [];

    /**
     * @column
     * @readwrite
     * @type array
     * 
     * @label 
     */
    protected $_quantity = [];

    /**
     * @column
     * @readwrite
     * @type array
     * 
     * @label password
     */
    protected $_price = [];


}
