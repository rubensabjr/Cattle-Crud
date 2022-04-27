<?php

namespace App\Service;

use App\Entity\Cattle;
use DateTime;

class CattleService 
{
    public function Slaughter(Cattle $cattle): bool
    {
        $now = new DateTime('now');
        $sub = $now->sub(new \DateInterval('P5Y'));
        $date = $sub->format('Y-m-d');

        if($cattle->getBirth() < $date)
            return true;
        elseif($cattle->getMilk() < 40)
            return true;
        elseif($cattle->getMilk() < 70 && $cattle->getRation() > 50)
            return true;
        elseif($cattle->getWeight() > 270)
            return true;
        else 
            return false;
    }
}