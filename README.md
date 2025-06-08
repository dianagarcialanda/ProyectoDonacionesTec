# 📚 Sistema de Gestión de Donaciones - ITO

Sistema web desarrollado como proyecto escolar para el Instituto Tecnológico de Orizaba. Permite registrar, consultar y gestionar donaciones, ofreciendo además visualización estadística y generación de comprobantes.

---

## 📝 Descripción del proyecto

El sistema permite:

- Registrar donantes y sus donaciones (en especie o monetarias).
- Consultar el historial de donaciones.
- Generar comprobantes en PDF.
- Visualizar estadísticas mediante dashboards.
- Gestionar usuarios con diferentes roles (administrador y donante).

---

## 🖼️ Captura de pantalla

Guarda tu imagen en una carpeta llamada `img` dentro del proyecto y asegúrate de que se llame `captura_inicio.png`.

![Captura de inicio](./img/captura_inicio.png)

---

## 🛠️ Instalación y ejecución

### Requisitos:
- XAMPP (o cualquier servidor local con PHP y MySQL)
- Navegador web
- phpMyAdmin

### Pasos:
1. Coloca la carpeta del proyecto dentro de `C:\xampp\htdocs\`
2. Abre XAMPP y ejecuta los servicios **Apache** y **MySQL**
3. Abre tu navegador y entra a `http://localhost/phpmyadmin`
4. Crea una base de datos llamada `donaciones_ito`
5. Importa el archivo `bd_donaciones.sql` incluido en el proyecto
6. Configura la conexión a la base de datos en:
