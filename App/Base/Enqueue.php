<?php
namespace App\Base;

class Enqueue {

    protected array $load = [];
    public function __construct() {

        add_action('wp_enqueue_scripts', 
                   array($this, 'enqueue'));  

    }

    public function style() {
        
        return [];
    }


    public function script() {

        return [];
    }

    public function enqueue() {


        foreach ($this->load as $key ) {
            if(!$this->condition($key)) return; 
        }


        if (sizeof($this->style()) > 0) {

            foreach ($this->style() as $style) {
                
                wp_enqueue_style(
                    handle: uniqid(basename($style['SRC'])),
                    src:  $style['SRC'],
                    deps: $style['DEPS'],
                );

            }
        }


        if (sizeof($this->script()) > 0) {

            foreach ($this->script() as $script) {

                wp_enqueue_script(
                    handle: uniqid(basename($script["SRC"])),
                    src:  $script['SRC'],
                    deps: $script['DEPS'],
                    ver : $script['VER'] ? $script['VER'] : false ,
                    args: $script['FOOTER'],
                );
            }
        }
   }

    private function condition(string $key) {
        $conditions = include  dirname(__DIR__ , 2)."/config/conditions.php";
        return $conditions["$key"];
    }


}

