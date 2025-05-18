<?php

namespace App\Libraries;

use CodeIgniter\Config\Services;

class Mailer
{
    protected $email;
    protected $view;
    
    public function __construct()
    {
        $this->email = Services::email();
        $this->view = Services::renderer(); // Load view engine
    }
    
    /**
     * Send an email using a view template
     *
     * @param string|array $to Single email or array of recipients
     * @param string $subject
     * @param string $viewPath view file path relative to `app/Views/`, e.g. 'emails/welcome'
     * @param array $data Data passed to the view
     * @param array $options Optional [fromEmail, fromName, cc, bcc, attachments]
     * @return bool|string true if success, or debugger info if failed
     */
    public function send(string|array $to, string $subject, string $viewPath, array $data = [], array $options = [])
    {
        $fromEmail = $options['fromEmail'] ?? config('Email')->fromEmail;
        $fromName  = $options['fromName'] ?? config('Email')->fromName;
        
        $message = $this->view->setData($data)->render($viewPath);
        
        $this->email->clear(true); // Clear attachments and headers
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setFrom($fromEmail, $fromName);
        
        // Handle optional fields
        if (!empty($options['cc'])) {
            $this->email->setCC($options['cc']);
        }
        
        if (!empty($options['bcc'])) {
            $this->email->setBCC($options['bcc']);
        }
        
        if (!empty($options['attachments'])) {
            foreach ((array) $options['attachments'] as $file) {
                $this->email->attach($file);
            }
        }
        
        if ($this->email->send()) {
            return true;
        }
        
        return $this->email->printDebugger(['headers', 'subject', 'body']);
    }
}
