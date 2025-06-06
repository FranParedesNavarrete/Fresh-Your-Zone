<p align="center"><img src="public/assets/images/logo/fzy-logo-dark.png" width="400" alt="FZY Logo"></p>

## Sobre FZY

**Fresh Your Zone (FZY)** es una aplicación web pensada para la compra y venta de ropa entre usuarios. Permite a compradores y vendedores registrarse, subir productos, gestionar pedidos y comunicarse de forma sencilla. Nuestro objetivo es ofrecer una experiencia rápida, segura y visualmente atractiva para quienes buscan renovar su estilo o dar una segunda vida a sus prendas.

## Funcionalidades Principales
- Registro y autenticación de usuarios 
- Subida de productos con imágenes, descripciones y categorías
- Sistema de pedidos y gestión de ventas
- Panel de administración con gestión de usuarios, productos y pedidos
- Diseño responsive optimizado para dispositivos móviles y escritorio
- Modo claro y oscuro 

## Tecnologías utilizadas
- Laravel
- MySQL
- Bootstrap 5
- Git y  GitHub para control de versiones

## Capturas de pantalla
 ### Página principal
 ![Index](public/assets/images/screenshots/index.png)

 ### Página de producto
 ![Product](public/assets/images/screenshots/product-details.png)

 ### Pasarela de pago
 ![Payment gateway](public/assets/images/screenshots/paymen-gateway.png)

## FZY Sponsors
Queremos agradecer a los sponsors que apoyan el desarrollo de FZY. Si estas interesado en ser un sponsor, por favor visita este [enlace de afiliación](https://www.google.com/url?sa=i&url=https%3A%2F%2Fx.com%2FDefensaAnimalZA%2Fstatus%2F1450733997156360194&psig=AOvVaw2n66AvogwpcA9r8J4LHViI&ust=1748433841333000&source=images&cd=vfe&opi=89978449&ved=0CBQQjRxqFwoTCOD_-uHNw40DFQAAAAAdAAAAABAE).

## Instalación del proyecto
 ### 1. Clonar el repositorio
 Ejecuta en tu terminal el siguiente comando para clonar el repositorio.<br>
 `git clone https://github.com/FranParedesNavarrete/Fresh-Your-Zone.git`

 ### 2. Instalar las dependencias
 Ejecuta en tu terminal el siguiente comando para instalar composer dentro de la carpeta del proyecto.<br>
 `composer install`<br>
 Instala: <br>
 `npm install && npm run dev`

 ### 3. Crear el enlace simbólico
 Ejecuta: <br>
 `php artisan storage:link`

 ### 4. Crear el archivo .env y configurar las credenciales
 Configura el archivo `.env`

 ### 5.Ejecutar las migraciones
 `php artisan migrate:fresh`
 > [!CAUTION]
 > Ejecutar `php artisan migrate:fresh` elimina todos los datos previos en tu base de datos.

 ### 6. Arrancar el servidor
 `php artisan serve`<br>
 > [!NOTE]
 > Si utilizas Laragon no hace falta ejecutar el comando, solo inicia el servidor desde la interfaz.

## Licencia
Este proyecto usa la licencia [MIT](https://opensource.org/licenses/MIT).

## Autor
- Fran Paredes Navarrete - [@FranParedesNavarrete](https://github.com/FranParedesNavarrete)

<p align="center">Gracias por visitar FZY</p> 