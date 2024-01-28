<?php
namespace Core {
    class View{
        public int $status;

        public string $path;

        public function __construct($status, $path){
            $this->path = $path;
            $this->status = $status;
        }
    }
}