<?php
/**
 * Router
 * Manual routing engine that matches incoming request paths to Controller actions
 * using Regular Expressions, without relying on any framework's routing system.
 */
class Router
{
    private $routeTable = [];

    /**
     * Registers a new route.
     * @param string $verb GET or POST
     * @param string $pathBlueprint e.g. "/photo/{id}"
     * @param array $handlerPair e.g. ["PhotoController", "show"]
     */
    public function add($verb, $pathBlueprint, $handlerPair)
    {
        $this->routeTable[] = [
            "method" => strtoupper($verb),
            "pattern" => $pathBlueprint,
            "action" => $handlerPair
        ];
    }

    /**
     * Matches the current request against all registered routes and dispatches
     * to the matching Controller action, passing any extracted parameters.
     * @param string $incomingVerb
     * @param string $incomingPath
     */
    public function dispatch($incomingVerb, $incomingPath)
    {
        $incomingPath = rtrim($incomingPath, "/");
        if ($incomingPath === "") {
            $incomingPath = "/";
        }

        foreach ($this->routeTable as $candidateRoute) {
            if ($candidateRoute["method"] !== strtoupper($incomingVerb)) {
                continue;
            }

            $expressionPattern = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $candidateRoute["pattern"]);
            $expressionPattern = "#^" . $expressionPattern . "$#";

            if (preg_match($expressionPattern, $incomingPath, $extractedParams)) {
                array_shift($extractedParams);

                $handlerClass = $candidateRoute["action"][0];
                $handlerMethod = $candidateRoute["action"][1];

                require_once __DIR__ . "/../controllers/" . $handlerClass . ".php";
                $handlerInstance = new $handlerClass();
                call_user_func_array([$handlerInstance, $handlerMethod], $extractedParams);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}