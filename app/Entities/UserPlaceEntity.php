<?php


namespace App\Entities;


class UserPlaceEntity  extends PlaceEntity {

    /**
     * @var boolean
     */
    public $isFavourite;

    /**
     * @var boolean
     */
    public $isWantToGo;

    /**
     * @var string
     */
    public $notes;



}
