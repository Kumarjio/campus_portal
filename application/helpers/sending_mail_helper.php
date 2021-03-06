<?php

if (!function_exists('send_mail')) {

    function send_mail($tomailid, $subject, $message, $cc = '', $attachement = '') {

        $ci = get_instance();
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.devrepublic.nl',
            'smtp_user' => 'edward@devrepublic.nl',
            'smtp_pass' => 'inderimboe',
            'mailtype' => 'html',
        );
        $ci->load->library('email', $config);
        $ci->email->set_newline("\r\n");
        $ci->email->from('edward@devrepublic.nl');
        $ci->email->to($tomailid);
        $ci->email->subject($subject);
        $ci->email->message($message);

        if ($cc != '')
            $ci->email->bcc($cc);
        
        if ($attachement != '')
            $ci->email->attach($attachement);

        if (!$ci->email->send()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
?>
