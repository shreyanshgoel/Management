<?php

/**
 * The Payment Model
 *
 * @author Shreyansh Goel
 */
namespace models;

class Transaction extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_from_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_to_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_amount;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_wallet_id;

}
