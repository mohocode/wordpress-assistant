<?php

namespace App\Base;

class ThemeSetup
{

    private array $menus = [];
    private array $addSupports  = [];
    private array $removeSupport  = [];
    private array $sidebars  = [];
    public  array $conditionsEnqueue = [];
    private string $textDomain;
    public function __construct()
    {

        add_action('after_setup_theme', [$this, 'bootTheme']);

        add_action('after_setup_theme', [$this, 'registerMenus'], 0);

        add_action('widgets_init', [$this, 'registerSidebar']);

        // add_action("wp_enqueue_scripts", [$this, 'enqueueScript']);

        add_action('after_setup_theme', [$this, 'loadTextDomain']);

      
        $this->extraSetup();
    }

    public function setMenu($item)
    {
        array_push($this->menus, $item);
    }

    public function setSupport(string $item)
    {
        array_push($this->addSupports, $item);
    }

    public function setRmSupport($item)
    {
        array_push($this->removeSupport, $item);
    }

    public function setSidebar($item)
    {
        array_push($this->sidebars, $item);
    }
    public function bootTheme() {

        if (count($this->addSupports) > 0)
            foreach ($this->addSupports as $key) {
                add_theme_support($key);
            }

        if (count($this->removeSupport) > 0)
            foreach ($this->removeSupport as $key) {
                remove_theme_support([$key]);
            }
    }

    public function registerSidebar()
    {
        if (count($this->sidebars) > 0)
            foreach ($this->sidebars as $key) {
                register_sidebar($key);
            }
    }

    public function registerMenus()
    {

        if (count($this->menus) > 0) {

            register_nav_menus($this->menus[0]);
        }
    }

    public function loadTextDomain()
    {

        if (isset($this->textDomain) && $this->textDomain)
            load_theme_textdomain($this->textDomain, get_template_directory() . '/languages');
    }

    public function setTextDomain(string $name)
    {
        $this->textDomain = $name;
    }

    function getThemeTextDomain()
    {
        $theme = wp_get_theme();
        return $theme->get($this->textDomain);
    }

    public function extraSetup(array $options = [])
    {

        foreach ($options as $option) {

            switch ($option) {

                case 'disable_Gutenberg':
                    add_filter('use_block_editor_for_post', '__return_false');
                    break;
            }
        }
    }
}
