    <!-- // ==========================
    //   PAGINACIÓN
    // ========================== -->
    <?php
    function paginacion($total_paginas, $pagina_actual) {
        if ($total_paginas <= 1) {
            return; // No mostrar nada si no hay más páginas
        }
    
        echo "<div class='pagination'>";
    
        // 👉 Primera página
        echo "<a href='?pagina=1' class='" . ($pagina_actual == 1 ? "active" : "") . "'>
                <img class='svg_lite' src='/cfl_402/assets/svg/left_arrow.svg'>
              </a>";
    
        // 👉 Página anterior
        if ($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>
                    <img class='svg_lite' src='/cfl_402/assets/svg/left_one_arrow.svg'>
                  </a>";
        }
    
        // 👉 Rango de páginas centrado
        $rango = 2;
        for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++) {
            echo "<a href='?pagina=$i' class='" . (($i == $pagina_actual) ? 'active' : '') . "'>$i</a>";
        }
    
        // 👉 Página siguiente
        if ($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>
                    <img class='svg_lite' src='/cfl_402/assets/svg/right_one_arrow.svg'>
                  </a>";
        }
    
        // 👉 Última página
        echo "<a href='?pagina=$total_paginas' class='" . (($pagina_actual == $total_paginas) ? 'active' : '') . "'>
                <img class='svg_lite' src='/cfl_402/assets/svg/right_arrow.svg'>
              </a>";
    
        echo "</div>";
    }
     
    ?>