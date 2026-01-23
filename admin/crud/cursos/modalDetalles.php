<?php
// Modal para Ver Curso
?>
<div id="modalVerCurso" class="modal">
    <div class="modal-content">
        <span class="cerrar" id="cerrarVerCurso">&times;</span>
        <!-- El contenido se llena via JS -->
        <div id="contenidoCurso"></div>
        
        <div class="modal-buttons" style="justify-content: center; margin-top: 20px;">
             <button type="button" class="btn-cancel" onclick="document.getElementById('modalVerCurso').style.display='none'">Cerrar</button>
        </div>
    </div>
</div>
