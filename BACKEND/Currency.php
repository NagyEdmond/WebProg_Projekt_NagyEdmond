<?php

class Currency
{

    private $name;
    private $rate;
    private $dateOfRate;

    public function __construct($name, $rate, $dateOfRate)
    {
        $this->name = $name;
        $this->rate = $rate;
        $this->dateOfRate = $dateOfRate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getDateOfRate()
    {
        return $this->dateOfRate;
    }
}

?>