<?php

use Behat\Gherkin\Node\TableNode;

require_once 'SubContext.php';

class AccountContext extends SubContext
{
    /**
     * @Given /^I log in with email "(?P<email>[^"]*)" and password "(?P<password>[^"]*)"$/
     */
    public function iLogInAsWithPassword($email, $password)
    {
        $this->getPage('Account')->login($email, $password);
    }

    /**
     * @Given /^I log in successful as "(?P<username>[^"]*)" with email "(?P<email>[^"]*)" and password "(?P<password>[^"]*)"$/
     */
    public function iLogInSuccessfulAsWithPassword($username, $email, $password)
    {
        $this->getPage('Account')->login($email, $password);
        $this->getPage('Account')->verifyLogin($username);
    }

    /**
     * @When /^I log me out$/
     */
    public function iLogMeOut()
    {
        $this->getPage('Account')->logout();
    }

    /**
     * @Then /^I change my email with password "(?P<password>[^"]*)" to "(?P<new>[^"]*)"$/
     * @Then /^I change my email with password "(?P<password>[^"]*)" to "(?P<new>[^"]*)" with confirmation "(?P<confirmation>[^"]*)"$/
     */
    public function iChangeMyEmailWithPasswordToWithConfirmation($password, $email, $emailConfirmation = null)
    {
        $this->getPage('Account')->changeEmail($password, $email, $emailConfirmation);
    }

    /**
     * @Then /^I change my password from "(?P<old>[^"]*)" to "(?P<new>[^"]*)"$/
     * @Then /^I change my password from "(?P<old>[^"]*)" to "(?P<new>[^"]*)" with confirmation "(?P<confirmation>[^"]*)"$/
     */
    public function iChangeMyPasswordFromToWithConfirmation($currentPassword, $password, $passwordConfirmation = null)
    {
        $this->getPage('Account')->changePassword($currentPassword, $password, $passwordConfirmation);
    }

    /**
     * @Given /^I change my billing address:$/
     */
    public function iChangeMyBillingAddress(TableNode $table)
    {
        $this->getPage('Account')->changeBilling($table->getHash());
    }

    /**
     * @Given /^I change my shipping address:$/
     */
    public function iChangeMyShippingAddress(TableNode $table)
    {
        $pageInfo = Helper::getPageInfo($this->getSession(), array('controller'));
        $pageName = ucfirst($pageInfo['controller']);

        if($pageName === 'Checkout') {
            $pageName = 'CheckoutConfirm';
        }

        /** @var \Page\Emotion\Account|\Page\Emotion\CheckoutConfirm $page */
        $page = $this->getPage($pageName);
        $data = $table->getHash();

        $page->changeShippingAddress($data);
    }

    /**
     * @Given /^the "([^"]*)" address should be "([^"]*)"$/
     */
    public function theAddressShouldBe($type, $address)
    {
        $this->getPage('Account')->checkAddress($type, $address);
    }

    /**
     * @Given /^I register me:$/
     */
    public function iRegisterMe(\Behat\Gherkin\Node\TableNode $table)
    {
        $this->getPage('Account')->register($table->getHash());
    }

    /**
     * @When /^I change the payment method to (?P<paymentId>\d+)$/
     * @When /^I change the payment method to (?P<paymentId>\d+):$/
     */
    public function iChangeThePaymentMethodTo($payment, TableNode $table = null)
    {
        $pageInfo = Helper::getPageInfo($this->getSession(), array('controller', 'action'));
        $pageName = ucfirst($pageInfo['controller']);

        if($pageName === 'Checkout') {
            $pageName = ($pageInfo['action'] === 'shippingPayment') ? 'CheckoutCart' : 'CheckoutConfirm';
        }

        /** @var \Page\Emotion\Account|\Page\Emotion\CheckoutConfirm $page */
        $page = $this->getPage($pageName);
        $data = array(
            array(
                'field' => 'register[payment]',
                'value' => $payment
            )
        );

        if($table) {
            $data = array_merge($data, $table->getHash());
        }

        $page->changePaymentMethod($data);
    }
}
