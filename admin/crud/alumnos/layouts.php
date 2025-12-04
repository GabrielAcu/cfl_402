
<?php

function fallido($motivo){
        echo"<div class='fallido'>
            <div class='titulo-fallido'> Acción Fallida </div>

            <div class='motivo'> $motivo  </div>
            
            <a class='add_link2'  href='index.php'> Volver al listado </a>
            </div>";
        }


function exitoso( $motivo){
        echo"<div class='exitoso'>
            <div class='titulo-exitoso'> Acción Exitosa </div>

            <div class='motivo'> $motivo  </div>
            
            <a class='add_link2'  href='index.php'> Volver al listado </a>

            </div>";
        }

function nochange( $motivo){
        echo"<div class='no-change'>
            <div class='titulo-exitoso'> Sin Cambios </div>

            <div class='motivo'> $motivo  </div>

            <a class='add_link2'  href='index.php'> Volver al listado </a>

            </div>";
        }
?>