<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Validate the login function with zhaobing\' email and password! ');
$I->amOnPage('/');
$I->see('赵兵的电影');
$I->canSeeInCurrentUrl('/auth/login');
$I->fillField('email','zhaobing024@gmail.com');
$I->fillField('password','123456');
$I->click('Login');
$I->seeCurrentUrlEquals('/movies');
$I->see('欢迎赵兵登录');
