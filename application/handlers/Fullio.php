<?php

class Fullio extends mud\RequestHandler {

    function get($args) 
    {
        require MODELS_DIR.'User.php';
        require MODELS_DIR.'Set.php';

        // find a user by their subdomain
        $fullio = Model::factory('User')->where('subdomain', SUBDOMAIN)->find_one();

        if ( ! $fullio)
        {
            // echo "string";
            // return;
            redirect('error');
        }

        // get all sets for that user
        $sets = $fullio->sets()->find_many();

        // dumb hack, for slashed quotes
        mud\Template::add_filter(array(
            'fancy' => function($string) {
                $string = str_replace('\\\'', '&rsquo;', $string);
                return $string;
            }
        ));
        // fun
        mud\Template::add_filter(array(
            'next_title' => function($array, $i) {
                $i = $i+1;
                if (array_key_exists($i, $array))
                    return $array[$i]->title;
                else
                    return false;
            }
        ));
        // fun
        mud\Template::add_filter(array(
            'prev_title' => function($array, $i) {
                $i = $i-1;
                if (array_key_exists($i, $array))
                    return $array[$i]->title;
                else
                    return false;
            }
        ));
        

        $sets = array_map(function($n) {
            $n->images = json_decode($n->images);
            return $n;
        }, $sets);


        // Set fullio.name by method
        $fullio->name = $fullio->get_name();

        $file = TEMPLATE_DIR.'fullio.html';
        $data = array(
            'fullio' => $fullio,
            'sets' => array_reverse($sets),
            'user' => $this->get_current_user()
        );
        $this->response(mud\Template::render($file, $data));
    }
}
