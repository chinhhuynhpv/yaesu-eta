<?php

namespace App\Traits;

trait HandleSearchedItem
{
   function checkSearchedItem($value, $possibleValues) {
       if ($value) {
           $requestedValue = trim(strtolower($value));
           return array_search($requestedValue, $possibleValues);
       }

       return null;
   }
}
