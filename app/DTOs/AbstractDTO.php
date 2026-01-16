<?php

namespace App\DTOs;

class AbstractDTO
{
    public function toArray(){
        return (array) $this;
    }
}
