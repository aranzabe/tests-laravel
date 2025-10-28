# Tests-Laravel

Recuerda que para que funcione el ejemplo, una vez realizado el pull deberás realizar (dentro de la carpeta del proyecto) un:
````
composer update 
````
Tendremos que regenerar la key del proyecto con:
````
cp .env.example .env           
php artisan key:generate
````
También tienes una base de datos exportada en la carpeta del proyecto. Configura el .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ejemploEloquent
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

## 1️⃣ ¿Qué son los tests en Laravel?

Laravel incorpora PHPUnit y una capa superior propia llamada **Laravel Test Framework**, que permite escribir tests legibles y expresivos.

Los tests sirven para **verificar automáticamente que la aplicación funciona correctamente** al realizar cambios en el código.

---

## 2️⃣ Tipos de tests

Laravel organiza los tests en dos carpetas:

```bash
tests/
 ├── Feature/
 └── Unit/
```

### 🔹 **Unit Tests**

- Se crean con:

```bash
php artisan make:test NombreTest --unit
```

- Se guardan en `tests/Unit/`.
- **Prueban funciones o clases específicas.**
- Son los **más rápidos**.
- **No deben incluir pruebas de BD, emails o llamadas externas.**

### 🔹 **Feature Tests (o de integración)**

- Se crean con:

```bash
php artisan make:test NombreTest
```

- Se guardan en `tests/Feature/`.
- **Prueban flujos completos**: rutas, controladores, middlewares, base de datos, mail, etc.
- Ideales para testear **rutas API REST**.

> ⚠️ Importante: el sufijo Test en el nombre de la clase es obligatorio para que PHPUnit reconozca los tests.
> 
> 
> Ejemplo correcto: `UserApiTest.php`.
> 

---

## **3️⃣ Crear tests**

```bash
php artisan make:test LoQueSeaTest          # Crea un test de tipo Feature
php artisan make:test LoQueSeaTest --unit   # Crea un test de tipo Unit
```

Por defecto, Laravel crea los tests dentro de `tests/Feature/`.

---

## 4️⃣ Ejecutar los tests

### 🧩 Todos los tests:

```bash
php artisan test
# o también
./vendor/bin/phpunit
```

🧩 Un test concreto (por clase):

```bash
php artisan test tests/Feature/PruebasBorradoTest.php
# o
./vendor/bin/phpunit tests/Feature/PruebasBorradoTest.php
```

🧩 Filtrar por nombre de clase o método:

```bash
php artisan test --filter=PruebasBorradoTest
php artisan test --filter=test_insertar2
php artisan test --filter=test_colocar_minas
```

🧩 Filtrar por tipo de test (Feature o Unit):

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
# o con PHPUnit:
./vendor/bin/phpunit --filter Feature
./vendor/bin/phpunit --filter Unit
```

**🧩 Modo detallado (verbose):**

```bash
php artisan test -v
```

---

## 5️⃣ Testeando rutas API REST (Feature Tests)

Laravel permite simular peticiones HTTP fácilmente:

| Método | Descripción |
| --- | --- |
| `get($url)` | Petición GET |
| `post($url, $data)` | Petición POST |
| `put($url, $data)` | Petición PUT |
| `delete($url)` | Petición DELETE |
| `patch($url, $data)` | Petición PATCH |
| `getJson()`, `postJson()`, etc. | Versión adaptada para APIs JSON |

Y para validar respuestas:

| Método | Verificación |
| --- | --- |
| `assertStatus($code)` | Código de estado HTTP |
| `assertJson([...])` | Contenido JSON exacto |
| `assertJsonFragment([...])` | Fragmento JSON |
| `assertJsonCount($n)` | Número de elementos |
| `assertDatabaseHas($table, [...])` | Datos insertados |
| `assertDatabaseMissing($table, [...])` | Datos borrados |

---

## 6️⃣ Ejemplo completo de CRUD API (Feature Tests)

Supongamos una API `/api/users`.

🟢 Crear un usuario

```php
public function test_can_create_user()
{
    $data = [
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com',
        'password' => 'secret123'
    ];

    $response = $this->postJson('/api/users', $data);

    $response->assertStatus(201)
             ->assertJsonFragment(['email' => 'juan@example.com']);

    $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
}
```

🔵 Listar usuarios

```php
public function test_can_list_users()
{
    \App\Models\User::factory()->count(3)->create();

    $response = $this->getJson('/api/users');

    $response->assertStatus(200)
             ->assertJsonCount(3);
}
```

🟣 Mostrar usuario concreto

```php
public function test_can_show_a_user()
{
    $user = \App\Models\User::factory()->create();

    $response = $this->getJson("/api/users/{$user->id}");

    $response->assertStatus(200)
             ->assertJsonFragment(['email' => $user->email]);
}
```

🟠 Actualizar usuario

```php
public function test_can_update_user()
{
    $user = \App\Models\User::factory()->create();

    $data = ['name' => 'Nombre actualizado'];

    $response = $this->putJson("/api/users/{$user->id}", $data);

    $response->assertStatus(200)
             ->assertJsonFragment(['name' => 'Nombre actualizado']);

    $this->assertDatabaseHas('users', ['name' => 'Nombre actualizado']);
}
```

🔴 Eliminar usuario

```php
public function test_can_delete_user()
{
    $user = \App\Models\User::factory()->create();

    $response = $this->deleteJson("/api/users/{$user->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
}
```

---

## 7️⃣ Otros ejemplos

### 🅰️ Ejemplo de pruebas unitarias, `PruebasUnitariasTest`:

```bash
php artisan make:test PruebasUnitariasTest --unit
```

```php

namespace Tests\Unit;

use App\Models\Buscaminas;
use PHPUnit\Framework\TestCase;

class PruebasUnitariasTest extends TestCase
{
        public function test_iniciar_tablero()
    {
        $tam = rand(1,10);
        // echo 'Iniciado tablero con '.$tam;
        $b = new Buscaminas($tam);
        $response = $b->iniciarTablero($tam);
        $this->assertEquals($tam, $response);
    }

    public function test_colocar_minas(){
        $b = new Buscaminas();
        $tam = rand(1,5);
        $b->iniciarTablero($tam);

        $minas = rand(1,3);
        $b->colocarMinas($minas);
        $ocurrencias = array_count_values($b->tablero);
        // print_r($ocurrencias);
        // print_r($b->tablero);

        $this->assertEquals($minas, $ocurrencias['*']);
    }

    public function test_calcular_precios(){
        $cantidad = 10;
        $precio = 100;
        $b = new Buscaminas();
        $this->assertEquals($b->calcularPrecio($cantidad, $precio),800);
    }

```

Siendo la clase `Buscaminas`:

```php
class Buscaminas {
    public $tablero;
    public $descuento = 0.2;

    public function iniciarTablero($cant) {
        $this->tablero = array_fill(0,$cant,"-");
        return count($this->tablero);
    }

    public function colocarMinas($minas){
        while($minas>0){
            $pos = rand(0,count($this->tablero)-1);
            if ($this->tablero[$pos] == '-'){
                $this->tablero[$pos] = '*';
                $minas--;
            }
        }
    }

    public function calcularPrecio($cant, $precio) {
        return $cant * $precio * (1-$this->descuento);
    }

```

### 🅱️ Ejemplo de pruebas features:

`PruebasBorradoTest:` 

```bash
php artisan make:test PruebasBorradoTest
```

```php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasBorradoTest extends TestCase
{
    public function test_borrar()
    {
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando para borrar persona: ';
        print_r ($datos);

        $this->json('post', '/api/insertarpersona', $datos);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"])
            ->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Persona eliminada correctamente.',
            ]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"])
            ->assertStatus(404)
            ->assertJson([
                'mensaje' => 'Persona no encontrada.',
            ]);
    }

    public function test_borrar2(){
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando para borrar persona: ';
        print_r ($datos);

        //Confirma que la persona fue insertada en la base de datos
        //$this->json('post', '/api/insertarpersona', $datos);
        //También se puede hacer así:
        $response = $this->postJson('/api/insertarpersona', $datos);

        $this->assertDatabaseHas('personas', ['dni' => $datos['dni']]);

        $response = $this->delete("/api/borrarpersona/".$datos["dni"]);
        $response->assertStatus(200);
        $response->assertJson(['mensaje' => 'Persona eliminada correctamente.']);

        $response = $this->delete("/api/borrarpersona/".$datos["dni"]);
        $response->assertStatus(404);
        $response->assertJson(['mensaje' => 'Persona no encontrada.']);
    }

```

`PruebasBusquedaTest`:

```bash
php artisan make:test PruebasBusquedaTest
```

```php
namespace Tests\Feature;

use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasBusquedaTest extends TestCase
{
    //use RefreshDatabase; //Limpia y migra la base de datos antes de cada test

    public function test_buscar_persona() {

        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->numerify('#########'),  //Para tener números sin símbolos y que no falle el test.
            "edad" => rand(18,100)
        ];

        $this->json('post', '/api/insertarpersona', $datos);

        echo 'Insertado para buscar persona: ';
        print_r ($datos);

        $this->json('get', '/api/buscarpersona/'.$datos["dni"])
        ->assertStatus(200)
        ->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"], "edad" => $datos["edad"]])
        ->assertJsonStructure(
            ['dni', 'nombre', "tfno"]);
            // ['dni', 'nombre', "tfno","edad"]);

        //Borramos el caso de prueba.
        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);
    }

    public function test_buscar_persona2() {
        //Oción A)
        //op A.1
        // $fak = \Faker\Factory::create('es_ES');

        // $datos = [
        //     "dni" => $fak->dni,
        //     "nombre" => $fak->name,
        //     "tfno" => $fak->numerify('#########'),  //Para tener números sin símbolos y que no falle el test.
        //     "edad" => rand(18,100)
        // ];

        //op A.2
        //O usamos la factoría pero la opción make que no lo inserta en la base de datos.
        //$datos = Persona::factory()->make()->toArray();

        //Común a la opción A.1 y A.2.
        // $this->json('post', '/api/insertarpersona', $datos);

        // echo 'Insertado para buscar persona: ';
        // print_r ($datos);

        //Opción B)
        $datos = Persona::factory()->create()->toArray();
        echo 'Insertando desde una factoría una persona para probar búsqueda: ';
        print_r ($datos);

        //Común a la opción A y B.
        //Confirma que la persona fue insertada en la base de datos
        $this->assertDatabaseHas('personas', ['dni' => $datos['dni']]);

        //$response = $this->get('/api/buscarpersona/'.$datos["dni"]);
        //O tambén se puede hacer así:
        $response = $this->getJson('/api/buscarpersona/'.$datos["dni"]);
        $response->assertStatus(200);
        $response->assertJson(["dni" => $datos["dni"],
                               "nombre" => $datos["nombre"],
                               "tfno" => $datos["tfno"],
                               "edad" => $datos["edad"]]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);//Esta opción es común a las dos opciones.
    }
}
```

Y `PruebasInsercionTest`:

```bash
php artisan make:test PruebasInsercionTest
```

```php
namespace Tests\Feature;

use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasInsercionTest extends TestCase
{
    public function test_insertar()
    {
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando persona para probar inserción: ';
        print_r ($datos);

        $this->json('post', '/api/insertarpersona', $datos)
            ->assertStatus(201)
            ->assertJsonStructure(['dni', 'nombre', "tfno"])
            ->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"]]);

        $this->json('post', '/api/insertarpersona', $datos)
            ->assertStatus(404)
            ->assertJson([
                'mens' => 'Clave duplicada',
            ]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);
    }

    public function test_insertar2()
    {
        //Opción A)
        // op A.1
        //O usamos el faker aquí.
        // $fak = \Faker\Factory::create('es_ES');
        // $datos = [
        //     "dni" => $fak->dni,
        //     "nombre" => $fak->name,
        //     "tfno" => $fak->phoneNumber,
        //     "edad" => rand(18,100)
        // ];
        // op A.2
        //O usamos la factoría pero la opción make que no lo inserta en la base de datos.
        $datos = Persona::factory()->make()->toArray();

        $response = $this->post('/api/insertarpersona', $datos);
        $response->assertStatus(201);
        $response->assertJsonStructure(['dni', 'nombre', "tfno", "edad"]);
        $response->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"], "edad" => $datos["edad"]]);

        echo 'Insertando persona para probar inserción: ';
        print_r ($datos);

        //Opción B)
        //Otra opción, usar la factoría con create que sí lo inserta en la base de datos.
        // $datos = Persona::factory()->create()->toArray();
        // echo '------->  Insertando desde una factoría una persona para probar inserción, clave duplicada: ';
        // print_r ($datos);

        //Común a la opción A y B.
        $response = $this->post('/api/insertarpersona', $datos);
        $response->assertStatus(404);
        $response->assertJson(['mens' => 'Clave duplicada']);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]); //Esta opción es común a las dos opciones.
    }
}
```

---

## 8️⃣ Consejos finales

✅ Usa `RefreshDatabase` para limpiar la BD entre tests.

⚙️ ¿Cómo se usa?

Simplemente añades el **trait** `RefreshDatabase` en tu clase de test, así:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase; // 👈 Limpia y migra la base de datos antes de cada test

    public function test_can_create_user()
    {
        // tu código de test aquí
    }
}
```

- Usa `RefreshDatabase` **solo en tests Feature** (los que tocan BD).
    
    ❌ No lo uses en tests unitarios, porque no deberían tocar base de datos.
    
- Si tienes **muchos tests**, combinar `RefreshDatabase` con `--parallel` hace que el tiempo total siga siendo rápido.
- Si necesitas **sembrar datos iniciales** (por ejemplo, roles o configuraciones), puedes usar:

```php
use Database\Seeders\RoleSeeder;

$this->seed(RoleSeeder::class);
```

✅ Mantén los nombres descriptivos (`test_can_create_user`, `test_eliminar_usuario`, etc.).

✅ Los tests deben ser **independientes** (no dependen del orden).

✅ Usa `factories` para generar datos de prueba, guarda o no según uses ***make*** o ***create***.

```php
$datos = Persona::factory()->make()->toArray();
//o
$datos = Persona::factory()->create()->toArray();
```

✅ Laravel 12 permite usar `--parallel` para correr tests más rápido:

## ⚙️ **¿Cómo funciona `-parallel`?**

Laravel usa internamente la librería **ParaTest** para lanzar varios procesos de PHPUnit a la vez.

Cada proceso ejecuta una parte distinta del conjunto total de tests.

Ejemplo:

- Si tienes 200 tests y 4 procesos paralelos, Laravel lanza 4 grupos de 50 tests cada uno simultáneamente.
- Cuando todos terminan, combina los resultados en un solo informe.

---

## 🚀 **Ventajas**

- Mucho **más rápido** (a menudo 2× o 3× según tu CPU).
- Muy útil en proyectos grandes con muchos tests Feature o con base de datos.

---

## ⚠️ **Cosas importantes a tener en cuenta**

1. **Base de datos separada por proceso:**
    
    Laravel crea copias de la base de datos de pruebas (por ejemplo `testing_1`, `testing_2`, etc.) para que los procesos no interfieran entre sí.
    
    Esto se maneja automáticamente, pero puedes configurarlo en `phpunit.xml` o en tu `.env.testing`.
    
2. **No mezclar datos entre procesos:**
    
    No guardes archivos o estados compartidos entre tests (cada proceso es independiente).
    
3. **Limpieza automática:**
    
    Al terminar, Laravel elimina las bases de datos de test creadas.
    
4. **Puedes especificar cuántos procesos quieres:**

🧩 **Ejemplo de uso**

```bash
# Ejecuta todos los tests en paralelo usando el número de núcleos de la CPU
php artisan test --parallel

# Ejecuta solo los tests de Feature en paralelo
php artisan test --testsuite=Feature --parallel

# Ejecuta con 8 procesos
php artisan test --parallel --processes=8
```

🔍 **Cuándo merece la pena usarlo**

| Tipo de proyecto | ¿Usar `--parallel`? | Motivo |
| --- | --- | --- |
| Pequeño (menos de 50 tests) | ❌ No necesario | No notarás mejora |
| Mediano (100–500 tests) | ✅ Recomendado | Acelera bastante |
| Grande (1000+ tests) | 🚀 Muy recomendado | Reduce el tiempo de minutos a segundos |

En resumen:

```
php artisan test --parallel
```

**divide el trabajo entre varios procesos** para que tus tests corran **más rápido,** especialmente útil en proyectos con muchos **Feature tests** que acceden a base de datos o servicios externos.
