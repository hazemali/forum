<?php


/**
 * @param $class
 * @param array $attributes
 * @param null $count
 * @return mixed
 */
function make($class , $attributes = [] , $count = null){

    return factory($class , $count)->make($attributes);

}


/**
 * @param $class
 * @param array $attributes
 * @param null $count
 * @return mixed
 */
function create($class , $attributes = [] , $count = null){
    return factory($class,$count)->create($attributes);

}