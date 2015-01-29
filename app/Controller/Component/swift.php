<?php

    App::import('Vendor', 'swift/lib/Swift');
    /*
     * PasswordComponent
     *
     * @author Juraj Vlk juraj.vlk@gmail.com
     */

    class SwiftComponent extends Object {

        var $controller = null;
        function startup (&$controller) {
                // This method takes a reference to the controller which is loading it.
                // Perform controller initialization here.
            $this->controller = $controller;
        }

        /**
         * generate seo optimazed string
         *
         * @param string $text string
         * @param string $separator string which is inserted between words
         * @return string $output seo optimazed string
         *
         * http://www.bitrepository.com/php-format-text-into-a-seo-friendly-string.html
         */

        function send($to, $subject='', $template = '', $type = 'html', $data = array()){

            ini_set("SMTP", SMTP_HOST);
            ini_set("smtp_port", Swift_Connection_SMTP::PORT_SECURE);
            ini_set("sendmail_from", SMTP_LOGIN);
            $smtp = new Swift_Connection_SMTP(SMTP_HOST, Swift_Connection_SMTP::PORT_SECURE, Swift_Connection_SMTP::ENC_TLS);
                    

            $smtp->setUsername(SMTP_LOGIN);
            $smtp->setPassword(SMTP_PASS);
            $smtp->setTimeout(SMTP_TIMEOUT);
            $smtp->attachAuthenticator(new Swift_Authenticator_LOGIN());
                    

            $swift = new Swift($smtp);

            foreach ($data as $key => $data) {
                    $this->controller->set($key, $data);
            }
            
            $template_full_path = "/elements/email/" . $type . "/" . $template;
            $this->controller->layout = false;
            
            $body = $this->controller->render($template_full_path, null, null);
            $this->controller->output = '';
            $message =& new Swift_Message($subject, $body, "text/html");

            if ($swift->send($message, $to, SMTP_LOGIN))
            {
                
            }
            else
            {
                
            }

            //It's polite to do this when you're finished
            $swift->disconnect();

        }

    }

?>