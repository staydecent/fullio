<?php

namespace mud;

use Mongo,
    MongoDate,
    MongoID,
    MongoException;

class MongoModel {
    /*
        A model class to be used with MongoDB.
    */
    public $created_at,
           $updated_at;

    protected $_m,         // The mongo instance
              $_db,        // Db instance 
              $_collection,// Collection instance
              $_id,        // current doc id
              $_objNew;     // update obj

    public function set_collection($db, $collection) {
        /*
            Simply connect to the desired collection
            from the desired database name.
        */
        try {
            $this->_m = new Mongo(DB_HOST);
        }
        catch (MongoConnectionException $e) {
            throw new Exception("Error connecting to MongoDB: ".$e->getMessage());
        }

        $this->_db = $this->_m->$db;
        $this->_collection = $this->_db->$collection;
    }

    public function get_collection() {
        /*
            Just returns the collection.
            Must already be set.
        */
        return $this->_collection;
    }

    public function set_properties($array) {
        /*
            Named array to set the content
            of each found property.
        */
        foreach ($array as $property => $value) {
            $this->$property = $value;
        }
    }

    public function get_properties($array = array()) {
        /*
            Get all properties that would be saved.
            Ignores properties prefixed with an underscore.
        */
        $array = get_object_vars($this);

        foreach ($array as $k => $v) {
            // haystack, needle
            $pos = strpos($k, '_');
            if ($pos === 0)
                unset($array[$k]);
        }

        return $array;
    }

    private function update_objNew() {
        /*
            Set the update object.
            Helps with performance by unsetting
            any empty properties.
        */
        foreach ($this->_objNew as $cmd => $pairs) 
        {
            foreach ($pairs as $k => $v) 
            {
                if (empty($this->$k))
                    unset($this->_objNew[$cmd][$k]);
                else
                    $this->_objNew[$cmd][$k] = $this->$k;
            }
        }
    }

    public function find($query = array(), $fields = array(), $sort = false) {
        /*
            Wrapper for MongoCollection::find
        */
        $cursor = $this->_collection->find($query, $fields);

        if (is_array($sort))
            $cursor->sort($sort);

        $this->set_properties($cursor);
        return $cursor;
    }

    public function find_one($query, $fields = array()) {
        /*
            Wrapper for MongoCollection::findOne
        */
        $match = $this->_collection->findOne($query, $fields);
        if ($match !== null)
            $this->set_properties($match);

        return $match;
    }

    public function exists($property) {
        /*
            Check if a document exists by
            the specified property.
        */
        if (empty($property))
            return false;

        $match = $this->find_one(array($property=>$this->$property), array('_id'));

        if ($match === NULL)
            return false;
        else
            return true;
    }

    public function id() {
        /*
            Returns the document ID or false.
        */
        if (!isset($this->_id)) {
            $match = $this->find_one(array('email'=>$this->email), array('_id'));

            if ($match === NULL) {
                return false;
            }
            else {
                $this->_id = $match['_id'];
            }
        }
        
        return $this->_id;
    }

    public function save() {
        /*
            Insert or update a record.
        */
        Event::fire('before_save');

        $update = isset($this->_id) && $this->_id InstanceOf MongoID;
        $array = $this->get_properties();
        $date = new MongoDate();

        if ($update) {
            Event::fire('before_update');
            $this->updated_at = $date;
            $this->update_objNew();

            $this->_collection->update(array('_id'=>$this->id()), $this->_objNew);
        }
        else {
            Event::fire('before_insert');
            $array['created_at'] = $date;
            $array['updated_at'] = $date;

            $this->_collection->insert($array);   
            $this->id();
        }

        Event::fire('after_save');
        return true;
    }
}

?>