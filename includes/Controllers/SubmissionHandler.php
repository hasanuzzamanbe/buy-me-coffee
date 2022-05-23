<?php
namespace buyMeCoffee\Controllers;

class SubmissionHandler
{
    public function handleSubmission()
    {
        parse_str($_REQUEST['form_data'], $form_data);

        var_dump($_REQUEST, $form_data);
        die();
        wp_send_json( array(
            'success' => true,
            'message' => 'Thank you for your submission!',
        ), 200 );

    }
}
