# SkillBridge - Corrección final de accesibilidad y bilingüismo

Esta versión incluye:

- Interfaz bilingüe EN/ES reforzada en Home, empleos, empresas, perfil, postulaciones, publicar vacante, recursos, accesibilidad, privacidad y términos.
- Botón de idioma que muestra el idioma actual y guarda la preferencia.
- Modo lectura por hover/focus, con área de detección más amplia y sin bloquear enlaces o botones.
- El modo lectura no encierra ni marca visualmente el texto.
- Todos los íconos Font Awesome reciben aria-label, title y etiqueta de lectura.
- El botón Reset es visible en alto contraste y no cambia el idioma.
- Preferencias por cuenta: idioma, modo oscuro, alto contraste, modo lectura y escala de texto.
- Vacantes bilingües con campos en inglés y español.
- Hero badge "Inclusive employment platform" eliminado.
- Archivos listos para Render + Aiven: Dockerfile, render.yaml y config/conexion.php por variables de entorno.

Pruebas realizadas:

- PHP lint en todos los archivos PHP.
- JavaScript syntax check en todos los archivos JS.

Después de reemplazar la carpeta en htdocs, usar Ctrl + F5.
