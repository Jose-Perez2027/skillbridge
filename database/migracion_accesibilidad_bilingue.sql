-- SkillBridge accessibility and bilingual vacancy migration
-- Run this in phpMyAdmin only if the columns do not exist yet.

ALTER TABLE usuarios
ADD COLUMN idioma_preferido VARCHAR(5) DEFAULT 'en',
ADD COLUMN modo_oscuro TINYINT(1) DEFAULT 0,
ADD COLUMN alto_contraste TINYINT(1) DEFAULT 0,
ADD COLUMN modo_lectura TINYINT(1) DEFAULT 0,
ADD COLUMN escala_texto DECIMAL(3,2) DEFAULT 1.00;

ALTER TABLE vacantes
ADD COLUMN titulo_en VARCHAR(150) NULL AFTER titulo,
ADD COLUMN titulo_es VARCHAR(150) NULL AFTER titulo_en,
ADD COLUMN descripcion_en TEXT NULL AFTER descripcion,
ADD COLUMN descripcion_es TEXT NULL AFTER descripcion_en,
ADD COLUMN responsabilidades_en TEXT NULL AFTER responsabilidades,
ADD COLUMN responsabilidades_es TEXT NULL AFTER responsabilidades_en,
ADD COLUMN requisitos_en TEXT NULL AFTER requisitos,
ADD COLUMN requisitos_es TEXT NULL AFTER requisitos_en,
ADD COLUMN habilidades_en TEXT NULL AFTER habilidades,
ADD COLUMN habilidades_es TEXT NULL AFTER habilidades_en;

UPDATE vacantes
SET
    titulo_en = IF(titulo_en IS NULL OR titulo_en = '', titulo, titulo_en),
    titulo_es = IF(titulo_es IS NULL OR titulo_es = '', titulo, titulo_es),
    descripcion_en = IF(descripcion_en IS NULL OR descripcion_en = '', descripcion, descripcion_en),
    descripcion_es = IF(descripcion_es IS NULL OR descripcion_es = '', descripcion, descripcion_es),
    responsabilidades_en = IF(responsabilidades_en IS NULL OR responsabilidades_en = '', responsabilidades, responsabilidades_en),
    responsabilidades_es = IF(responsabilidades_es IS NULL OR responsabilidades_es = '', responsabilidades, responsabilidades_es),
    requisitos_en = IF(requisitos_en IS NULL OR requisitos_en = '', requisitos, requisitos_en),
    requisitos_es = IF(requisitos_es IS NULL OR requisitos_es = '', requisitos, requisitos_es),
    habilidades_en = IF(habilidades_en IS NULL OR habilidades_en = '', habilidades, habilidades_en),
    habilidades_es = IF(habilidades_es IS NULL OR habilidades_es = '', habilidades, habilidades_es);

UPDATE usuarios
SET
    idioma_preferido = IF(idioma_preferido IS NULL OR idioma_preferido = '', 'en', idioma_preferido),
    modo_oscuro = IFNULL(modo_oscuro, 0),
    alto_contraste = IFNULL(alto_contraste, 0),
    modo_lectura = IFNULL(modo_lectura, 0),
    escala_texto = IFNULL(escala_texto, 1.00);
