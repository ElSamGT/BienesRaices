
    <main class="contenedor seccion">
        <h1>Contacto</h1>

        <?php

        if($mensaje){ ?>
            <p class='alerta exito'> <?php echo $mensaje; ?></p>;
        <?php }    ?>

        <picture>
            <source srcset="build/img/destacada3.webp" type="image/webp">
            <source srcset="build/img/destacada3.jpg" type="image/jpeg">
            <img loading="lazy" src="build/img/destacada3.jpg" alt="Imagen Contacto">
        </picture>

        <h2>Llene el formulario de Contacto</h2>

        <form action="/contacto" method="POST" class="formulario">
            <fieldset>
                <legend>Informacion Personal</legend>

                <label for="nombre">Nombre</label>
                <input type="text" placeholder="Tu Nombre" name="contacto[nombre]" id="nombre" required>
                <label for="email">E-mail</label>
                <input type="email" placeholder="Tu Email" name="contacto[email]" id="email" required>
                
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="contacto[mensaje]" required></textarea>
            </fieldset>
            <fieldset>
                <legend>Informacion Sobre la Propiedad</legend>
                <label for="opciones">Vende o Compra:</label>
                <select id="opciones" name="contacto[tipo]" required>
                    <option value="" disabled selected>--Seleccione--</option>
                    <option value="Compra">Compra</option>
                    <option value="Vende">Vende</option>
                </select>
                <label for="presupuesto">Presupuesto</label>
                <input type="number" placeholder="Tu Presupuesto" name="contacto[precio]" id="presupuesto" required>
                
            </fieldset>

            
            <fieldset>
                <legend>Informacion Sobre la Propiedad</legend>
                
                <p>Como desea ser contactado</p>
                <div class="forma-contacto">
                    <label for="contactar-telefono">Teléfono</label>
                    <input name="contacto[contacto]" type="radio" value="telefono" id="contactar-telefono" required>
                    <label for="contactar-email">E-mail</label>
                    <input name="contacto[contacto]" type="radio" value="email" id="contactar-email" required>
                </div>

                <div id="contacto"></div>
            
            </fieldset>

            <input type="submit" value="Enviar" class="boton-verde">
        </form>
    </main>