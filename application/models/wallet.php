<?php

/**
 * The Payment Model
 *
 * @author Shreyansh Goel
 */
namespace models;

class Wallet extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_balance;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_user_id;

}
