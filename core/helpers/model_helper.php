<?php
   /**
    *
    * Allow models to use other models
    *
    * This is a substitute for the inability to load models
    * inside of other models in CodeIgniter.  Call it like
    * this:
    *
    * $salaries = model_load_model('salary');
    * ...
    * $salary = $salaries->get_salary($employee_id);
    *
    * @param string $model_name The name of the model that is to be loaded
    *
    * @return object The requested model object
    *
    */
        
   function model_load_model($model_name)
   {
      $FURY =& get_instance();
      $FURY->load->model($model_name);
      return $FURY->$model_name;
   }