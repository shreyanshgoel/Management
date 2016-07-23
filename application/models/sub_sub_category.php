<?php

/**
 * The sub category Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Sub_Sub_Category extends \Shared\Model {

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
     * @label sub category id
     */
    protected $_sub_category_id;

}
