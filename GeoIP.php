<?php
/** 
 * @author Administrador
 * 
 * 
 */
class GeoIP
{
    private $host = 'http://freegeoip.net';
    private $returnType = 'json'; //csv, xml our json
    private $ip = '';
    private $url = '';
    private $location;
    private $allowedReturnType = array('xml','json','csv');
    
    
    /**
     * Atribuir um ip manualmente
     * @param $ip
     * @return $this
     */
    public function setIp($ip)
    {
        if(!filter_var($ip, FILTER_VALIDATE_IP))
            throw new Exception(sprintf('O %s não é um endereço válido!',$ip));
        else
            $this->ip = $ip;

       return $this;
    }

    
    /**
     * Atribui um tipo de retorno
     * @param $returnType
     */
    public function setReturnType($returnType)
    {
        if(!in_array($returnType,$this->allowedReturnType))
            throw new Exception(sprintf('O tipo de retorno %s não é suportado!',$returnType));
        else
            $this->returnType = $returnType;            
            
        return $this;    
    }
    
    
    /**
     * Construtor
     * Recupera o ip constroi a url da requisição
     * @param string $ip Ip a ser analisado
     */
    public function GeoIP($ip = '')
    {
        if($ip) //Caso algum ip for informado
            $this->setIp($ip)->makeUrl(); //Atribui o ip e gera a url de consulta
        else    
            $this->makeUrl();  //Caso nenhum ip for informado gera url de consulta
    }
    
    
    /**
     * Gera a url de consulta e recupera as informações de localização do IP
     * @uses GeoIP::makeLocation() Para recuperar as informações de localização do IP
     * @return $this
     */
    public function makeUrl()
    {
        $this->ip = (!$this->ip)? $_SERVER['REMOTE_ADDR'] : $this->ip;
        $this->url =  sprintf('%s/%s/%s',$this->host,$this->returnType, $this->ip);  
        $this->makeLocation();
        return $this;
    }
    
    
    /**
     * Recupera as informações de localização
     * @return Void
     */
    private function makeLocation()
    {
        $this->location = file_get_contents($this->url);    
    }
    
    
    /**
     * Recupera a localização formata de acordo com o $this->returnType 
     */
    public function getLocation()
    {
        //Caso a url ainda não estiver definida
        if(!$this->location) $this->makeUrl();
        
        switch ($this->returnType){
            case 'json' :
                return $this->returnJson();
                break;

            case 'xml' :
                return $this->returnXml();
                break;

            case 'csv' :
                return $this->returnCsv();
                break;
        }
    }
    
    
    /**
     * Return um array 
     * @return Array 
     */
    private function returnJson()
    {
        return  json_decode(stripslashes($this->location),true);
    }
    
    
    /**
     * Trata o retorno como CSV
     * @return Array
     */
    private function returnCsv()
    {
        echo $this->location; exit();
        $arrReturn = array();
        $arrReturn = explode(",",$this->location); 
        return $arrReturn;
    }   
    
    
    /**
     * Trata o retorno no formato XML
     * @return Array
     */
    private function returnXml()
    {
       $arrReturn = array();
       $xml = simplexml_load_string($this->location);
       foreach ($xml as $key => $value)
           $arrReturn[$key] = (string)$value;
       return $arrReturn;     
    }  
}
