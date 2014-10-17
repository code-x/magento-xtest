<?php

class Mage_Core_Test_Double_Model_Email_Template extends Mage_Core_Model_Email_Template
{
    public function send($email, $name = null, array $variables = array())
    {
        /** @var $mailqueue Codex_Xtest_Xtest_Helper_Mailqueue */
        $mailqueue = Xtest::getXtest('xtest/helper_mailqueue');

        $mailqueue->addMail($this, $email, $name, $variables);
        return true;
    }
}