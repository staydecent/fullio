<?php

class NewSet extends mud\RequestHandler {

    function get($args) {
        /*
            For testing mail processing.
        */
        $file = TEMPLATE_DIR.'form.mail_test.html';
        $data = array(
        );
        $this->response(mud\Template::render($file, $data));
    }

    function post($args) {
        /*
            Mailgun sends us a nice POST.
        */
        $log = mud\Logger::instance(ROOT.'logs/');
        $log->info('Mail post hit.');

        require MODELS_DIR.'Set.php';
        require MODELS_DIR.'User.php';
        require SYSTEM_DIR.'AWSSDKforPHP/sdk.class.php';

        // grab the message parts we plan to save
        $form = $this->request(array(
            'sender',
            'subject',
            'body-plain'
        ));
        $log->info('Sender: '. $form['sender']);

        $user = Model::factory('User')->where('email', $form['sender'])->find_one();

        if ($user) 
        {
            $log->info('User exists from: '. $form['sender']);
            // upload attachments
            $s3 = new AmazonS3;
            $individual_filenames = array();

            foreach ($_FILES as $f) 
            {
                // name => 431x377.gif
                // type => image/gif
                // tmp_name => /tmp/phpfeytmd
                // error => 0
                // size => 1621
                if (!stristr($f['type'], 'image'))
                    continue;

                $filename = str_replace('/tmp/', '', $f['tmp_name']).'_'.$f['name'];

                // Store the filename for later use
                $individual_filenames[] = $filename;

                $s3->batch()->create_object(S3_BUCKET, $filename, array(
                    'fileUpload' => $f['tmp_name'],
                    'contentType' => $f['type'],
                    'acl' => $s3::ACL_PUBLIC,
                    'length' => $f['size'],
                    'meta' => array('owner'=>$user->id())
                ));
            }

            $resp = $s3->batch()->send(); // bbooM!?
            $file_urls = array();

            if ($resp->areOK()) 
            {
                foreach ($individual_filenames as $filename) 
                {
                    // save the urls of these files with the db.sets collection
                    $file_urls[] = $s3->get_object_url(S3_BUCKET, $filename);
                }
            }
            else 
            {
                $log->fatal('Failed to upload to s3: ' . print_r($resp, TRUE));
            }

            $set = Model::factory('Set')->create();

            $set->user_id = $user->id();
            $set->title = $form['subject'];
            $set->body = $form['body-plain'];
            $set->images = $file_urls;

            if ( ! $set->save()) 
            {
                $log->fatal("New set failed!");
            }
        }
        else
        {
            $log->fatal($form['sender']." Failed!");
        }
    }
}