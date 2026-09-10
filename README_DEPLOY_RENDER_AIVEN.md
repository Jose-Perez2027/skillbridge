# SkillBridge - Render + Aiven

Esta versión está preparada para ejecutarse localmente en XAMPP y para desplegarse en Render usando Docker y una base MySQL en Aiven.

## Variables de entorno para Render

Configura estas variables en el Web Service de Render:

```text
DB_HOST=host-de-aiven
DB_PORT=puerto-de-aiven
DB_NAME=nombre-base
DB_USER=usuario-aiven
DB_PASSWORD=password-aiven
```

Si Aiven requiere certificado SSL, agrega un Secret File con el certificado CA y configura:

```text
DB_SSL_CA=/etc/secrets/ca.pem
```

## Base de datos

La carpeta `database/` incluye:

```text
migracion_accesibilidad_bilingue.sql
```

Ese archivo agrega preferencias por cuenta y campos bilingües para vacantes.

## Accesibilidad incluida

- Cambio de idioma EN / ES.
- Modo lectura por hover y focus de teclado.
- El modo lectura no bloquea botones ni enlaces.
- Reset de accesibilidad sin cambiar idioma.
- Preferencias guardadas por cuenta cuando el usuario inicia sesión.
- Íconos con descripción para lectura.
- Formularios con labels y mensajes accesibles.

## Vacantes bilingües

El formulario de publicación pide versión en inglés y versión en español para título, descripción, responsabilidades, requisitos y habilidades. Los campos fijos como modalidad, categoría y tipo de empleo se traducen desde el sistema.
