<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/module_service.php';
require_once __DIR__ . '/../../services/questions_service.php';

// Verificar si el usuario está autenticado y es del tipo correcto
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header('Location: ../auth/login.html');
    exit;
}

$userId = $_SESSION['user_id'];
$moduleId = 1; // Cambiar al módulo actual

// Crear instancias de los servicios
$moduleService = new ModuleService($pdo); // Asegúrate de que $pdo esté definido y sea una instancia de PDO
$questionsService = new QuestionsService($pdo);

$modules = $moduleService->getModules();
$progress = $moduleService->getProgress($userId);

// Obtener módulos únicos con su progreso
$modulesUnique = $moduleService->getUniqueModulesWithProgress($modules, $progress);

// Obtener el módulo actual
$module = array_filter($modulesUnique, function($mod) use ($moduleId) {
    return $mod['moduloId'] == $moduleId;
});
$module = $module ? array_shift($module) : null;

if (!$module) {
    die('Módulo no encontrado.');
}

// Obtener el progreso actual del módulo
$progreso = array_filter($progress, function($p) use ($moduleId) {
    return $p['moduloId'] == $moduleId;
});
$progreso = $progreso ? array_shift($progreso) : ['completado' => 0];

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['examCompleted']) && $_POST['examCompleted'] == '1' && isset($_POST['correctAnswers']) && $_POST['correctAnswers'] == '20') {
        // El usuario completó el examen y obtuvo todas las respuestas correctas, establecer progreso al 100%
        $nuevoProgreso = 100;
    } else {
        // Establecer el progreso a 75% al presionar "Continuar" o "Volver"
        $nuevoProgreso = 75;
    }

    $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);
    
    if ($nuevoProgreso == 100) {
        // Si el progreso es 100%, redirigir al dashboard o módulo siguiente
        header('Location: ../dashboard'); // O redirigir a otro módulo si es necesario
        exit;
    } elseif (isset($_POST['volver'])) {
        // Redirigir al dashboard
        header('Location: ../dashboard'); // Redirigir al dashboard
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo 1 - Introducción a OOP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard-style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/module-style.css">
    <link rel="stylesheet" href="../../public/css/repaso-style.css">	
    <style>
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #f0f0f0;
            z-index: 1000;
        }
        .progress-bar {
            height: 100%;
            background-color: #007bff;
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <a class="navbar-brand" href="#">Java Academy</a>
        <div class="navbar-nav ml-auto">
            <span class="nav-item nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a class="nav-item nav-link" href="logout.php">Cerrar sesión</a>
        </div>
    </nav>

    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <div class="container mt-4">
        <h1 class="text-center"><?php echo htmlspecialchars($module['titulo']); ?></h1>
        <div class="card mt-4">
            <div class="card-body">
                <!-- Contenido del módulo -->
                <h3>Unidad 1: Introducción al Paradigma Orientado a Objetos</h3>
                <h4 class="reveal">Introducción al Paradigma Orientado a Objetos (POO)</h4>
                <!-- Contenido del módulo aquí... -->

                <p class="reveal">El Paradigma Orientado a Objetos (POO) es un enfoque de programación que organiza los datos y el comportamiento en unidades llamadas objetos. Estos objetos son instancias de clases, que son plantillas que definen los atributos y métodos que los objetos tendrán. POO facilita la creación de software modular y reutilizable.</p>

                <h4 class="reveal">¿Qué es un objeto?</h4>
                <p class="reveal">En la programación orientada a objetos (POO), un objeto es una instancia de una clase. Un objeto puede ser visto como una entidad que tiene un estado (definido por los atributos de la clase) y un comportamiento (definido por los métodos de la clase). Por ejemplo, si tienes una clase llamada <code>Perro</code>, un objeto de esa clase podría ser un <code>Golden Retriever</code> específico con un nombre y una edad particular.</p>

                <h4 class="reveal">¿Qué es una clase?</h4>
                <p class="reveal">Una clase es una plantilla o molde a partir del cual se crean objetos. Define un tipo de objeto especificando sus atributos y métodos. En términos sencillos, una clase es como un plano que describe cómo debería ser un objeto y cómo debería comportarse.</p>

                <h4 class="reveal">¿Qué son los atributos?</h4>
                <p class="reveal">Los atributos son las propiedades o características de una clase. Son variables que se definen dentro de la clase y que determinan el estado del objeto. Por ejemplo, en la clase <code>Perro</code>, los atributos podrían ser <code>nombre</code>, <code>edad</code>, y <code>raza</code>.</p>

                <h4 class="reveal">¿Qué son los métodos?</h4>
                <p class="reveal">Los métodos son funciones definidas dentro de una clase que describen el comportamiento de los objetos de esa clase. Los métodos permiten que un objeto realice ciertas acciones o responda a ciertas solicitudes. Por ejemplo, la clase <code>Perro</code> podría tener métodos como <code>ladrar()</code> y <code>comer()</code>.</p>

                <h4 class="reveal">¿Qué es una entidad?</h4>
                <p class="reveal">En el contexto de programación orientada a objetos, una entidad puede referirse a un objeto específico creado a partir de una clase. Por ejemplo, un <code>Golden Retriever</code> sería una entidad creada a partir de la clase <code>Perro</code>.</p>

                <h4 class="reveal">Principios del Paradigma Orientado a Objetos</h4>

                <h5 class="reveal">1. Abstracción</h5>
                <p class="reveal">La abstracción en POO se refiere a ocultar los detalles complejos y mostrar solo la información necesaria. Permite al programador centrarse en el comportamiento general de los objetos sin preocuparse por los detalles internos. Por ejemplo, al conducir un coche, no necesitas saber cómo funciona el motor, solo cómo usar el volante y los pedales.</p>
                <p class="reveal"><strong>Ejemplo:</strong> Imagina una clase <code>Vehículo</code> que tiene un método <code>conducir()</code>. La implementación detallada del método se oculta, y solo se expone la acción de conducir al usuario.</p>

                <h5 class="reveal">2. Encapsulamiento</h5>
                <p class="reveal">El encapsulamiento es la práctica de agrupar datos (atributos) y métodos que operan sobre esos datos en una sola unidad o clase, y restringir el acceso a algunos de los datos. Esto protege el estado interno del objeto y solo permite modificarlo a través de métodos específicos.</p>
                <p class="reveal"><strong>Ejemplo:</strong> Una clase <code>CuentaBancaria</code> puede tener un atributo privado <code>saldo</code> y métodos públicos <code>depositar()</code> y <code>retirar()</code> para modificar el saldo. El saldo no se puede modificar directamente desde fuera de la clase.</p>

                    <div class="centered-container">
                    <h4>Repaso Encapsulamiento</h4>
                    <iframe width="853" height="480" src="https://www.youtube.com/embed/T_g6fCGxEeo" title="Encapsulamiento   Modificadores de Acceso - Java" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>

                <h5 class="reveal">3. Herencia</h5>
                <p class="reveal">La herencia permite que una clase (subclase) herede atributos y métodos de otra clase (superclase). Esto promueve la reutilización de código y la creación de una jerarquía de clases. La subclase puede extender o modificar el comportamiento heredado de la superclase.</p>
                <p class="reveal"><strong>Ejemplo:</strong> Si tienes una clase <code>Animal</code> con métodos <code>comer()</code> y <code>dormir()</code>, puedes crear una subclase <code>Perro</code> que herede estos métodos y además añada su propio método <code>ladrar()</code>.</p>

                <h5 class="reveal">4. Polimorfismo</h5>
                <p class="reveal">El polimorfismo permite que objetos de diferentes clases sean tratados como objetos de una clase común. El polimorfismo se logra mediante métodos sobrescritos o sobrecargados que permiten diferentes implementaciones del mismo método en diferentes clases.</p>
                <p class="reveal"><strong>Ejemplo:</strong> Si tienes una clase <code>Forma</code> con un método <code>dibujar()</code>, puedes tener subclases como <code>Círculo</code> y <code>Cuadrado</code>, cada una con su propia implementación del método <code>dibujar()</code>.</p>

                <h4 class="reveal">Diagrama de Clases</h4>
                <pre class="reveal">
Clase: Perro
---------------
+ nombre: string
+ edad: int
+ raza: string
---------------
+ ladrar(): void
+ comer(): void

Clase: Animal
---------------
+ nombre: string
+ edad: int
---------------
+ comer(): void
+ dormir(): void

Clase: Perro extiende Animal
---------------
+ ladrar(): void
                </pre>

                <form method="post">
                    <input type="hidden" name="progreso" value="75"> <!-- Establece el progreso a 75% -->
                    <input type="hidden" name="examCompleted" value="0"> <!-- Valor por defecto -->
                    <input type="hidden" name="correctAnswers" value="0"> <!-- Valor por defecto -->
                    <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
                    <button type="button" id="examButton" class="btn btn-warning">Examen</button>
                    <button type="submit" name="continuar" id="continueButton" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="examModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examModalLabel">Confirmación de Examen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de comenzar con el examen del módulo? Debes responder correctamente todas las preguntas para obtener el 100% de progreso.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="confirmCheck">
                        <label class="form-check-label" for="confirmCheck">
                            Sí, estoy seguro.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a href="exam.php" id="startExamButton" class="btn btn-primary" disabled>Comenzar Examen</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#examButton').on('click', function() {
                $('#examModal').modal('show');
            });

            $('#confirmCheck').on('change', function() {
                $('#startExamButton').prop('disabled', !this.checked);
            });

            // Función para actualizar la barra de progreso
            function updateProgressBar() {
                var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var scrolled = (winScroll / height) * 100;
                var progress = Math.min(scrolled, <?php echo $progreso['completado']; ?>); // Limitar el progreso al progreso actual
                document.getElementById("progressBar").style.width = progress + "%";
            }

            // Evento de scroll
            window.onscroll = function() {
                updateProgressBar();
            };

            // Verificar el progreso antes de permitir continuar
            $('#continueButton').on('click', function(e) {
                var progress = <?php echo $progreso['completado']; ?>;
                if (progress < 100) {
                    e.preventDefault();
                    alert('Debes de completar el 100% para pasar al siguiente módulo');
                }
            });
        });
    </script>
</body>
</html>
