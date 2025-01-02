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
$moduleId = 2; // Módulo actual

// Crear instancias de los servicios
$moduleService = new ModuleService($pdo);
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
        // Si el progreso es 100%, redirigir al módulo 3
        header('Location: module3.php');
        exit;
    } elseif (isset($_POST['volver'])) {
        // Redirigir al dashboard
        header('Location: ../dashboard');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo 2 - Avanzando en OOP</title>
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
                <h3>Unidad 2: Fundamentos de Programación Orientada a Objetos</h3>

    <p>Aprenderemos sobre los aspectos léxicos esenciales como la sintaxis para la creación de clases, tipos de datos, atributos, métodos, y la declaración de objetos. Aprenderemos sobre las estructuras de control básicas que permiten la toma de decisiones y las ejecuciones de operaciones repetitivas. Al final de la unidad habrás aprendido las bases necesarias para entender y aplicar los principios básicos de la POO.</p>

    <h2>Aspectos léxicos</h2>
    <p>Identificar la sintaxis de la declaración de clases, tipos de datos, atributos, variables, constantes, métodos, instancias y modificadores de acceso.</p>

    <h3>¿Qué es la Sintaxis?</h3>
    <p>La sintaxis se refiere a las reglas que rigen la estructura de los símbolos, las palabras y la puntuación que se encuentran en los lenguajes de programación.</p>
    <p>La sintaxis de los lenguajes de programación define el significado de las distintas combinaciones de símbolos. También identifica las palabras clave y los símbolos válidos que un programador puede utilizar para escribir su código fuente. Y del mismo modo que las reglas gramaticales rigen cada lenguaje humano, una sintaxis única rige cada lenguaje de programación.</p>
    <p>Para dominar un lenguaje de programación, es importante entender su sintaxis. Escribir el código de acuerdo con las reglas estructurales establecidas, facilita su comprensión tanto para las máquinas como para los humanos.</p>
    <p>El código legible se explica por sí mismo y es fácilmente comprensible para diferentes personas. Esto fomenta la colaboración y la innovación.</p>

    <h3>El código legible se adhiere a las cuatro C de la programación:</h3>
    <ul>
        <li><strong>Integración del código</strong>: El código legible puede ser entendido fácilmente por los compiladores e intérpretes.</li>
        <li><strong>Comunicación:</strong> Un código bien escrito se explica por sí mismo, sin dejar lugar a la confusión o la ambigüedad.</li>
        <li><strong>Coherencia</strong>: El código es coherente cuando se adhiere fielmente a la sintaxis establecida. Facilita resultados predecibles y hace que el código sea más fácil de entender.</li>
        <li><strong>Claridad</strong>: Un código legible comunica su función e intención con claridad.</li>
    </ul>

    <h2>Clases</h2>
    <p>Una clase es la descripción de una familia de objetos que tienen la misma estructura (atributos) y el mismo comportamiento (métodos). Las clases son el patrón habitual que proporcionan los lenguajes orientados a objetos para definir la implementación de los objetos.</p>

    <h3>Sintaxis en la Declaración de una clase:</h3>
    <pre><code>
public class Animal {
    private String nombre;
    private int edad;
    //Constructor de la clase Animal
    public Animal(String nombre, int edad){
        this.nombre=nombre;
        this.edad=edad;
    }
}
    </code></pre>

    <h3>Explicación línea a línea:</h3>
    <ol>
        <li><strong>public class Animal {</strong>: Esta línea define una nueva clase llamada Animal. La palabra clave public indica que esta clase es accesible desde otras clases. El nombre de la clase debe coincidir con el nombre del archivo en el que se guarda el código, es decir, Animal.java.</li>
        <li><strong>private String nombre;</strong>: Se declara un atributo privado de tipo String llamado nombre. La palabra clave private significa que este atributo solo es accesible dentro de la clase Animal, lo que garantiza el encapsulamiento y protección de los datos.</li>
        <li><strong>private int edad;</strong>: Similar a la línea anterior, esta línea declara un atributo privado de tipo int llamado edad, que almacena la edad del animal.</li>
        <li><strong>public Animal(String nombre, int edad) {</strong>: Se declara un constructor para la clase Animal. Un constructor es un método especial que se llama cuando se crea una nueva instancia de la clase. El constructor tiene el mismo nombre que la clase y no tiene un tipo de retorno. Aquí, el constructor toma dos parámetros: nombre (de tipo String) y edad (de tipo int).</li>
        <li><strong>this.nombre = nombre;</strong>: Dentro del constructor, this.nombre se refiere al atributo nombre de la instancia actual de Animal. La expresión this.nombre = nombre; asigna el valor del parámetro nombre al atributo nombre de la clase. La palabra clave this se utiliza para diferenciar entre el atributo de la clase y el parámetro del constructor, ya que tienen el mismo nombre.</li>
        <li><strong>this.edad = edad;</strong>: De manera similar a la línea anterior, esta línea asigna el valor del parámetro edad al atributo edad de la clase. La palabra clave this se usa nuevamente para aclarar que nos referimos al atributo de la instancia actual.</li>
        <li>Las últimas dos líneas son las llaves de cierre del constructor y de la clase respectivamente.</li>
    </ol>

    <h3>Actividades:</h3>
    <ol>
        <li>Crea una clase llamada Persona y agrega por lo menos 3 atributos</li>
        <li>Usando el código anterior de la clase Persona, crea un constructor para la clase Personas.</li>
    </ol>
        <div class="centered-container">
        <h4>Repaso Clase Persona</h4>
        <iframe width="853" height="480" src="https://www.youtube.com/embed/lGxHDkoQnrM" title="Clase Persona - Java Academy" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
    
    <h2>Métodos</h2>
    <p>Los métodos son bloques de código que se utilizan para realizar tareas específicas. Cada método tiene un nombre que lo identifica y puede recibir una serie de parámetros, los cuales son variables que proporcionan información necesaria para que el método realice su tarea. Estos parámetros actúan como entrada para el método, permitiéndole operar con los valores recibidos.</p>

    <h3>Sintaxis para los métodos:</h3>
    <pre><code>
public class Animal{
    private String nombre;
    private int edad;
    
    //Constructor de la clase Animal
    public Animal(String nombre, int edad){
        this.nombre=nombre;
        this.edad=edad;
    }
    
    //Método para imprimir un saludo
    public void emitirSonido(){
        System.out.println("Hola, soy un " + nombre + "y tengo " + edad + "años.");
    }
}
    </code></pre>

    <h3>Explicación línea a línea del método emitirSonido:</h3>
    <ol>
        <li><strong>public void emitirSonido() {</strong>: Esta línea define un método público llamado emitirSonido. El tipo de retorno es void, lo que significa que este método no devuelve ningún valor. Este método puede ser llamado desde otras clases, siempre que tengan acceso a una instancia de Animal.</li>
        <li><strong>System.out.println("Hola, soy un " + nombre + " y tengo " + edad + " años.");</strong>: Dentro del método emitirSonido, esta línea imprime un mensaje en la consola. La expresión System.out.println es un método de Java que se utiliza para imprimir texto en la consola. La cadena de texto que se imprime combina texto literal con los valores de los atributos nombre y edad, utilizando el operador de concatenación +.</li>
        <li>La última línea es la llave de cierre del método</li>
    </ol>

    <h3>Actividad:</h3>
    <p>Crea un método público en la clase personas para presentarse y otro para decir su profesión.</p>

    <h2>Estructuras de Control</h2>
    <p>Las estructuras de control determinan la secuencia de ejecución de las sentencias de un programa.</p>
    <p>Los programas contienen instrucciones que se ejecutan generalmente una a continuación de la otra según la secuencia en la que el programador ha escrito el código. Sin embargo, hay ocasiones en las que es necesario romper esta secuencia de ejecución para hacer que una serie de instrucciones se ejecuten o no dependiendo de una determinada condición o hacer que una serie de instrucciones se repitan un número determinado de veces.</p>
    <p>Las estructuras de control permiten modificar el orden natural de ejecución de un programa. Mediante ellas podemos conseguir que el flujo de ejecución de las instrucciones sea el natural o varíe según se cumpla o no una condición o que un bloque de instrucciones se repitan dependiendo de que una condición se cumpla o no.</p>

    <h3>Estructuras de decisión:</h3>
    <p>Las sentencias de decisión son sentencias que nos permiten tomar una decisión para poder ejecutar un bloque de sentencias u otro.</p>
    <p>Existen las siguientes estructuras de decisión:</p>
    <ul>
        <li>if</li>
        <li>else</li>
        <li>switch</li>
    </ul>

    <h3>Estructuras de repetición:</h3>
    <p>Los ciclos o también conocidos como bucles, son una estructura de control de total importancia para el proceso de creación de un programa.</p>
    <p>Un ciclo en Java o bucle en Java permite repetir una o varias instrucciones cuantas veces lo necesitemos o sea necesario.</p>
    <p>Existen diferentes tipos de ciclos o bucles en Java, cada uno tiene una utilidad para casos específicos y depende de nuestra habilidad y conocimientos poder determinar en qué momento es bueno usar alguno de ellos:</p>
    <ul>
        <li>Ciclo for</li>
        <li>Ciclo while</li>
        <li>Ciclo do-while</li>
    </ul>

    <h3>Ejemplo de estructuras de control:</h3>
    <pre><code>
//Estructura de decisión
if(edad > 18){
    System.out.println("Eres mayor de edad.");
} else{
    System.out.println("Eres menor de edad.");
}
//Estructura de repetición 
for(int i=0; i<5; i++){
    System.out.println("Iteracion: " + i);
}
    </code></pre>

    <h3>Explicación línea a línea:</h3>
    <ol>
        <li><strong>if (edad > 18) {</strong>: Inicia una sentencia if que evalúa la condición edad > 18. La sentencia if es una estructura de control que permite ejecutar un bloque de código solo si una condición especificada se evalúa como verdadera. Aquí, la condición verifica si el valor de la variable edad es mayor que 18.</li>
        <li><strong>System.out.println("Eres mayor de edad.");</strong>: Si la condición del if es verdadera (edad es mayor que 18), se ejecuta esta línea, que imprime el mensaje "Eres mayor de edad." en la consola. System.out.println es un método que envía una línea de texto a la salida estándar, que generalmente es la consola.</li>
        <li><strong>}</strong>: Esta llave cierra el bloque de código que se ejecuta si la condición del if es verdadera.</li>
        <li><strong>else {</strong>: La palabra clave else indica el inicio de un bloque alternativo que se ejecutará si la condición del if es falsa. Si edad no es mayor que 18, el programa ejecutará el bloque dentro de else.</li>
        <li><strong>System.out.println("Eres menor de edad.");</strong>: Si la condición del if es falsa, se ejecuta esta línea, que imprime el mensaje "Eres menor de edad." en la consola.</li>
        <li><strong>}</strong>: Esta llave cierra el bloque de código del else.</li>
        <li><strong>for (int i = 0; i < 5; i++) {</strong>: Inicia un bucle for, que es una estructura de control utilizada para repetir un bloque de código un número específico de veces. La estructura del bucle for en Java se compone de tres partes:
            <ul>
                <li>Inicialización (int i = 0): Se declara e inicializa una variable de control i con un valor inicial de 0.</li>
                <li>Condición (i < 5): Se evalúa antes de cada iteración. El bucle continúa ejecutándose mientras esta condición sea verdadera.</li>
                <li>Incremento (i++): Se ejecuta al final de cada iteración. En este caso, i++ incrementa el valor de i en 1.</li>
            </ul>
        </li>
        <li><strong>System.out.println("Iteración: " + i);</strong>: Dentro del cuerpo del bucle for, esta línea imprime un mensaje indicando la iteración actual, junto con el valor de i. El operador de concatenación + se utiliza para unir el texto literal "Iteración: " con el valor actual de i, que es el número de la iteración actual. Esta línea se ejecuta cinco veces, con i tomando valores de 0 a 4.</li>
        <li><strong>}</strong>: Esta llave cierra el bloque de código del bucle for. Una vez que la condición del bucle (i < 5) se vuelve falsa (cuando i alcanza 5), el programa continúa con el siguiente bloque de código fuera del bucle.</li>
    </ol>

    <h3>Actividad:</h3>
    <p>Escribe un programa que pida al usuario un número y determine si es par o impar usando una estructura if-else.</p>
    <div>
	    <div class="centered-container">
    <h4>Repaso Estructura de Control</h4>
    <iframe width="853" height="480" src="https://www.youtube.com/embed/oONBIwxTeI8" title="Repaso Estructura de Control" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</div>

    <form method="post">
                    <input type="hidden" name="progreso" value="75">
                    <button type="submit" name="volver" class="btn btn-secondary">Volver</button>
                    <button type="button" id="examButton" class="btn btn-warning">Examen</button>
                    <button type="submit" name="continuar" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="examModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examModalLabel">Confirmar Examen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea comenzar el examen? Asegúrese de haber revisado todo el contenido antes de proceder.</p>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="confirmCheck">
                        <label class="form-check-label" for="confirmCheck">Confirmo que he revisado el contenido.</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="startExamButton" class="btn btn-primary" disabled>Comenzar Examen</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var totalHeight = $(document).height() - $(window).height();
            var progressBar = $('#progressBar');
            var examButton = $('#examButton');

            function updateProgressBar() {
                var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var scrolled = (winScroll / height) * 100;
                var progress = Math.min(scrolled, 75); // Limitar el progreso a 75%
                progressBar.css('width', progress + '%');

                if (progress >= 75) {
                    examButton.prop('disabled', false);
                }
            }

            $(window).scroll(function() {
                updateProgressBar();

                $('.reveal').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();

                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).addClass('visible');
                    }
                });
            });

            $('#examButton').click(function() {
                $('#examModal').modal('show');
            });

            $('#confirmCheck').change(function() {
                $('#startExamButton').prop('disabled', !this.checked);
            });

            $('#startExamButton').click(function() {
                $('#examModal').modal('hide');
                window.location.href = 'exam2.php'; // Redirige al examen en la misma ventana
            });

            // Inicializar la barra de progreso
            updateProgressBar();
        });
    </script>
</body>
</html>