
<?php

function fallido($motivo){
        echo"<div class='fallido'>
            <div class='titulo-fallido'> Acción Fallida </div>

            <div class='motivo'> $motivo  </div>
            </div>";
        }


function exitoso( $motivo){
        echo"<div class='exitoso'>
            <div class='titulo-exitoso'> Acción Exitosa </div>

            <div class='motivo'> $motivo  </div>
            </div>";
        }

function nochange( $motivo){
        echo"<div class='no-change'>
            <div class='titulo-exitoso'> Sin Cambios </div>

            <div class='motivo'> $motivo  </div>
            </div>";
        }
?>