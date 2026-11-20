# PPLL Frontal Client

Esta librería contiene las clases necesarias para establecer peticiones
al servicio <Skeleton Service>.

## Instalación

Para instalar este paquete es necesario meterlo como requisito en nuestro 
`composer.json` de la siguiente forma:

```
composer require "vocento/frontal-client"
```

## Declaración del servicio en Symfony

Modificaremos el fichero `Resources/config/services/models/frontal.yml` para poner lo siguiente:

```yaml
parameters:
    http.frontal.ppll.client.host: "http://des-api-frontal-ppll.srv.vocento.in"
    http.frontal.abc.client.host: "http://des-api-frontal-abc.srv.vocento.in"

services:
    frontal.ppll.client:
        alias: 'frontal.ppll.http.client'
        
    frontal.abc.client:
        alias: 'frontal.abc.http.client'

    frontal.ppll.http.client:
       class: Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient
       public: false
       arguments:
           - '%http.frontal.ppll.client.host%'
     
    frontal.abc.http.client:
        class: Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient
        public: false
        arguments:
            - '%http.frontal.abc.client.host%'
```

## Modo de uso

```php
...
use Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient;

class Foo
{
    ...
    public function method()
    {
        ...
        /** @var HttpPPLLFrontalClient $frontalClient */
        // Obtener el servicio frontal.ppll.client
        $frontalClient = $this->container->get('frontal.ppll.client');

        // Obtener el layout del frontal de un medio en formato twig
        $frontalResponse = $frontalClient->getTwigLayout('des-www.laverdad.es', 'deportes');
        $twigLayout = $frontalResponse->getBody()->getContents();

        // Obtener el layout del frontal de un medio en formato html
        $frontalResponse = $frontalClient->getHtmlLayout('des-www.laverdad.es', 'deportes');
        $htmlLayout = $frontalResponse->getBody()->getContents();
        
        // Obtener el layout del frontal de un medio en formato html con parámetros GET
        $frontalResponse = $frontalClient->getHtmlLayout('des-www.laverdad.es', 'deportes', '/', [], null, ['param1' => 'parameter1']);
        $htmlLayout = $frontalResponse->getBody()->getContents();
    }
}
```