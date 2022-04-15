<?php

namespace App\Analytics;

use Symfony\Component\Finder\Finder;

class LoginNumbers extends Finder
{
    public function getNumberOfLogins()
    {
        $finder = new Finder();
        $finder->files()->in('var/log/analytics.log');

        $handler = new \SplFileObject($finder);
        $file = $handler->openFile('r');
    }
}
