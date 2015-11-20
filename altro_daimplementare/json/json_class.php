<?php

class JSonBuilder
{
    public $jsonstring = "{";
    
    public function addElement($chiave, $valore)
    {
        if($this->jsonstring != "{")
            $this->jsonstring .= ',"'.$chiave.'":'.$this->pulisciStringa($valore).'';
        else
            $this->jsonstring .= '"'.$chiave.'":'.$this->pulisciStringa($valore).'';
    }
    
    function pulisciStringa($stringa)
    {
        $newstring = json_encode($stringa);
        return $newstring;
    }
    
    public function build()
    {
        $this->jsonstring .= "}";
    }
    
    public function getJsonString()
    {
        echo $this->jsonstring;
    }
    
}

?>