<?php

namespace App\Salam;


class Hello
{
    /**
     * @var DireSalam
     */
    private $direSalam;

    public function __construct(DireSalam $direSalam)
    {
        $this->direSalam = $direSalam;
    }

    public function arabe()
    {
        $this->direSalam->dire();
    }

    public function berbere()
    {
        echo 'azoul';
    }
}
