<?php
class Model
{
    /**
     * @var object
     */
    protected $storage;

    /**
     * Inicializa conexion
     */
    public function __construct($filename)
    {
        $this->storage = new Storage(new FileIO($filename));
    }
}
