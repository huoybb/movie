<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('Validate the login function with zhaobing\' email and password!');
$I->amOnPage('/');
$I->see('movies are there');
