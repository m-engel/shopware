@newsletter
Feature: Newsletter

    Scenario: Subscribe to and unsubscripe from newsletter
        Given I am on the homepage
        When  I subscribe to the newsletter with "test@example.de"
        Then  I should see "Vielen Dank. Wir haben Ihre Adresse eingetragen."

        When  I unsubscribe the newsletter
        Then  I should see "Ihre eMail-Adresse wurde gelöscht"

    Scenario: I can subscribe to the newsletter with additional data
        Given I am on the page "Newsletter"
        When  I subscribe to the newsletter with "test@example.de" :
            | field      | value           |
            | salutation | mr              |
            | firstname  | Max             |
            | lastname   | Mustermann      |
            | street     | Musterstr. 55   |
            | zipcode    | 55555           |
            | city       | Musterhausen    |
        Then  I should see "Vielen Dank. Wir haben Ihre Adresse eingetragen."

        When  I unsubscribe the newsletter
        Then  I should see "Ihre eMail-Adresse wurde gelöscht"

    @javascript @account
    Scenario:
        Given I am on the page "Account"
        And   I log in successful as "Max Mustermann" with email "test@example.com" and password "shopware"
        When  I check "newsletter"
        Then  I should see "Erfolgreich gespeichert"
        And   the checkbox "newsletter" should be checked

        When  I uncheck "newsletter"
        Then  I should see "Ihre eMail-Adresse wurde gelöscht"
        And   the checkbox "newsletter" should be unchecked
        And   I log me out