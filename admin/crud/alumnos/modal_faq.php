<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link rel="stylesheet" href="modal.css">

</head>
<body>

</html>

<div id="modalFaq" class="modal">
    <div class="faq-content">
    <span class="cerrar">&times;</span>
    <h2> Explicación Básica </h2>

    <div class="modalCurso">
            <?php
            require_once dirname(__DIR__, 3) . '/config/path.php';
            require_once BASE_PATH . '/config/csrf.php';
            echo getCSRFTokenField();
            ?>

            
        <div class='fila_faq'>
            <div class='campo_faq'>
                <img class='svg_lite' src='/cfl_402/assets/svg/pencil.svg' title='Modificar'>
                <p class="faq_text"> Modificar Alumno </p>
            </div>
            <div class="campo_faq">
                <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' title='Eliminar'>
                <p class="faq_text"> Eliminar Alumno </p>
            </div>
        </div>
        
        <div class='fila'>
            <div class="campo_faq">
               <img class="svg_lite" src="/cfl_402/assets/svg/plus.svg" title="Inscribir a un curso">
                <p class="faq_text"> Inscribir Alumno a un Curso </p>
            </div>
        

            <div class="campo_faq">
                <img class="svg_lite" src="/cfl_402/assets/svg/contact.svg" title="Contactos">
                <p class="faq_text">  Ver Contactos de Alumno  </p>
            </div>
        </div>



        
        </div>
        </div>
       

</div>

</body>