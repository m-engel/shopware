<?php

use Behat\Gherkin\Node\TableNode;
require_once 'SubContext.php';

class DetailContext extends SubContext
{
    /**
     * @Given /^I am on the detail page for article (?P<articleId>\d+)$/
     */
    public function iAmOnTheDetailPageForArticle($articleId)
    {
        $this->getPage('Detail')->open(array('articleId' => $articleId));
    }

    /**
     * @When /^I put the article into the basket$/
     * @When /^I put the article "(?P<quantity>[^"]*)" times into the basket$/
     */
    public function iPutTheArticleTimesIntoTheBasket($quantity = 1)
    {
        $this->getPage('Detail')->toBasket($quantity);
    }

    /**
     * @Given /^I go to previous article$/
     */
    public function iGoToPreviousArticle()
    {
        $this->getPage('Detail')->goToNeighbor('back');
    }

    /**
     * @Given /^I go to next article$/
     */
    public function iGoToNextArticle()
    {
        $this->getPage('Detail')->goToNeighbor('next');
    }

    /**
     * @Given /^I should see an average customer evaluation of (?P<average>\d+) from following evaluations:$/
     */
    public function iShouldSeeAnAverageCustomerEvaluationOfFromFollowingEvaluations($average, TableNode $evaluations)
    {
        /** @var \Page\Emotion\Detail $page */
        $page = $this->getPage('Detail');

        /** @var \Element\MultipleElement $notePositions */
        $articleEvaluations = $this->getElement('ArticleEvaluation');
        $articleEvaluations = $articleEvaluations->setParent($page);

        $evaluations = $evaluations->getHash();

        $page->checkEvaluations($articleEvaluations, $average, $evaluations);
    }

    /**
     * @When /^I choose the following article configuration:$/
     */
    public function iChooseTheFollowingArticleConfiguration(TableNode $configuration)
    {
        $configuration = $configuration->getHash();

        $this->getPage('Detail')->configure($configuration);
    }

    /**
     * @Then /^I can not select "([^"]*)" from "([^"]*)"$/
     */
    public function iCanNotSelectFrom($configuratorOption, $configuratorGroup)
    {
        $this->getPage('Detail')->canNotSelectConfiguratorOption($configuratorOption, $configuratorGroup);
    }

    /**
     * @When /^I write an evaluation:$/
     */
    public function iWriteAnEvaluation(TableNode $data)
    {
        $this->getPage('Detail')->writeEvaluation($data->getHash());
    }

    /**
     * @When /^the shop owner activates my latest evaluation$/
     * @When /^the shop owner activates my latest (\d+) evaluations$/
     */
    public function theShopOwnerActivateMyLatestEvaluation($limit = 1)
    {
        $sql = 'UPDATE `s_articles_vote` SET `active`= 1 ORDER BY id DESC LIMIT '.$limit;
        $this->getContainer()->get('db')->exec($sql);
        $this->getSession()->reload();
    }

    /**
     * @Given /^I can select every (\d+)\. option of "([^"]*)" from "([^"]*)" to "([^"]*)"$/
     */
    public function iCanSelectEveryOptionOfFromTo($graduation, $select, $min, $max)
    {
        $this->getPage('Detail')->checkSelect($select, $min, $max, $graduation);
    }

    /**
     * @When /^I submit the notification form with "([^"]*)"$/
     */
    public function iSubmitTheNotificationFormWith($email)
    {
        $this->getPage('Detail')->submitNotification($email);
    }


}
