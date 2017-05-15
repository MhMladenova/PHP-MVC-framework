<?php

namespace MVCF\Routers;

class DefaultRouter implements \MVCF\Routers\IRouter {
    
    public function getURI() {
        return substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
    }
}
