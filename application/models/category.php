<?php

/**
 * The Payment Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Category extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label category name
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required
     * @label short form
     */
    protected $_short_name;

}
