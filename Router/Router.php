<?php
/*
 * VERSIONE ORIGINALE
 * <?php
namespace  Router;
class Router
{
private array $routes=[];
public function addRoute($method,$url,$controller,$action):void {
    $this->routes[$method][$url]=[
        'controller' =>$controller,
        'action' => $action
    ];
}
public function match($url,$method):array {
    $values=[];
    if(array_key_exists($url,$this->routes[$method])) {
        $values['controller'] = $this->routes[$method][$url]['controller'];
        $values['action'] = $this->routes[$method][$url]['action'];
    }
    return $values;
}


}
 * */
namespace Router;

class Router
{
    // Array che conterrà tutte le rotte organizzate per metodo HTTP (GET, POST, ecc.)
    private array $routes = [];

    /*
     * Aggiunge una nuova rotta al router.
     * Se l'URL contiene parametri dinamici (es. {id}), li trasforma in espressioni regolari.
     *
     * $method     Metodo HTTP (GET, POST, ecc.)
     * $url        URL della rotta (es. "events_edit/{id}")
     *  $controller Nome del controller da chiamare
     *  $action     Metodo (azione) da eseguire nel controller
     */
    public function addRoute($method, $url, $controller, $action): void {
        // Converte i parametri dinamici tra graffe in gruppi regex con nome
        // Esempio: 'events_edit/{id}' diventa 'events_edit/(?P<id>[^/]+)'
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<\1>[^/]+)', $url);

        // Delimita il pattern per l'intera stringa URL
        $pattern = '#^' . $pattern . '$#';

        // Salva la rotta nel metodo corretto
        $this->routes[$method][] = [
            'pattern' => $pattern,       // Pattern regex per il match
            'controller' => $controller, // Controller da istanziare
            'action' => $action          // Metodo da invocare nel controller
        ];
    }

    /*
     * Tenta di trovare una rotta che corrisponda all'URL e metodo specificato.
     * Se la trova, ritorna controller, action e parametri (se presenti).
     *
     *  $url    URL della richiesta (già normalizzato)
     *  $method Metodo HTTP della richiesta
     *  array  Array con chiavi 'controller', 'action', 'params' (se trovata), o array vuoto
     */
    public function match($url, $method): array {
        // Se non ci sono rotte definite per il metodo richiesto, ritorna array vuoto
        if (!isset($this->routes[$method])) {
            return [];
        }

        // Scorre tutte le rotte per il metodo specificato
        foreach ($this->routes[$method] as $route) {
            // Prova a far combaciare la regex con l'URL corrente
            if (preg_match($route['pattern'], $url, $matches)) {
                // Estrae solo i match nominati (i parametri dinamici), scartando gli indici numerici
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params
                ];
            }
        }

        // Nessuna corrispondenza trovata
        return [];
    }
}


