<?php

class User extends Model {

    public function sets() 
    {
        return $this->has_many('Set');
    }

    /**
     * Hash password before save.
     */
    public function save()
    {
        require_once SYSTEM_DIR.'mud/Hash.php';

        $this->password = mud\Hash::make($this->password);
        parent::save();

        return TRUE;
    }

    // public function set_password($raw_password) 
    // {
    //     require SYSTEM_DIR.'mud/Hash.php';
    //     $this->password = mud\Hash::make($raw_password);
    // }

    public function check_password($raw_password) 
    {
        require SYSTEM_DIR.'mud/Hash.php';
        return mud\Hash::check($raw_password, $this->password);
    }

    public function get_name() 
    {
        return ( ! empty($this->name)) ? $this->name : ucwords($this->subdomain);
    }

    public function get_url() 
    {
        return 'http://'.$this->subdomain.'.'.BASE_DOMAIN;
    }

    public function valid_subdomain() 
    {
        $illegal_strings = array(
              'www'
            , 'fullioapp'
            , 'fullio'
            , 'admin'
            , 'test'
            , 'blog'
        );

        $this->subdomain = strtolower($this->subdomain);
        
        if (ctype_alnum($this->subdomain)) 
        {
            foreach ($illegal_strings as $string) 
            {
                if ($string == $this->subdomain)
                {
                    return FALSE;
                }
            }
        }
        else 
        {
            return FALSE;
        }

        return TRUE;
    }
}