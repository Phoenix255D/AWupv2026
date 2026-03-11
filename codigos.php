<?php
echo "Condicionales\n\n";

//Condicionales

echo "1. IF-ELSE:\n";
$edad = 25;

// If
if ($edad >= 18) {
    echo "   Eres adulto.\n";
}

// If-else
if ($edad >= 65) {
    echo "Eres un adulto mayor.\n";
} else {
    echo "Todavia no eres un adulto mayor.\n";
}

// If-elseif-else
$puntos = 85;
echo "   Puntos: $puntos - promedio: ";
if ($puntos >= 90) {
    echo "A\n";
} elseif ($puntos >= 80) {
    echo "B\n";
} elseif ($puntos >= 70) {
    echo "C\n";
} elseif ($puntos >= 60) {
    echo "D\n";
} else {
    echo "F\n";
}

echo "\n2. SWITCH:\n";
$dia = "Lunes";

switch ($dia) {
    case "Lunes":
        echo "Inicio de la semana\n";
        break;
    case "Martes":
    case "Miercoles":
    case "Jueves":
        echo " Media semana\n";
        break;
    case "Viernes":
        echo " ¡Viernes por fin!\n";
        break;
    case "Sabado":
    case "Domingo":
        echo " Fin de semana!\n";
        break;
    default:
        echo " No existe\n";
}

// Bucles

echo "\n3. FOR:\n";
echo "   Contador de 1 a 5:\n   ";
for ($i = 1; $i <= 5; $i++) {
    echo "$i ";
}

echo "\n\n Tabla de multiplicar (3):\n";
for ($i = 1; $i <= 10; $i++) {
    $resultado = 3 * $i;
    echo "   3 × $i = $resultado\n";
}

echo "\n4. FOREACH:\n";
$fruta = ["Manzana", "Platano", "Cereza", "Frambuesa"];

echo " Array de fruta:\n";
foreach ($fruta as $fruta) {
    echo " - $fruta\n";
}

echo "\n Array asociativo:\n";
$estudiante = [
    "nombre" => "Pedro",
    "edad" => 21,
    "calificacion" => "A",
    "curso" => ["Matematicas", "Ciencias", "Historia"]
];

foreach ($estudiante as $key => $value) {
    if (is_array($value)) {
        echo " $key: " . implode(", ", $value) . "\n";
    } else {
        echo " $key: $value\n";
    }
}

echo "\n7. WHILE:\n";
echo "   Contador desde 5:\n   ";
$contador = 5;
while ($contador > 0) {
    echo "$contador ";
    $contador--;
}
echo "Fin\n";

echo "\n procesar ordenes hasta acabar:\n";
$ordenes = [101, 102, 103, 104];
$ordeIndex = 0;
while (isset($ordenes[$ordeIndex])) {
    echo " Procesando orden #{$ordenes[$ordeIndex]}\n";
    $ordeIndex++;
}

echo "\n8. DO-WHILE:\n";
echo "   Juego de adivinar:\n";
$numeroSecreto = 7;
$adivino = 0;
$intento = 0;

do {
    $adivino = rand(1, 10);
    $intento++;
    echo "   Intento no. $intento: adivinó $adivino\n";
} while ($adivino !== $numeroSecreto && $intento < 5);

if ($adivino === $numeroSecreto) {
    echo " Numero $numeroSecreto econtrado en $intento intentos!\n";
} else {
    echo " Fallaste, $intento intentos\n";
}

?>