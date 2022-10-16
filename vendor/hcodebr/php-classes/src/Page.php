<?php

namespace Hcode;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options = []; //final options to the TPL construction
    private $defaults = [
        "data"=>[]
    ];

    public function __construct($opts = array())//$opts options passed via SLIM
    {
        $this->options = array_merge($this->defaults,$opts);
        // config of rainTPL 
        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"] . "/views/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
            "debug"         => true // set to false to improve the speed (throughout the course this is set to false, but I'm setting to true so I can learn more and it can help me with debuging.)
        );

        Tpl::configure( $config );

        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        $this->tpl->draw("header");
        
    }

    private function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->tpl->assign($key,$value);
        }
    }

    public function setTpl($name,$data = array(), $returnHTML = false)
    {
        $this->setData($data);

        return $this->tpl->draw($name,$returnHTML);

    }

    public function __destruct()
    {
        $this->tpl->draw("footer");        
    }
}