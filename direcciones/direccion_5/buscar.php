<?php
    if(isset($_POST['query'])) {
        $query = strtolower($_POST['query']);
        $directorio = __DIR__ . '/../../documentos/dir5';
        $resultados = [];

        //recorrer subcarpetas
        $subcarpetas = ['Boletines', 'Documentos de Telesalud'];

        foreach ($subcarpetas as $sub){
            $ruta = $directorio . '/' . $sub;
            if (is_dir($ruta)) {
                $archivos = scandir($ruta);
                foreach ($archivos as $archivo){
                    if ($archivo != '.' && $archivo != '..'){
                        if(stripos($archivo,$query) !== false) {
                            $url = '/dgmoss-project/documentos/dir5/' . rawurlencode($sub) . '/' . rawurlencode($archivo);
                            $resultados[] = "<li><a href=\"$url\" target=\"_blank\">$archivo</a></li>";
                        }
                    }
                }
            }
        }

        //Imprimir resultados
        if (count($resultados) > 0) {
            echo "<h5>Resultados encontrados: </h5><ul>" . implode('',$resultados) . "</ul>";
        } else {
            echo "<p>No se encontraron resultados.</p>";
        }

    }

?>