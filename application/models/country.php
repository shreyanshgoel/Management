<?php

/**
 * The Country Model
 *
 * @author Shreyansh Goel
 */
namespace models;
class Country extends \Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label country name
     */
    protected $_name;

}
