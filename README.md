
Classe que possui a responsabilidade de retornar os dados de localização de um IP


Exemplo
<php?
    try{
        $geoIP = new GeoIP();
        $arrLocation = $geoIP->getLocation();
        print_r($arrLocation);
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
?>
    
O trecho de código acima irá retornar
Array
(
    [ip] => 189.13.172.91
    [country_code] => BR
    [country_name] => Brazil
    [region_code] => 15
    [region_name] => Minas Gerais
    [city] => Coronel Fabriciano
    [zipcode] => 
    [latitude] => -19.5167
    [longitude] => -42.6333
    [metro_code] => 
    [areacode] => 
)
