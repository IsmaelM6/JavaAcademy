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
$moduleId = 3; // Módulo actual

// Crear una instancia del servicio de módulos y preguntas
$moduleService = new ModuleService($pdo);
$questionsService = new QuestionsService($pdo);

// Obtener todos los módulos y el progreso del usuario
$modules = $moduleService->getModules();
$progress = $moduleService->getProgress($userId);

// Obtener módulos únicos con su progreso
$modulesWithProgress = $moduleService->getUniqueModulesWithProgress($modules, $progress);

// Buscar el módulo actual en los módulos con progreso
$module = array_filter($modulesWithProgress, function($m) use ($moduleId) {
    return $m['moduloId'] == $moduleId;
});
$module = !empty($module) ? reset($module) : null;

if (!$module) {
    die('Módulo no encontrado.');
}

// Verificar el progreso del módulo actual
$progreso = $module['progreso'];
$progreso = $progreso >= 75 ? $progreso : 0; // Manejar caso donde el progreso es menor a 75%

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['terminar'])) {
        // Redirigir al usuario al dashboard si el progreso es 100%
        if ($progreso == 100) {
            header('Location: ../dashboard');
            exit;
        } else {
            echo "<script>
                    alert('Para terminar el módulo debes de tener el 100%');
                    window.location.href = 'modulo3.php';
                  </script>";
        }
    } elseif (isset($_POST['volver'])) {
        // Establecer el progreso a 75% al presionar "Volver"
        $nuevoProgreso = 75;
        $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);
        header('Location: ../dashboard');
        exit;
    } elseif (isset($_POST['comenzarExamen']) && isset($_POST['confirmar'])) {
        // Obtener preguntas aleatorias para el examen del módulo 3
        $questions = $questionsService->getRandomQuestionsByModule($moduleId);

        // Evaluar las respuestas del examen
        $correctAnswers = 0;
        $userAnswers = [];
        foreach ($questions as $question) {
            $questionId = $question['ejercicioId'];
            $userAnswers[$questionId] = isset($_POST["question_$questionId"]) ? $_POST["question_$questionId"] : [];
            $correctAnswersArr = explode(',', $question['resp_crrct']);
            sort($userAnswers[$questionId]);
            sort($correctAnswersArr);
            if ($userAnswers[$questionId] == $correctAnswersArr) {
                $correctAnswers++;
            }
        }

        $totalQuestions = count($questions);
        $todasCorrectas = $correctAnswers === $totalQuestions;

        if ($todasCorrectas) {
            $nuevoProgreso = 100;
            $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);

            echo "<script>
                    alert('¡Felicitaciones! Has completado el módulo con un 100%.');
                    window.location.href = 'modulo3.php';
                  </script>";
        } else {
            $nuevoProgreso = 75;
            $moduleService->updateProgress($userId, $moduleId, $nuevoProgreso);

            echo "<script>
                    alert('No has aprobado el examen, vuélvelo a intentar.');
                    window.location.href = 'modulo3.php';
                  </script>";
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo 3 - Avanzando en OOP</title>
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
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s, transform 0.5s;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
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
            <h1>Unidad 3: Programación Orientada a Objetos Avanzada</h1>
    <p>La Unidad 3 se adentra en conceptos avanzados de la Programación Orientada a Objetos, permitiéndote construir aplicaciones más robustas y mantenibles.</p>
    
    <p>La herencia, un pilar de la POO, permite la reutilización de código y la extensión de funcionalidades mediante el uso de clases abstractas e interfaces. Además, estudiaremos el polimorfismo, que habilita a los objetos para tomar múltiples formas, simplificando el diseño de software complejo. Finalmente, abordaremos el manejo de excepciones, una habilidad crucial para construir aplicaciones que sean capaces de manejar errores de manera efectiva y continuar funcionando de manera estable.</p>
    
    <h2>Herencia</h2>
    <p>La herencia, en términos simples, es como construir a partir de cimientos ya existentes. A continuación se explica la sintaxis de la herencia:</p>
    <pre><code>//Superclase
public class Animal {
    public void hacerSonido() {
        System.out.println("El animal hace un sonido.");
    }
}

//Subclase
public class Perro extends Animal {
    @Override
    public void hacerSonido() {
        System.out.println("El perro ladra.");
    }
}
    </code></pre>
    <h3>Explicación de la sintaxis:</h3>
    <ol>
        <li><code>public class Perro extends Animal {</code>: Define una clase pública llamada Perro, que extiende la clase Animal. La palabra clave <code>extends</code> indica que Perro es una subclase de Animal, y por tanto, hereda sus métodos y atributos.</li>
        <li><code>@Override</code>: Esta es una anotación que indica que el método siguiente es una sobrescritura de un método en la superclase (Animal). En este caso, <code>hacerSonido</code> en Perro reemplaza la implementación de <code>hacerSonido</code> en Animal.</li>
        <li><code>public void hacerSonido() {</code>: Declara un método público llamado <code>hacerSonido</code> en la subclase Perro. Este método sobrescribe el método <code>hacerSonido</code> en la superclase Animal.</li>
        <li><code>System.out.println("El perro ladra.");</code>: Este método de salida imprime el texto "El perro ladra." en la consola. Esto proporciona una implementación específica del método <code>hacerSonido</code> para los objetos de la clase Perro.</li>
        <li>Las últimas 2 líneas cierran la subclase Perro.</li>
    </ol>
    
    <h3>Actividad:</h3>
    <p>Crea una clase llamada Perro, agrega un método llamado presentación y 3 subclases con nombres de razas de perros, debe heredar el método y personalizarlo.</p>
    
    <h2>Polimorfismo</h2>
    <p>El Polimorfismo es la capacidad de un objeto para adoptar múltiples formas. Permite que un objeto de una clase pueda ser tratado como un objeto de una superclase o interfaz. A continuación se muestra un ejemplo de sobrecarga y sobrescritura de métodos:</p>
    <pre><code>//Sobrecarga de métodos
public class Calculadora {
    //Método para sumar dos números enteros
    public int sumar(int a, int b) {
        return a + b;
    }

    //Método para sumar dos números de punto flotante
    public double sumar(double a, double b) {
        return a + b;
    }
}

//Polimorfismo con sobrescritura de métodos
public class Main {
    public static void main(String[] args) {
        Animal miAnimal = new Perro(); // Polimorfismo
        miAnimal.hacerSonido(); // Llama al método sobrescrito en Perro

        //Casting de referencias
        Perro miPerro = (Perro) miAnimal; // Convertir de Animal a Perro
        miPerro.hacerSonido();
    }
}
    </code></pre>
    
    <h3>Explicación de la sintaxis:</h3>
    <ol>
        <li><code>public class Calculadora {</code>: Define una clase pública llamada Calculadora. La clase Calculadora contiene métodos para realizar operaciones matemáticas básicas.</li>
        <li><code>public int sumar(int a, int b) {</code>: Declara un método público llamado <code>sumar</code> que acepta dos parámetros enteros (int a y int b) y devuelve un valor entero. El método <code>sumar</code> toma dos enteros como argumentos y retorna su suma.</li>
        <li><code>return a + b;</code>: Realiza la operación de suma sobre los parámetros <code>a</code> y <code>b</code> y devuelve el resultado. La palabra clave <code>return</code> se utiliza para enviar el resultado de la suma al lugar donde se llamó el método.</li>
        <li><code>public double sumar(double a, double b) {</code>: Declara un método público llamado <code>sumar</code> que acepta dos parámetros de tipo double y devuelve un valor de tipo double. Esta es una sobrecarga del método <code>sumar</code>, ya que tiene el mismo nombre que el anterior pero diferente tipo de parámetros.</li>
        <li><code>return a + b;</code>: Realiza la operación de suma sobre los parámetros <code>a</code> y <code>b</code>, que son números de punto flotante, y devuelve el resultado.</li>
        <li>Las siguientes 2 líneas cierran el método y la clase respectivamente.</li>
    </ol>
    
    <h3>Actividad:</h3>
    <p>Define una clase Ave con un método <code>hacerSonido</code>. Crea una subclase Loro que sobrescriba el método <code>hacerSonido</code> para que imprima "El loro imita sonidos". Instancia un objeto de tipo Ave y asigna un objeto Loro a esa referencia, luego llama al método <code>hacerSonido</code>.</p>
    
            <div class="centered-container">
            <h4>Repaso Herencia y polimorfismo</h4>
            <iframe width="853" height="480" src="https://www.youtube.com/embed/MgrpWNahzPI" title="Herencia y Polimorfismo con múltiples clases" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

            <div class="centered-container">
            <h4>Repaso Herencia y Polimorfismo con múltiples clases</h4>
            <iframe width="853" height="480" src="https://www.youtube.com/embed/MgrpWNahzPI" title="Herencia y Polimorfismo con múltiples clases" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

    <h2>Excepciones</h2>
    <p>Las excepciones en Java son eventos anómalos que pueden ocurrir durante la ejecución de un programa y que alteran el flujo normal de ejecución. Estos eventos representan situaciones inesperadas o errores que deben ser manejados de manera adecuada para garantizar que el programa continúe ejecutándose y evitar así interrupciones. Las excepciones son elementos fundamentales en la construcción de programas robustos y fiables en cualquier lenguaje de programación.</p>
    
    <p>Tipos de excepciones:</p>
    <ul>
        <li><strong>Checked exceptions</strong>: Son excepciones que se deben declarar en la firma del método o capturar explícitamente en un bloque try-catch. Si una excepción comprobada no se maneja correctamente, el código no se compilará. Estas excepciones se heredan de la clase Exception.</li>
        <li><strong>Unchecked exceptions</strong>: Son excepciones que no están obligadas a ser manejadas explícitamente. Estas excepciones ocurren durante la ejecución del programa y no se requiere que sean declaradas en la firma del método o capturadas mediante un bloque try-catch. Se heredan de la clase RuntimeException.</li>
        <li><strong>Errors</strong>: Son problemas graves que generalmente están fuera del control del programador y no deben manejarse explícitamente. Estos errores indican problemas serios que deberían detener la ejecución del programa. Se heredan de la clase Error.</li>
    </ul>
    
    <h3>Excepción Try</h3>
    <p>El bloque try se utiliza para envolver el código que puede generar una excepción. Si se produce una excepción dentro del bloque try, se captura en uno o más bloques catch asociados.</p>
    
    <h3>Finally</h3>
    <p>Se utiliza para ejecutar código que debe ejecutarse, independientemente de si se ha producido una excepción o no. Se usa para liberar recursos que deben cerrarse, como conexiones a bases de datos o archivos.</p>
    
    <h3>Ejemplo de código:</h3>
    <pre><code>public class ManejoExcepciones {
    public static void main(String[] args) {
        try {
            int resultado = dividir(10, 0);
            System.out.println("Resultado: " + resultado);
        } catch (ArithmeticException e) {
            System.out.println("Error: División por cero.");
        } finally {
            System.out.println("Bloque finally ejecutado.");
        }
    }

    public static int dividir(int a, int b) throws ArithmeticException {
        if (b == 0) {
            throw new ArithmeticException("División por cero no permitida.");
        }
        return a / b;
    }
}
    </code></pre>
    
    <h3>Explicación de la sintaxis:</h3>
    <ol>
        <li><code>public static void main(String[] args) {</code>: Define el método main, que es el punto de entrada de una aplicación Java. La firma del método main incluye: <ul><li><code>public</code>: El método es accesible desde cualquier lugar.</li><li><code>static</code>: Permite que el método se ejecute sin necesidad de instanciar la clase ManejoExcepciones.</li><li><code>void</code>: Indica que el método no devuelve ningún valor.</li><li><code>String[] args</code>: Es un array de String que puede contener argumentos pasados desde la línea de comandos.</li></ul></li>
        <li><code>try {</code>: Inicia un bloque try, que contiene el código que podría generar una excepción. Este bloque es seguido por uno o más bloques catch y opcionalmente un bloque finally para manejar posibles excepciones.</li>
        <li><code>int resultado = dividir(10, 0);</code>: Llama al método dividir con los argumentos 10 y 0. Dado que la operación de división por cero es aritméticamente indefinida, esto provocará una excepción ArithmeticException.</li>
        <li><code>catch (ArithmeticException e) {</code>: Inicia un bloque catch que captura excepciones del tipo ArithmeticException. Si se lanza una excepción ArithmeticException en el bloque try, este bloque catch la capturará y ejecutará su contenido.</li>
        <li><code>finally {</code>: Inicia un bloque finally, que siempre se ejecuta independientemente de si se lanzó una excepción o no. Esto es útil para liberar recursos o realizar acciones que deben ejecutarse pase lo que pase.</li>
        <li><code>public static int dividir(int a, int b) throws ArithmeticException {</code>: Declara un método estático llamado dividir que toma dos parámetros enteros a y b. Este método puede lanzar una excepción ArithmeticException. La palabra clave throws indica que el método puede generar esa excepción, y quien lo llame debe manejarla.</li>
        <li><code>if (b == 0) {</code>: Comienza una estructura de control if que verifica si el valor de b es 0. Esta es una condición para detectar una operación de división por cero, lo que no está permitido.</li>
        <li><code>throw new ArithmeticException("División por cero no permitida.");</code>: Si b es 0, lanza una nueva instancia de ArithmeticException con un mensaje que indica que la división por cero no está permitida. La palabra clave throw se utiliza para lanzar una excepción manualmente.</li>
    </ol>
    
    <h3>Actividad:</h3>
    <p>Crea un método <code>leerArchivo</code> que lance una excepción <code>IOException</code>. Usa <code>throws</code> en la declaración del método y maneja la excepción en un bloque <code>try-catch</code>.</p>
           
            <div class="centered-container">
            <h4>Repaso Excepciones</h4>
            <iframe width="853" height="480" src="https://www.youtube.com/embed/CBCJmbKgqGs" title="Excepciones en Java" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
    
            <div class="centered-container">
            <h4>Repaso Tratamiento de cadenas y Parseo de datos.</h4>
            <iframe width="853" height="480" src="https://www.youtube.com/embed/1T1zbCkH-_0" title="Tratamiento de cadenas y Parseo de datos." frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

                <form method="post">
                    <input type="hidden" name="progreso" value="75"> <!-- Establece el progreso a 75% -->
                    <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
                    <button type="button" id="examButton" class="btn btn-warning">Hacer Examen</button>
                    <button type="button" id="finishButton" class="btn btn-success">Terminar Módulo</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación del Examen -->
    <div class="modal fade" id="examModal" tabindex="-1" role="dialog" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examModalLabel">Confirmación de Examen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea comenzar el examen?</p>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="confirmCheckbox">
                        <label class="form-check-label" for="confirmCheckbox">Confirmo que deseo realizar el examen.</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmExamButton" class="btn btn-primary">Comenzar Examen</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var progressBar = document.getElementById('progressBar');
            var progress = <?php echo json_encode($progreso); ?>;
            progressBar.style.width = progress + '%';

            // Función para calcular el progreso basado en el scroll
            function updateProgressBar() {
                var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
                var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
                var scrollPercentage = (scrollTop / (scrollHeight - clientHeight)) * 100;
                progressBar.style.width = Math.min(scrollPercentage, 100) + '%';
            }

            window.addEventListener('scroll', updateProgressBar);

            // Modal de confirmación para el examen
            var examButton = document.getElementById('examButton');
            var confirmExamButton = document.getElementById('confirmExamButton');
            var confirmCheckbox = document.getElementById('confirmCheckbox');
            examButton.addEventListener('click', function() {
                $('#examModal').modal('show');
            });
            confirmExamButton.addEventListener('click', function() {
                if (confirmCheckbox.checked) {
                    var examUrl = 'exam3.php';
                    window.location.href = examUrl;
                } else {
                    alert('Por favor, confirme que desea realizar el examen.');
                }
            });

            // Botón Terminar Módulo
            var finishButton = document.getElementById('finishButton');
            finishButton.addEventListener('click', function() {
                if (<?php echo json_encode($progreso); ?> < 100) {
                    alert('Para terminar el módulo debes de tener el 100%');
                } else {
                    // Enviar formulario para terminar el módulo
                    var form = document.querySelector('form');
                    form.method = 'post';
                    form.innerHTML += '<input type="hidden" name="terminar">';
                    form.submit();
                }
            });

            // Animación de revelado
            var reveals = document.querySelectorAll('.reveal');
            function revealElements() {
                var windowHeight = window.innerHeight;
                reveals.forEach(function(element) {
                    var elementTop = element.getBoundingClientRect().top;
                    if (elementTop < windowHeight - 50) {
                        element.classList.add('visible');
                    }
                });
            }
            window.addEventListener('scroll', revealElements);
            revealElements(); // Llamar al cargar la página
        });
    </script>
</body>
</html>
