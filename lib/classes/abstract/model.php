<?php
    //DatabaseInteraction
    abstract class Model 
    //extends InteractDB 
    {
        protected $_model;

        function __construct() //$byPass = false)
        {
            //if(!$byPass)
            //{
            //	parent::__construct();
                $this->_model = get_class($this);
                $this->_table = strtolower($this->_model);
            /*}
            else
            {
                parent::__construct();
            }*/
        }

        function __destruct()
        {

        }
    }
