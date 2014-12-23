<?php

class Codex_Xtest_Xtest_Helper_Mailqueue
{
    protected static $_mailqueue = array();

    public function reset()
    {
        self::$_mailqueue = array();
        return $this;
    }

    public function getLastMail()
    {
        return end( self::$_mailqueue );
    }

    public function addMail( Mage_Core_Model_Email_Template $mailObject, $email, $name, $variables )
    {

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        $mailObject->setUseAbsoluteLinks(true);
        $text = $mailObject->getProcessedTemplate($variables, true);

        if( empty($email) ) {
            throw Mage::exception('Codex_Xtest', 'to is empty', Codex_Xtest_Exception::EMPTY_MAIL_RECIPIENT);
        }

        if( empty($text) ) {
            throw Mage::exception('Codex_Xtest', 'body is empty', Codex_Xtest_Exception::EMPTY_MAIL_BODY);
        }

        self::$_mailqueue[] = array(
            'variables' => $variables,
            'emails' => $emails,
            'body' => $text
        );

        return $this;
    }

    public function getCount()
    {
        return count( self::$_mailqueue );
    }

}