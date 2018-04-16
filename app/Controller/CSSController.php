<?php
    class CSSController extends AppController
    {
      public function index()
      {
        $this->layout = false;
        $this->response->type( "text/css" );
        echo "@charset \"UTF-8\"; " . PHP_EOL;

        $fileName = pathinfo( $this->request->params["pass"][0], PATHINFO_FILENAME ) ;
        $this->render( $fileName );
      }
    }
?>
