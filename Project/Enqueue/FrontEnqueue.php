<?php
namespace Project\Enqueue;
use App\Base\Enqueue;

class FrontEnqueue extends Enqueue {

    public function __construct() {
        parent::__construct();
    }

    protected array $load = [
        "front",
    ];

    public function style() {
        return [

            [ 
                "SRC" => "/assets/bootstrap.rtl.min.css" ,
                "DEPS" => "" ,
            ],

            [ 
                "SRC" => "/assets/mohammad.css" ,
                "DEPS" => "" ,
            ],
        ];
    }
    public function script() {

        return [
            [ 
                "SRC" => "/assets/bootstrap.min.js" ,
                "DEPS" => "" ,
                "VER" => "" ,
                "FOOTER" => true ,
            ],

            [ 
                "SRC" => "/assets/mohammad.js" ,
                "DEPS" => "" ,
                "VER" => "" ,
                "FOOTER" => true ,
            ],
        ] ;
    }
}

