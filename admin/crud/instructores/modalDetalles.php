<?php
// Modal para Ver Instructor
?>
<div id="modalVerInstructor" class="modal">
    <div class="modal-content">
        <span class="cerrar" id="cerrarVerInstructor">&times;</span>
        <!-- El contenido se llena via JS -->
        <div id="contenidoInstructor"></div>
        
        <div class="modal-buttons" style="justify-content: center; margin-top: 20px;">
             <button type="button" class="btn-cancel" onclick="document.getElementById('modalVerInstructor').style.display='none'">Cerrar</button>
        </div>
    </div>
</div>
