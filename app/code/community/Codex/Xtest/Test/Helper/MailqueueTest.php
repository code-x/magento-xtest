<?php

class Codex_Xtest_Test_Helper_MailqueueTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    /**
     * Do not sending Mails, Queue them instead
     */
    public function testQueueCoreMail()
    {
        $customerFixture = Xtest::getXtest('xtest/fixture_customer');

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $customerFixture->getTest();

        $customer->sendNewAccountEmail();
        $this->assertMailsSent( 1 );
    }

    /**
     * Checks ifs setUp/ tearDown Resets Mail-Queue
     */
    public function testQueueSecondMail()
    {
        $this->testQueueCoreMail();
    }

    /**
     * Do not Queue empty recipients
     */
    public function testThrowExceptionBecauseEmptyRecipient()
    {
        $this->setExpectedMageException('Codex_Xtest', Codex_Xtest_Exception::EMPTY_MAIL_RECIPIENT );

        $customerFixture = Xtest::getXtest('xtest/fixture_customer');

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $customerFixture->getTest();
        $customer->setEmail('');

        $customer->sendNewAccountEmail();
    }

}