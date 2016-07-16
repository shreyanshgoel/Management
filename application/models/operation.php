<?php

/**
 * The User Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Operation extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label table id
     */
    protected $_table_id;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @validate required
     * @label c1/c2/c3/c4/c5/c6/c7/c8/c9/c10
     */
    protected $_o_col_1;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_1;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_2;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_2;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_3;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_3;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_4;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_4;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_5;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_5;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_6;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_6;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_7;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_7;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_8;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label add = 1/ subtract = 2/ multiply = 3/ divide = 4
     */
    protected $_operation_8;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_o_col_9;

    /**
     * @column
     * @readwrite
     * @type text
     * 
     * @label entry
     */
    protected $_result_col;


}
