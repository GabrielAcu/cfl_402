<?php
// ==========================
//   PAGINACION.PHP (SEPARADO)
// ==========================
?>

<?php 
    function paginacion($pagina_actual, $total_paginas){
        $enlaces = "";
        
        if ($total_paginas > 1){
            // Enlace a la primera página 
            
            if($pagina_actual == 1){
                $enlaces .= "<a href='?pagina=1' class='active'>Primera</a>";
            } else {
                $enlaces .= "<a href='?pagina=1' class=''>Primera</a>";
            }
            
            // Enlace a la página anterior 
            if ($pagina_actual > 1){
                $enlaces .= "<a href='?pagina=".($pagina_actual - 1)."'>Anterior</a>";
            }
            
            // Mostrar enlaces para algunas páginas (ej: 5 páginas alrededor de la actual)
            
            $rango = 2; // Número de páginas a mostrar antes y después de la actual
            for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++){
            
                $enlaces .= "<a href='?pagina=$i' class='". (($i == $pagina_actual) ? 'active':'')."'>$i</a>";
            }
            
            // Enlace a la página siguiente 
            if ($pagina_actual < $total_paginas){
                $enlaces .= "<a href='?pagina=".($pagina_actual + 1)."'>Siguiente</a>";
            }

            // Enlace a la última página 
            $enlaces .= "<a href='?pagina=$total_paginas' class='".(($pagina_actual == $total_paginas) ? 'active':'')."'>Última</a>";
        }
        
        return $enlaces;
    }
    ?>

