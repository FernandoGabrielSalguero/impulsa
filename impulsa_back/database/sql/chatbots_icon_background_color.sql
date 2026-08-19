-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).
-- Color de fondo del icono del chatbot (widget público + preview del panel).

ALTER TABLE chatbots
  ADD COLUMN icon_background_color varchar(7) NOT NULL DEFAULT '#009EE3' AFTER avatar_url;
