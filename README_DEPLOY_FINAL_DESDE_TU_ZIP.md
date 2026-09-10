# SkillBridge - Deploy final

Este paquete fue armado desde el ZIP actual enviado por el usuario.

## Base de datos
Importar en Aiven MySQL:

```text
database/skillbridge_db_IMPORTAR_AIVEN.sql
```

## Variables en Render

```text
DB_HOST=host_de_aiven
DB_PORT=puerto_de_aiven
DB_NAME=nombre_de_base
DB_USER=usuario_de_aiven
DB_PASSWORD=password_de_aiven
```

Si Aiven requiere certificado CA, agregar también:

```text
DB_SSL_CA=/etc/secrets/ca.pem
```

## Deploy

1. Subir esta carpeta a GitHub.
2. Crear Web Service en Render.
3. Seleccionar Docker.
4. Agregar variables de entorno.
5. Deploy.

## Nota
Las carpetas `img/perfiles` y `cv` funcionan en local. En Render free pueden perder archivos subidos cuando el servicio reinicia o se redeploya.
