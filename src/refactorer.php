<?php

function refactorPrice(float $price): string
{
    return number_format($price, 2, ',', ' ');
}