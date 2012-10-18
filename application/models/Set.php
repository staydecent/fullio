<?php

class Set extends Model {

    public function user() 
    {
        return $this->belongs_to('User');
    }

    public function save()
    {
        $this->images = json_encode($this->images);

        parent::save();
    }
}