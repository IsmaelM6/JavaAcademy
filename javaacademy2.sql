-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-08-2024 a las 01:00:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `javaacademy2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `adminId` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`adminId`, `nombre`, `password`, `nombre2`) VALUES
(1, 'rootUnico@gmail.com', '$2y$10$z2jnF319HJyLzu4sgyxGN.w5o9PY6Npo38u.L3VDAYvx3Zme9FFhi', 'SuperRoot');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `alumnoId` int(11) NOT NULL,
  `nombreUsuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`alumnoId`, `nombreUsuario`, `email`, `password`, `fechaCreacion`, `estado`, `reset_token`, `reset_expiry`) VALUES
(1, 'Luis Flores Castro', 'lsflorescastro@gmail.com', '$2y$10$bcyYVS.uOacgpii5RH2O0uPVeRHjYhtrjxO6ysTsKopm0iu2rIPIO', '2024-07-04 12:02:24', 1, NULL, NULL),
(2, 'Yonathan Uriel Escudero', 'yonathan@gmail.com', '$2y$10$Iuo4FjceMIpV1BaRAu3NJOWaok75.AUbvapCVDfKlDpmqOJButbRC', '2024-07-04 12:02:24', 1, NULL, NULL),
(3, 'Israel Molina', 'molinais@gmail.com', '$2y$10$3qPWclUyEItH4KQCwFDfZ.pbH6oHn313LJKsMTxFnHzLLAtjcmwgi', '2024-07-04 12:02:24', 0, NULL, NULL),
(4, 'Ismael Beltran', 'tokis060516@gmail.com', '$2y$10$2jfcLEXMhmV5rKpk6U/hROmoMCeD4EW0TXG9jgevjTKeeYGhHWxAC', '2024-07-04 12:02:24', 0, NULL, NULL),
(5, 'Cesar Raso', 'cesarraso96@gmail.com', '$2y$10$Y8INghAN14E4Yqq9z1JnmOze4MyuDSSLw9pOq.2zcAXu.EQpy4DGq', '2024-07-04 12:02:24', 1, NULL, NULL),
(6, 'Miguel Hernandez', 'lancers260499@gmail.com', '$2y$10$C6Pe2fayhqhCyTfKh1OAlejr69VET.UKj7VifTLajJHuOFvkxYU36', '2024-07-27 14:50:15', 1, NULL, NULL),
(7, 'Ismael Miguel Hernandez', 'miguelhdez026@gmail.com', '$2y$10$cOH8h4mWviCa4/usnNdiTepgYEH93.zIPXSxQfYzjdwpL5D9kaHta', '2024-07-27 18:11:19', 1, NULL, '2024-07-29 04:41:15'),
(8, 'Jordy Guerrero', 'jordyguerrero255@gmail.com', '$2y$10$HFfBmOcyleofmlZaQ0rIfOzEMZ2FN4GA6NNma9w2Rjxp4q9pD74H6', '2024-07-29 13:30:19', 1, NULL, NULL),
(9, 'Abimael Beltran', 'mikegari840@gmail.com', '$2y$10$Kd982eSud038dj/9WMArKes7pDITz5FUDA3HsEWjYSSBO2jbPPS0m', '2024-08-02 18:35:08', 1, NULL, NULL),
(10, 'Roman Fuentes', 'roman@gmail.com', '$2y$10$9DmfdmuFMAdt50GOkyw.g.Q1tCOtsD2kLTRfFC3Cov.5dSkDdIG6K', '2024-08-11 17:31:55', 1, NULL, NULL),
(11, 'ismael miguel', 'botasmath@gmail.com', '$2y$10$Zt58TPOs/vI0yKVhho5FmuyqJ1bumk0..suhiB0nJuuxdtx/pUhKu', '2024-08-16 16:56:09', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conexiones`
--

CREATE TABLE `conexiones` (
  `conecId` int(11) NOT NULL,
  `alumnoId` int(11) DEFAULT NULL,
  `inicioSesion` datetime DEFAULT current_timestamp(),
  `finSesion` datetime DEFAULT NULL,
  `duracion` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conexiones`
--

INSERT INTO `conexiones` (`conecId`, `alumnoId`, `inicioSesion`, `finSesion`, `duracion`) VALUES
(1, 4, '2024-07-25 23:07:04', '2024-07-25 23:11:45', '00:04:41'),
(2, 5, '2024-07-26 18:26:17', '2024-07-26 18:26:35', '00:00:18'),
(4, 2, '2024-07-26 18:49:49', '2024-07-26 18:50:12', '00:00:23'),
(5, 5, '2024-07-26 19:29:11', '2024-07-26 19:29:16', '00:00:05'),
(6, 1, '2024-07-26 19:29:41', '2024-07-26 19:29:44', '00:00:03'),
(7, 3, '2024-07-26 19:41:21', '2024-07-26 19:43:23', '00:02:02'),
(8, 3, '2024-07-26 19:45:54', '2024-07-26 19:47:08', '00:01:14'),
(9, 1, '2024-07-26 20:07:32', '2024-07-26 20:07:35', '00:00:03'),
(10, 3, '2024-07-26 20:24:11', '2024-07-26 20:24:26', '00:00:15'),
(11, 3, '2024-07-26 20:29:35', '2024-07-26 20:29:44', '00:00:09'),
(12, 1, '2024-07-26 20:32:21', '2024-07-26 20:32:24', '00:00:03'),
(13, 1, '2024-07-26 20:44:03', '2024-07-26 20:44:06', '00:00:03'),
(14, 2, '2024-07-26 20:44:54', '2024-07-26 20:46:25', '00:01:31'),
(15, 4, '2024-07-26 20:48:47', '2024-07-26 20:51:16', '00:02:29'),
(16, 6, '2024-07-27 14:50:25', '2024-07-27 14:51:02', '00:00:37'),
(17, 7, '2024-07-27 18:11:28', '2024-07-27 18:12:23', '00:00:55'),
(18, 5, '2024-07-28 12:52:34', '2024-07-28 12:53:33', '00:00:59'),
(19, 5, '2024-07-28 16:02:16', NULL, NULL),
(20, 7, '2024-07-28 19:44:30', '2024-07-28 19:45:58', '00:01:28'),
(21, 4, '2024-07-28 19:46:28', '2024-07-28 19:46:32', '00:00:04'),
(22, 4, '2024-07-28 20:42:51', '2024-07-28 20:44:11', '00:01:20'),
(23, 4, '2024-07-28 20:46:29', '2024-07-28 20:48:05', '00:01:36'),
(24, 7, '2024-07-29 11:31:36', '2024-07-29 11:32:55', '00:01:19'),
(25, 1, '2024-07-29 12:01:37', '2024-07-29 12:02:32', '00:00:55'),
(26, 5, '2024-07-29 13:28:01', '2024-07-29 13:28:16', '00:00:15'),
(27, 8, '2024-07-29 13:30:30', '2024-07-29 13:35:03', '00:04:33'),
(28, 8, '2024-07-29 13:37:34', '2024-07-29 13:40:27', '00:02:53'),
(29, 8, '2024-07-29 18:32:57', '2024-07-29 18:33:14', '00:00:17'),
(30, 8, '2024-07-29 18:38:31', '2024-07-29 18:38:38', '00:00:07'),
(31, 8, '2024-07-29 18:39:19', '2024-07-29 18:39:25', '00:00:06'),
(32, 7, '2024-07-29 18:40:15', '2024-07-29 18:40:33', '00:00:18'),
(33, 1, '2024-07-29 20:06:51', NULL, NULL),
(34, 8, '2024-07-29 22:32:37', NULL, NULL),
(35, 7, '2024-07-29 22:41:14', NULL, NULL),
(36, 7, '2024-07-31 16:28:32', '2024-07-31 16:32:29', '00:03:57'),
(37, 3, '2024-07-31 17:02:39', '2024-07-31 17:03:20', '00:00:41'),
(38, 8, '2024-07-31 20:18:10', '2024-07-31 20:18:26', '00:00:16'),
(39, 9, '2024-08-02 18:35:19', '2024-08-02 18:35:33', '00:00:14'),
(40, 2, '2024-08-04 20:40:04', '2024-08-04 20:43:43', '00:03:39'),
(41, 2, '2024-08-04 20:44:43', '2024-08-04 20:48:06', '00:03:23'),
(42, 2, '2024-08-04 23:02:49', '2024-08-04 23:05:49', '00:03:00'),
(43, 2, '2024-08-04 23:06:04', NULL, NULL),
(44, 2, '2024-08-04 23:59:47', NULL, NULL),
(45, 2, '2024-08-05 00:00:39', '2024-08-05 00:01:08', '00:00:29'),
(46, 2, '2024-08-05 00:13:10', NULL, NULL),
(47, 4, '2024-08-07 08:25:20', '2024-08-07 09:02:00', '00:36:40'),
(48, 4, '2024-08-07 09:06:45', '2024-08-07 10:06:23', '00:59:38'),
(49, 4, '2024-08-07 10:06:33', '2024-08-07 10:30:48', '00:24:15'),
(50, 1, '2024-08-07 10:31:11', '2024-08-07 11:38:55', '01:07:44'),
(51, 9, '2024-08-07 11:42:46', '2024-08-07 12:10:55', '00:28:09'),
(52, 2, '2024-08-07 12:11:27', '2024-08-07 12:12:42', '00:01:15'),
(53, 2, '2024-08-07 12:13:04', '2024-08-07 12:18:11', '00:05:07'),
(54, 7, '2024-08-07 16:50:06', '2024-08-07 16:53:08', '00:03:02'),
(55, 5, '2024-08-08 13:01:59', '2024-08-08 13:03:08', '00:01:09'),
(56, 4, '2024-08-11 17:26:40', '2024-08-11 17:26:44', '00:00:04'),
(57, 10, '2024-08-11 17:32:29', '2024-08-11 17:32:39', '00:00:10'),
(58, 4, '2024-08-11 17:37:09', '2024-08-11 17:37:11', '00:00:02'),
(59, 2, '2024-08-11 17:37:49', '2024-08-11 17:38:56', '00:01:07'),
(60, 1, '2024-08-11 17:39:06', '2024-08-11 17:40:46', '00:01:40'),
(61, 9, '2024-08-11 17:42:18', '2024-08-11 17:42:20', '00:00:02'),
(62, 5, '2024-08-11 17:43:21', '2024-08-11 17:43:25', '00:00:04'),
(63, 6, '2024-08-11 17:44:04', '2024-08-11 17:44:06', '00:00:02'),
(64, 8, '2024-08-11 17:44:37', '2024-08-11 17:44:40', '00:00:03'),
(65, 7, '2024-08-11 17:45:59', '2024-08-11 17:46:02', '00:00:03'),
(66, 2, '2024-08-11 18:10:43', '2024-08-11 18:29:49', '00:19:06'),
(67, 11, '2024-08-16 16:56:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `ejercicioId` int(11) NOT NULL,
  `moduloId` int(11) DEFAULT NULL,
  `pregunta` varchar(255) NOT NULL,
  `opcion_a` varchar(255) NOT NULL,
  `opcion_b` varchar(255) NOT NULL,
  `opcion_c` varchar(255) NOT NULL,
  `resp_crrct` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`ejercicioId`, `moduloId`, `pregunta`, `opcion_a`, `opcion_b`, `opcion_c`, `resp_crrct`) VALUES
(1, 1, '¿Cuáles son las fases del desarrollo de un software?', 'Implementación, Desarrollo, Pruebas, Mantenimiento', 'Diseño, Implementación, Pruebas', 'Análisis, Diseño, Implementación, Pruebas, Implantación, Mantenimiento', 'C'),
(2, 1, '¿Que es un paradigma?', 'Métodos de programación que sirven para resolver un problema', 'Métodos de programación que llegan a uno o varios resultados a la vez', 'Métodos utilizados para realizar tareas o proyectos', 'A'),
(3, 1, '¿cuáles son los paradigmas?', 'Imperativo, Funcional, Reactivo, Orientada a objetos', 'Funcional, Declarativo, Orientada a clases, Imperativo', 'Imperativo, Funcional, Declarativo, Reactivo, Orientado a objetos', 'C'),
(4, 1, 'Describe el paradigma Imperativo', 'método que permite desarrollar programas a través de procedimientos', 'método que desarrolla un programa sin procedimientos', 'método que explica cómo funciona el código de manera clara', 'A'),
(5, 1, 'Describe el paradigma Orientado a objetos', 'un modelo de programación que proporciona unas guías acerca de cómo trabajar con el', 'modelo que ayuda a programar de manera por secciones', 'organiza el código en entidades llamadas objetos, que contienen datos de atributos', 'C'),
(6, 1, 'Describe el paradigma Funcional', 'las funciones estarán en primer lugar y se centrara en expresiones', 'esta basado en conceptos que vienen de las matemáticas', 'es programación declarativo basado en el uso de funciones matemáticas', 'C'),
(7, 1, 'Describe el paradigma Declarativo', 'Determina el resultado y algunos pasos que se hicieron', 'No se detallan los pasos a seguir, solo se llega al resultado', 'Desarrolla todo sin explicar nada de lo realizado', 'B'),
(8, 1, '¿Qué es polimorfismo?', 'el polimorfismo permite que una misma función o método tenga diferentes comportamientos según el objeto con el que se use', 'es la capacidad de una clase base para obtener objetos de sus clases derivadas, utilizando una referencia común', 'el polimorfismo se refiere a la habilidad de definir múltiples métodos con el mismo nombre en una clase', 'A'),
(9, 1, '¿Cuáles son las tres maneras diferentes de encontrar un elemento en una lista de datos?', 'Búsqueda logica, búsqueda secuencial y búsqueda ordenada', 'Búsqueda aleatoria, búsqueda logica y búsqueda binaria', 'Búsqueda secuencial, búsqueda binaria y búsqueda logica', 'C'),
(10, 1, '¿En que consiste la busqueda secuencial?', 'Es factible si el acceso a los datos es extremadamente lento', 'En recorrer todos los elementos en una lista y compararlos del primero al ultimo', 'Es un metodo simple que se usa para encontrar datos en una lista ordenada, se hace una busqueda con dos indices', 'B'),
(11, 1, '¿En qué consiste el método de la burbuja?', 'El método de la burbuja es una técnica de programación que busca valores duplicados en un conjunto de datos', 'El método de la burbuja es un algoritmo de clasificación que ordena una lista de elementos comparando pares adyacentes y cambiándolos si están en el orden incorrecto', 'El método de la burbuja es una estrategia de búsqueda que encuentra el valor máximo en una lista de elementos no ordenados', 'B'),
(12, 1, '¿Qué es la encapsulación en la programación orientada a objetos?', 'La capacidad de un objeto de tomar muchas formas', 'El proceso de ocultar los detalles internos de un objeto y exponer solo lo necesario', 'La capacidad de un objeto de heredar propiedades de otro objeto', 'B'),
(13, 1, '¿Qué es el polimorfismo en la programación orientada a objetos?', 'La capacidad de un objeto de ser instanciado varias veces', 'La capacidad de una clase de tener múltiples constructores', 'La capacidad de un método de tomar varias formas', 'C'),
(14, 1, '¿Qué es la herencia en la programación orientada a objetos?', 'La capacidad de un objeto de tomar muchas formas', 'La capacidad de una clase de derivar características de otra clase', 'La capacidad de un método de ser sobrescrito', 'B'),
(15, 1, '¿Cuál es la estructura básica de una clase en programación orientada a objetos?', 'Funciones y variables', 'Atributos y métodos', 'Estructuras y punteros', 'B'),
(16, 1, '¿Qué define un objeto en programación orientada a objetos?', 'Un conjunto de funciones', 'Un conjunto de atributos y métodos', 'Una serie de valores numéricos', 'B'),
(17, 1, '¿Cómo se declara un atributo dentro de una clase en un lenguaje orientado a objetos?', 'Dentro de las funciones', 'Dentro del constructor', 'Dentro de la clase, pero fuera de los métodos', 'C'),
(18, 1, '¿Qué es un método en una clase?', 'Un tipo de atributo', 'Una función definida dentro de una clase', 'Un operador de comparación', 'B'),
(19, 1, '¿Qué se entiende por clase padre y clase hija en herencia?', 'La clase padre es la que hereda de la clase hija', 'La clase hija es la que hereda de la clase padre', 'La clase padre y la clase hija son clases que no tienen relación', 'B'),
(20, 1, '¿Qué sucede si una clase hija necesita un método que ya existe en la clase padre?', 'El método de la clase hija reemplaza al de la clase padre', 'El método de la clase padre se elimina automáticamente', 'La clase hija no puede usar el método de la clase padre', 'A'),
(21, 1, '¿Qué características hereda una clase hija de su clase padre?', 'Solo los métodos', 'Solo los atributos', 'Tanto los atributos como los métodos', 'C'),
(22, 1, '¿Cuál es la principal ventaja de utilizar clases y objetos en la programación orientada a objetos?', 'Reducción del tiempo de compilación', 'Encapsulamiento y modularidad del código', 'Mayor consumo de memoria', 'B'),
(23, 2, '¿Qué es la programación orientada a objetos?', 'Un paradigma de programación basado en el uso de clases y objetos.', 'Un lenguaje de programación especifico utilizado para desarrollar aplicaciones.', 'Un conjunto de reglas para diseñar DB relacionales.', 'A'),
(24, 2, '¿Qué es encapsulación?', 'Una técnica para agrupar y organizar datos.', 'Capacidad de un objeto de tomar muchas formas.', 'Agrupación de datos y métodos que operan en la misma unidad.', 'C'),
(25, 2, '¿Qué es un método dentro de un objeto?', 'Variable que almacena datos.', 'Función definida dentro de una clase.', 'Archivo que contiene un ejecutable.', 'B'),
(26, 2, '¿Qué es un atributo?', 'Función que realiza una operación especifica.', 'Método especial que se llama al momento de crear un objeto.', 'Variable que pertenece a una clase, o bien, a un objeto.', 'C'),
(27, 2, '¿Qué es un constructor?', 'Función diseñada para destruir un objeto.', 'Bloque de código que se ejecuta después de un bucle.', 'Método que se llama al momento de crear un objeto.', 'C'),
(28, 2, '¿Qué es la identacion?', 'El uso de espacios o tabulaciones al inicio de una línea de código indicando un bloque de código dentro del programa.', 'Método que declara variable de manera global dentro del programa.', 'Una técnica de optimización.', 'A'),
(29, 2, '¿Qué es un IDE?', 'Entorno de programación.', 'SO especializado en la gestión del hw.', 'Biblioteca diseñada para la programación.', 'A'),
(30, 2, '¿A qué se le conoce como Inputs?', 'Instrucciones que el programa ejecuta de manera secuencial.', 'Datos o información que se le proporciona al programa para ser procesado.', 'Resultados generados por el programa después de procesar la info.', 'B'),
(31, 2, '¿A que se le conoce como Outputs?', 'Instrucciones que el programa ejecuta de manera secuencial.', 'Datos o información que se le proporciona al programa para ser procesado.', 'Resultados generados por el programa después de procesar la info.', 'C'),
(32, 2, '¿Qué es una variable?', 'Conjunto de ordenes o instrucciones dadas de manera secuencial.', 'Archivo de texto.', 'Espacio reservado en la memoria para almacenar un valor.', 'C'),
(33, 2, '¿Qué tipos de datos son aceptados en variables?', 'Publicas, Privadas, protegidos y estáticos.', 'Entero, flotante, booleana y de caracteres.', 'Locales, globales y estáticas.', 'B'),
(34, 2, '¿Cuáles son los tipos de variables?', 'Publicas, Privadas, protegidos y estáticos.', 'Entero, flotante, booleana y de caracteres.', 'Locales, globales y estáticas.', 'C'),
(35, 2, '¿Qué es un comentario de código?', 'Elemento que permite insertar notas sin afectar el rendimiento de la página web.', 'Fragmentos de texto dentro del código que no afectan la ejecución del programa.', 'Realización de sugerencias o correcciones sin cambiar el texto.', 'B'),
(36, 2, '¿Qué es la programación Orientada a objetos?', 'Hace mejoras de amplio alcance en la forma de diseño, desarrollo y mantenimiento', 'Programación donde solo se programan objetos', 'Mundo lleno de objetos y que tienen resolución del problema se realiza en términos objetivos', 'C'),
(37, 2, '3 características básicas de la Programación Orientada a Objetos', 'basado en métodos, basado en subclases, basado en clases', 'basado en gráficas, basado en métodos, no tener herencia entre clases', 'basado en objetos, basado en clases, capaz de tener herencia de clases', 'C'),
(38, 2, '¿Qué palabra reservada se utiliza en Java para definir una subclase', 'implements', 'inherits', 'extends', 'C'),
(39, 2, '¿cuál es el beneficio principal de la herencia en programación orientada a objetos?', 'Aumentar el tiempo de ejecución', 'Reducir la cantidad de código a desarrollar', 'Facilitar el mantenimiento y su reusabilidad del código', 'C'),
(40, 2, '¿Qué es una clase base en el contexto de la herencia?', 'Una clase de la que derivan otras clases', 'Una clase que no tiene métodos', 'Una clase que hereda de otra clase', 'A'),
(41, 2, '¿Qué método sobrescribe en la clase Rectángulo para calcular el area?', 'getArea ()', 'superficie ()', 'area ()', 'A'),
(42, 2, '¿Qué tipo de enlace permite el polimorfismo en tiempo de ejecución?', 'Enlace estático', 'Enlace dinámico', 'Enlace directo', 'B'),
(43, 2, '¿Cuál de la siguientes es una ventaja de la herencia en Java?', 'Incrementa el tiempo de ejecución del programa', 'Reduce la legibilidad del código', 'Reutilización de código ', 'C'),
(44, 2, '¿Qué operador se utiliza para verificar si un objeto es una instancia de una clase especifica?', 'instanceof', 'instance', 'typeof', 'A'),
(45, 2, '¿Qué se entiende por conversión hacia arriba en el contexto de la herencia?', 'Convertir un tipo primitivo a un objeto', 'Asignar una referencia de una subclase a una variable de una superclase', 'Convertir un objeto a un tipo primitivo', 'B'),
(46, 2, '¿Cómo se accede a un atributo de un objeto en programación orientada a objetos?', 'Directamente a través del nombre del atributo', 'A través de una función global', 'Usando el nombre del objeto seguido de un punto y el nombre del atributo', 'C'),
(47, 2, '¿Qué es un constructor en una clase?', 'Un método para destruir objetos', 'Un método para modificar atributos', 'Un método especial para inicializar objetos', 'C'),
(48, 2, '¿Cuál es la diferencia principal entre una clase y un objeto?', 'Una clase es una instancia de un objeto; un objeto es una plantilla para crear clases', 'Una clase define el formato y comportamiento; un objeto es una instancia concreta de la clase', 'Una clase es una variable; un objeto es una función', 'B'),
(49, 2, '¿Qué significa el término \"sobreescritura\" en el contexto de la herencia?', 'Crear un nuevo atributo en la clase hija', 'Modificar el valor de un atributo en la clase padre', 'Reemplazar un método de la clase padre con uno nuevo en la clase hija', 'C'),
(50, 2, '¿Qué es una clase abstracta en el contexto de la herencia?', 'Una clase que no puede ser instanciada directamente', 'Una clase que solo puede ser instanciada por otras clases abstractas', 'Una clase que debe tener al menos un método', 'A'),
(51, 2, '¿Qué es una interfaz en programación orientada a objetos?', 'Una clase que puede contener métodos implementados', 'Un conjunto de métodos que una clase debe implementar sin proporcionar una implementación', 'Un tipo de atributo en una clase', 'B'),
(52, 2, '¿Qué se hereda cuando una clase hija extiende una clase padre?', 'Solo los métodos públicos y protegidos de la clase padre', 'Solo los atributos privados de la clase padre', 'Todos los atributos y métodos, incluyendo los privados', 'A'),
(53, 2, 'Las clases en programación orientada a objetos pueden contener tanto atributos como métodos.', 'Verdadero', 'Falso', 'Depende del lenguaje de programación', 'A'),
(54, 2, 'Un objeto puede ser creado sin que se defina una clase en la que basarse.', 'Verdadero', 'Falso', 'Depende del lenguaje de programación', 'B'),
(55, 2, 'En programación orientada a objetos, un método de una clase puede acceder a los atributos de esa misma clase.', 'Verdadero', 'Falso', 'Solo si los atributos son públicos', 'A'),
(56, 2, 'El constructor de una clase se utiliza para definir métodos adicionales en la clase.', 'Verdadero', 'Falso', 'Depende del lenguaje de programación', 'B'),
(57, 2, 'Una clase hija puede acceder a los métodos protegidos de su clase padre.', 'Verdadero', 'Falso', 'Solo si la clase hija está en el mismo paquete', 'A'),
(58, 2, 'Las clases hijas pueden heredar atributos privados de la clase padre.', 'Verdadero', 'Falso', 'Solo si se utiliza la palabra clave \"protected\"', 'B'),
(59, 2, '¿Cuál es la principal diferencia entre un método estático y un método no estático?', 'Los métodos estáticos pueden acceder a los atributos de instancia, mientras que los métodos no estáticos no pueden', 'Los métodos no estáticos pueden ser llamados sin una instancia de la clase, mientras que los métodos estáticos no pueden', 'Los métodos estáticos no pueden acceder a los atributos de instancia, mientras que los métodos no estáticos pueden', 'C'),
(60, 2, '¿Qué método en una clase es utilizado para proporcionar una representación en cadena de un objeto?', 'toString()', 'getString()', 'print()', 'A'),
(61, 2, '¿Cuál es la sintaxis correcta para definir una clase en la mayoría de los lenguajes orientados a objetos?', 'class NombreClase { }', 'create class NombreClase { }', 'define class NombreClase { }', 'A'),
(62, 2, '¿Cómo se declara un constructor no estático en una clase?', 'static NombreClase() { }', 'NombreClase() { }', 'void NombreClase() { }', 'B'),
(63, 3, '¿Cuáles son los tipos de comentarios que trabaja Java?', 'De una línea, de varias líneas y de documentación.', 'De una sola línea.', 'De varias líneas.', 'A'),
(64, 3, 'Selecciona cuales no son palabras reservadas', 'Extends, package, throw, static.', 'Byvalue, casst, transient, native.', 'Chars, casst, extends, outers.', 'C'),
(65, 3, '¿Cuántos tipos de operadores hay en Java?', 'Reducido, normal, logico y módulo.', 'Igualdad, negado, de bit y normal.', 'Aritméticos, de bit, relacionales y lógico.', 'C'),
(66, 3, '¿Que es herencia?', 'Descripción de una familia de objetos que tienen la misma estructura y el mismo comportamiento.', 'Conjunto de datos y funciones que permiten crear múltiples instancias.', 'Colección de métodos para operaciones en diferentes elementos.', 'A'),
(67, 3, '¿Son estructuras de datos que almacenan un número fijo de elementos de información?', 'Vectores o arrays', 'Ternario', 'De control', 'A'),
(68, 3, 'Para tener un vector multidimensional, ¿Qué se debe de hacer?', 'Añadir comillas (\" \" )', 'Añadir igual (= )', 'Añadir corchetes ([ ] )', 'C'),
(69, 3, '¿Cuáles son los tipos de estructuras de control que hay en Java?', 'Públicas, privadas y protegidas.', 'Condicionales, repetición o iteración y sentencias de salto (break, continue y return).', 'Locales, globales y privadas.', 'B'),
(70, 3, '¿Qué operador se utiliza para concatenar cadenas en Java?', '&', '+', '.', 'B'),
(71, 3, '¿Qué método de la clase String se usa para comparar dos cadenas en Java?', 'compareTo()', 'equals()', '==', 'B'),
(72, 3, '¿Cuál es el valor predeterminado de un atributo booleano en Java?', 'true', 'false', 'null', 'B'),
(73, 3, '¿Qué clase se utiliza para leer datos desde la consola en Java?', 'Input Reader', 'Scanner', 'Buffered Reader', 'B'),
(74, 3, '¿Qué palabra clave se utiliza para definir una interfaz en Java?', 'class', 'interface', 'implements', 'B'),
(75, 3, '¿Cuál es el modificador de acceso permisivo en Java?', 'public', 'private', 'package-private', 'A'),
(76, 3, '¿Qué palabra clave se usa para crear una subclase en Java?', 'extend', 'inherit', 'extends', 'C'),
(77, 3, '¿Qué significa el operador == en Java?', 'Asignación', 'Comparación de valores', 'Igualdad referencial', 'C'),
(78, 3, '¿Cuál es el tipo de retorno de un método que no devuelve nada en Java?', 'void', 'null', 'empty', 'A'),
(79, 3, '¿Cuál es el modificador que evita que una clase sea heredada?', 'static', 'final', 'private', 'B'),
(80, 3, '¿Qué operador lógico se usa para representar \"O\" en Java?', '&&', '||', 'or', 'B'),
(81, 3, '¿Qué método se usa para iniciar un hilo en Java?', 'run()', 'start()', 'begin()', 'B'),
(82, 3, '¿Qué palabra clave se utiliza para definir una clase que no puede ser instanciada?', 'abstract', 'final', 'static', 'A'),
(83, 3, '¿Qué operador se usa para negar una condición lógica en Java?', '&&', '||', '!', 'C'),
(84, 3, '¿Cuál es el modificador de acceso más restrictivo en Java?', 'public', 'private', 'protected', 'B'),
(85, 3, '¿Qué clase se utiliza para lanzar una excepción en Java?', 'Exception', 'Error', 'Throwable', 'A'),
(86, 3, '¿Qué palabra clave se utiliza para definir una variable que no puede cambiar su valor?', 'final', 'static', 'const', 'A'),
(87, 3, '¿Qué operador se usa para verificar si dos referencias de objeto apuntan al mismo objeto en Java?', '==', 'equals()', 'is()', 'A'),
(88, 3, '¿Qué palabra clave se utiliza para detener temporalmente la ejecución de un hilo en Java?', 'stop', 'wait', 'sleep', 'C'),
(89, 3, '¿Qué clase se utiliza para manejar eventos de tiempo en Java?', 'Event', 'Timer', 'Schedule', 'B'),
(90, 3, '¿Qué clase se utiliza para escribir datos en un archivo en Java?', 'FileInputStream', 'FileOutputStream', 'FileReader', 'B'),
(91, 3, '¿Qué palabra clave se utiliza para declarar una excepción personalizada en Java?', 'new Exception', 'create Exception', 'extends Exception', 'C'),
(92, 3, '¿Qué método se utiliza para eliminar todos los elementos de una lista en Java?', 'clear()', 'deleteAll()', 'removeAll()', 'A'),
(93, 3, '¿Cuál es la estructura correcta de un bucle while en Java?', 'while { condition; }', 'while (condition) { }', 'while [ condition ] { }', 'B'),
(94, 3, '¿Cuál es el método que se utiliza para obtener el hash code de un objeto en Java?', 'getHashCode()', 'hashCode()', 'getHash()', 'B'),
(95, 3, '¿Qué palabra clave se utiliza para declarar una clase anidada en Java?', 'static', 'nested', 'inner', 'A'),
(96, 3, '¿Cuál es el método que se llama automáticamente cuando se crea un objeto en Java?', 'constructor', 'build()', 'init()', 'A'),
(97, 3, '¿Qué clase se utiliza para representar una lista en Java?', 'ArrayList', 'LinkedList', 'List', 'A'),
(98, 3, '¿Cuál es el método que se utiliza para agregar un elemento al final de una lista en Java?', 'append()', 'add()', 'insert()', 'B'),
(99, 3, '¿Qué se debe hacer si el determinante de una ecuación cuadrática es menor que 0?', 'Mostrar las soluciones imaginarias', 'Mostrar \"No existen soluciones reales\"', 'Calcular el valor absoluto del determinante', 'B'),
(100, 3, '¿Cuál es la fórmula para calcular el área de un círculo?', 'A = 2 * PI * r', 'A = PI * r^2', 'A = r^2 / PI', 'B'),
(101, 3, '¿Qué método de la clase Entrada se usa para leer un número entero?', 'Entrada.cadena()', 'Entrada.caracter()', 'Entrada.entero()', 'C'),
(102, 3, '¿Qué se debe hacer si el usuario introduce dos números iguales?', 'Mostrar \"Son iguales\"', 'Sumar ambos números', 'Restar ambos números', 'A'),
(103, 3, '¿Cómo se determina si un número es positivo o negativo en Java?', 'if (num != 0)', 'if (num > 0)', 'if (num < 0)', 'B'),
(104, 3, '¿Qué se imprime si a > b en el ejercicio de mayor número?', '\"Son iguales\"', 'a + \" es mayor que \" + b', 'b + \" es mayor que \" + a', 'B'),
(105, 3, '¿Qué método de la clase Scanner se usa para leer una cadena de caracteres?', 'Scanner.nextInt()', 'Scanner.caracter()', 'Scanner.nextLine()', 'C'),
(106, 3, '¿Cómo se determina si un número es múltiplo de otro en Java?', 'if (n1 + n2 == 0)', 'if (n1 * n2 == 0)', 'if (n1 % n2 == 0)', 'C'),
(107, 3, '¿Qué es una clase en Java?', 'Una plantilla o modelo para crear objetos', 'Un tipo de variable que almacena valores numéricos', 'Un operador utilizado para realizar comparaciones', 'A'),
(108, 3, '¿Cuál es la sintaxis correcta para definir una clase?', 'class nombreClase() {}', 'class nombreClase {}', 'public class nombreClase {}', 'C'),
(109, 3, '¿Qué método se utiliza para iniciar la ejecución de un programa?', 'main()', 'init()', 'start()', 'A'),
(110, 3, '¿Cómo se declara un entero?', 'int numero;', 'integer numero;', 'number numero;', 'A'),
(111, 3, '¿Qué palabra clave se utiliza para crear un objeto?', 'new', 'create', 'object', 'A'),
(112, 3, '¿Cuál es el modificador de acceso que hace que un miembro de la clase sea accesible desde cualquier otra clase?', 'public', 'private', 'protected', 'A'),
(113, 3, '¿Qué significa \"overloading\"?', 'Definir múltiples métodos con el mismo nombre pero con diferentes parámetros', 'Sobreescribir un método en una clase hija', 'Usar múltiples constructores en una clase', 'A'),
(114, 3, '¿Qué significa \"overriding\"?', 'Redefinir un método en una clase hija con la misma firma que en la clase padre', 'Sobrecargar un método en una clase padre', 'Usar múltiples interfaces en una clase', 'A'),
(115, 3, '¿Qué es una interfaz?', 'Un contrato que una clase puede implementar', 'Una clase que no puede ser instanciada', 'Una clase que contiene solo métodos estáticos', 'A'),
(116, 3, '¿Qué operador se utiliza para comparar dos valores?', '==', '=', '!=', 'A'),
(117, 3, '¿Cuál es la forma adecuada de implementar un bloque para manejar excepciones?', 'try-catch-finally', 'try-catch', 'catch-try', 'A'),
(118, 3, '¿Qué palabra clave se utiliza para heredar una clase?', 'extends', 'implements', 'inherits', 'A'),
(119, 3, '¿Cuál es la estructura correcta para definir un método en una clase en Java?', 'tipoDeRetorno nombreMetodo(parametros) { cuerpoDelMetodo }', 'method tipoDeRetorno nombreMetodo(parametros) { cuerpoDelMetodo }', 'tipoDeRetorno nombreMetodo { parametros } { cuerpoDelMetodo }', 'A'),
(120, 3, '¿Cuál es el valor por defecto de una variable booleana?', 'false', 'true', '0', 'A'),
(121, 3, '¿Cómo se llama el proceso de convertir un tipo de dato en otro?', 'Casting', 'Wrapping', 'Overloading', 'A'),
(122, 3, '¿Qué método se utiliza para obtener la longitud de una cadena?', 'length()', 'size()', 'count()', 'A'),
(123, 3, '¿Cuál es el propósito del operador \"instanceof\"?', 'Verificar si un objeto es una instancia de una clase específica', 'Crear una instancia de una clase', 'Asignar un valor a una variable', 'A'),
(124, 3, '¿Qué palabra clave se utiliza para definir una constante?', 'final', 'const', 'static', 'A'),
(125, 3, '¿Qué es una \"Exception\" en Java?', 'Un error en tiempo de ejecución', 'Un error en tiempo de compilación', 'Una clase que no puede ser instanciada', 'A'),
(126, 3, '¿Cómo se crea una lista de tamaño variable?', 'Usando la clase `Vector`', 'Usando la clase `LinkedList`', 'Usando la clase `ArrayList`', 'C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `moduloId` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tutorId` int(11) DEFAULT NULL,
  `registroId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`moduloId`, `titulo`, `descripcion`, `tutorId`, `registroId`) VALUES
(1, 'Paradigma Orientado a Objetos', 'Introducción al Paradigma Orientado a Objetos (POO). Definir los principios del Paradigma Orientado a Objetos: abstracción, encapsulamiento, herencia y polimorfismo.', 3, 1),
(2, 'Fundamentos de programación Orientada a Objetos', 'Abstracción: clases y objetos. Identificar la estructura básica de clase, atributo, método y objeto.', 2, 2),
(3, 'Programación Orientada a Objetos', 'Herencia. Identificar las características y comportamiento de una clase padre a sus clases hijas.', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso`
--

CREATE TABLE `progreso` (
  `progresoId` int(11) NOT NULL,
  `alumnoId` int(11) DEFAULT NULL,
  `moduloId` int(11) DEFAULT NULL,
  `ejercicioId` int(11) DEFAULT NULL,
  `completado` tinyint(1) DEFAULT 0,
  `fechaCompletado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `progreso`
--

INSERT INTO `progreso` (`progresoId`, `alumnoId`, `moduloId`, `ejercicioId`, `completado`, `fechaCompletado`) VALUES
(1, 11, 1, NULL, 75, NULL),
(2, 11, 2, NULL, 75, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `registroId` int(11) NOT NULL,
  `alumnoId` int(11) DEFAULT NULL,
  `tutorId` int(11) DEFAULT NULL,
  `fechaIntegracion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`registroId`, `alumnoId`, `tutorId`, `fechaIntegracion`) VALUES
(1, 1, 1, '2024-07-04 12:22:19'),
(2, 2, 2, '2024-07-04 12:22:19'),
(3, 3, 3, '2024-07-04 12:22:19'),
(4, 4, 1, '2024-07-04 12:22:19'),
(5, 5, 2, '2024-07-04 12:22:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutores`
--

CREATE TABLE `tutores` (
  `tutorId` int(11) NOT NULL,
  `nombreTutor` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fechaCreacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutores`
--

INSERT INTO `tutores` (`tutorId`, `nombreTutor`, `email`, `password`, `fechaCreacion`) VALUES
(1, 'Pedro Martínez', 'pedro.martinez@gmail.com', '$2y$10$2zPhxW6zdRJ9k5aZ/0M2eehR3rzU0HkMxjoZVAbTtmvZZ0PrAD2Jq', '2024-07-04 12:11:25'),
(2, 'Lucía Fernández', 'lucia.fernandez@gmail.com', '$2y$10$upnqevwy6ocHgCLcFM8RCunDWYLTh1a3VGXRY3iz8Q5Oo0tGxuHFe', '2024-07-04 12:11:25'),
(3, 'Miguel Torres', 'miguel.torres@gmail.com', '$2y$10$9gKQUdd9NAfOXYvIZXo0eOGVak4pgi2/gm8Wrg0LC49dE5xDMNVhe', '2024-07-04 12:11:25'),
(4, 'Sofía Ramírez', 'sofia.ramirez@gmail.com', '$2y$10$gFe1jpsVsnwB6H7PM6GB7.3OcI.g2e6vAqD9s4wAcyDetWN536hbu', '2024-07-04 12:11:25'),
(5, 'David Hernández', 'david.hernandez@gmail.com', '$2y$10$yy3E9HTgVDuQRsVwgq3JPOwRkAO2alBG8l.qW4.WKgM6piL7kx6WK', '2024-07-04 12:11:25'),
(6, 'Edgardo Vargas', 'edgarvfaus@gmail.com', '$2y$10$GH1aAKDdDoFlkNo1YY8yBupMd2NrEhmEHB428JDvquASh.Xb6zRnq', '2024-07-24 14:35:30'),
(7, 'Luis Gallegos', 'gallegos@gmail.com', '$2y$10$Ga5sMk27YsKaawp2qTYTv..2cfPinaL8fFTIxvNaCrbZ.ReMRV17S', '2024-07-27 16:26:14'),
(8, 'Miguel Garibaldo', 'garigari@gmail.com', '$2y$10$kG6C2aqxPJ4Rem7bBWk2Fuf0CVSuN.GOdtuRFzJdIjtAfaiiQpsa.', '2024-07-27 16:44:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`adminId`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`alumnoId`);

--
-- Indices de la tabla `conexiones`
--
ALTER TABLE `conexiones`
  ADD PRIMARY KEY (`conecId`),
  ADD KEY `alumnoId` (`alumnoId`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`ejercicioId`),
  ADD KEY `moduloId` (`moduloId`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`moduloId`),
  ADD KEY `tutorId` (`tutorId`),
  ADD KEY `registroId` (`registroId`);

--
-- Indices de la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD PRIMARY KEY (`progresoId`),
  ADD KEY `alumnoId` (`alumnoId`),
  ADD KEY `moduloId` (`moduloId`),
  ADD KEY `ejercicioId` (`ejercicioId`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`registroId`),
  ADD KEY `alumnoId` (`alumnoId`),
  ADD KEY `tutorId` (`tutorId`);

--
-- Indices de la tabla `tutores`
--
ALTER TABLE `tutores`
  ADD PRIMARY KEY (`tutorId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `alumnoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `conexiones`
--
ALTER TABLE `conexiones`
  MODIFY `conecId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `ejercicioId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `moduloId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `progreso`
--
ALTER TABLE `progreso`
  MODIFY `progresoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `registroId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tutores`
--
ALTER TABLE `tutores`
  MODIFY `tutorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conexiones`
--
ALTER TABLE `conexiones`
  ADD CONSTRAINT `conexiones_ibfk_1` FOREIGN KEY (`alumnoId`) REFERENCES `alumnos` (`alumnoId`);

--
-- Filtros para la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD CONSTRAINT `ejercicios_ibfk_1` FOREIGN KEY (`moduloId`) REFERENCES `modulos` (`moduloId`);

--
-- Filtros para la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`tutorId`) REFERENCES `tutores` (`tutorId`),
  ADD CONSTRAINT `modulos_ibfk_2` FOREIGN KEY (`registroId`) REFERENCES `registro` (`registroId`);

--
-- Filtros para la tabla `progreso`
--
ALTER TABLE `progreso`
  ADD CONSTRAINT `progreso_ibfk_1` FOREIGN KEY (`alumnoId`) REFERENCES `alumnos` (`alumnoId`),
  ADD CONSTRAINT `progreso_ibfk_2` FOREIGN KEY (`moduloId`) REFERENCES `modulos` (`moduloId`),
  ADD CONSTRAINT `progreso_ibfk_3` FOREIGN KEY (`ejercicioId`) REFERENCES `ejercicios` (`ejercicioId`);

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`alumnoId`) REFERENCES `alumnos` (`alumnoId`),
  ADD CONSTRAINT `registro_ibfk_2` FOREIGN KEY (`tutorId`) REFERENCES `tutores` (`tutorId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
