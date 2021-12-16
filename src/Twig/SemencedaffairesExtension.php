<?php

namespace App\Twig;

use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SemencedaffairesExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_name', [$this, 'doSomething']),
            new TwigFilter('amount', [$this, 'amount']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
            new TwigFunction('containsAclField', [$this, 'containsAclField']),
        ];
    }

    public function doSomething($value)
    {
    }

    #region Acl Filds
    /**
     * Détermine si un objet se trouve dans une Collection
     *
     * @param mixed        $object
     * @param Collection   $arrayCollection
     *
     * @return bool
     */
    public function containsAclField($object, Collection $arrayCollection) {
        return $arrayCollection->contains($object->data);
    }
    #endregion

    #region Extension Amount
    /**
     * @param        $value
     * @param string $symbol
     * @param string $desSep
     * @param string $thousandSep
     *
     * @return string
     */
    public function amount($value, string $symbol = ' F CFA', string $desSep =',', string $thousandSep = ' '){
        $valueExtension = number_format($value,2, $desSep, $thousandSep);
        return $valueExtension . '' .$symbol ;
    }
    #endregion
}
