<?php
namespace App\cart ;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BasedMoney;
use NumberFormatter ;


class Money{
protected $money ;
public function __construct($value)
{
    $this->money =  new BasedMoney($value , new Currency('USD'));
}
public function formatted(){
    $formatter =  new intlMoneyFormatter(new NumberFormatter('en_us' , NumberFormatter::CURRENCY )
        ,new ISOCurrencies() );
    return $formatter->format($this->money);
}
public function amount()
{
    return $this->money->getAmount();
}
public function add(Money $money)
{
    //add method below add $this and $money

    $this->money = $this->money->add($money->instance());
    return $this;

}
public function instance()
{
    return $this->money  ;
}
}

