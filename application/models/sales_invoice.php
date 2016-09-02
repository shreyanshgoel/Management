<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Sales_Invoice extends \Shared\Model {


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
    protected $_customer_id;

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
     * @label password
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
