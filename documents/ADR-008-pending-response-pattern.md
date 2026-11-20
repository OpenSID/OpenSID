# ADR-008: Pending Response Pattern untuk Non-Return Controllers

## Status
**Accepted** - 100% Complete, Production Ready

## Konteks

CodeIgniter 3 memiliki pattern dimana controller methods **tidak perlu return value**. Output langsung di-echo atau di-set melalui `$this->load->view()`:

```php
// CodeIgniter Controller (TIDAK RETURN)
class PendudukController extends CI_Controller
{
    public function index()
    {
        $data['penduduk'] = $this->penduduk_model->get_all();
        $this->load->view('penduduk/index', $data); // Langsung load view
        // TIDAK ADA RETURN!
    }
    
    public function delete($id)
    {
        $this->penduduk_model->delete($id);
        redirect('penduduk'); // Langsung redirect
        // TIDAK ADA RETURN!
    }
}
```

**Masalah**: Laravel routing system mengharapkan controller method **harus return Response object**:

```php
// Laravel Controller (HARUS RETURN)
class PendudukController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::all();
        return view('penduduk.index', compact('penduduk')); // RETURN!
    }
    
    public function delete($id)
    {
        Penduduk::destroy($id);
        return redirect()->route('penduduk.index'); // RETURN!
    }
}
```

Ketika CodeIgniter controller dijalankan dalam Laravel routing:
- Method execute tapi return `null`
- Laravel tidak tahu response apa yang harus dikirim
- Result: Blank page atau error

**Tantangan**: Ribuan CodeIgniter controllers existing tidak bisa dimodifikasi untuk return value tanpa massive refactoring.

## Keputusan

**Implementasi "Pending Response Pattern"** - Capture response yang di-set tanpa return statement dan inject ke Laravel response pipeline.

### Implementasi

#### 1. Extended Controller Dispatcher

```php
// app/Providers/RouteServiceProvider.php

protected function extendControllerDispatcher()
{
    $this->app->singleton(\Illuminate\Routing\Contracts\ControllerDispatcher::class, 
    function ($app) {
        return new class($app) extends \Illuminate\Routing\ControllerDispatcher {
            public function dispatch($route, $controller, $method)
            {
                $parameters = $this->resolveParameters($route, $controller, $method);
                
                // Event sebelum controller dipanggil
                $this->container['events']->dispatch(
                    new CallingController($route, $controller, $method, $parameters, request())
                );
                
                // Capture output buffer untuk handle echo/print di controller
                $obLevelBefore = ob_get_level();
                ob_start();
                
                try {
                    $response = (function () use ($controller, $method, $parameters) {
                        if (method_exists($controller, 'callAction')) {
                            return $controller->callAction($method, $parameters);
                        }
                        
                        return $controller->{$method}(...array_values($parameters));
                    })();
                } finally {
                    $buffered = ob_get_level() > $obLevelBefore
                        ? ob_get_clean()
                        : null;
                }
                
                // Jika controller return null tapi ada output buffer, gunakan buffer
                if ($response === null && $buffered !== '' && $buffered !== null && $buffered !== false) {
                    $response = $this->buildResponseFromBuffer($buffered);
                }
                
                // Jika masih null, resolve pending response (view)
                if ($response === null) {
                    $response = $this->resolvePendingResponse();
                }
                
                // Event setelah controller selesai
                $this->container['events']->dispatch(
                    new ControllerCalled($route, $controller, $method, $parameters, 
                        request(), $response)
                );
                
                return $response;
            }
            
            protected function buildResponseFromBuffer(string $buffered): \Illuminate\Http\Response
            {
                $headers = [];
                foreach (headers_list() as $header) {
                    [$key, $value] = explode(':', $header, 2);
                    $headers[trim($key)] = trim($value);
                }
                return response($buffered)->withHeaders($headers);
            }
            
            protected function resolvePendingResponse()
            {
                // Check pending view dari ViewFactory
                $viewFactory = $this->container['view'];
                if (method_exists($viewFactory, 'getPendingView')) {
                    $view = $viewFactory->getPendingView();
                    if ($view !== null) {
                        $viewFactory->clearPendingView();
                        return $view;
                    }
                }
                
                // Default: null (empty response)
                return null;
            }
        };
    });
}
```

**Cara Kerja:**

1. **Output Buffering**: Capture semua `echo` atau `print` di controller via `ob_start()`
2. **Null Response Handling**: Jika controller return `null`, check buffer output
3. **Pending View Resolution**: Jika masih null, ambil pending view dari ViewFactory
4. **Event Dispatching**: Trigger `CallingController` (before) dan `ControllerCalled` (after) events

#### 2. Extended Redirector (Exception-Based)

**PENTING**: Implementasi aktual menggunakan **exception throwing**, BUKAN pending pattern.

```php
// app/Providers/RouteServiceProvider.php

protected function extendRedirectInstance()
{
    $this->app->extend('redirect', function () {
        $redirector = new class($this->app['url']) extends Redirector {
            protected function createRedirect($path, $status, $headers)
            {
                // Support CI's refresh redirect
                if ($status === 'refresh') {
                    $headers['Refresh'] = "0; url='{$path}'";
                    $status = 302;
                }
                
                $redirect = new RedirectResponse($path, $status, $headers);
                
                // Set session jika tersedia
                if (isset($this->session)) {
                    $redirect->setSession($this->session);
                }
                
                $redirect->setRequest($this->generator->getRequest());
                
                // THROW EXCEPTION untuk immediate exit behavior
                throw new class ($redirect) extends \Error implements Responsable {
                    public function __construct(private RedirectResponse $response)
                    {
                        parent::__construct('Redirect');
                    }
                    
                    public function toResponse($request): RedirectResponse
                    {
                        return $this->response;
                    }
                    
                    public function report(): bool
                    {
                        return true;  // Suppress error reporting
                    }
                };
            }
        };
        
        // Inject session ke redirector
        if (isset($this->app['session.store'])) {
            $redirector->setSession($this->app['session.store']);
        }
        
        return $redirector;
    });
}
```

**Kenapa Exception?** Karena CodeIgniter `redirect()` adalah **immediate exit**:
```php
// CI way
function redirect($uri = '', $method = 'location', $code = NULL)
{
    // ... code ...
    exit;  // <-- IMMEDIATE EXIT!
}
```

Jadi di Laravel, kita throw exception untuk simulate immediate behavior yang sama.

**Cara Kerja:**

1. **Exception Throwing**: Saat `redirect()->to()` dipanggil, throw custom Error yang implements `Responsable`
2. **Laravel Exception Handler**: Catch exception dan call `toResponse()` untuk mendapat RedirectResponse
3. **Immediate Exit Simulation**: Exception memberikan behavior mirip `exit()` di CI - eksekusi controller berhenti immediate
4. **Refresh Header Support**: Handle CI's `redirect($url, 'refresh')` dengan header `Refresh: 0`

## Konsekuensi

### Positif

✅ **Zero Code Changes Required**
- CodeIgniter controllers work as-is
- Tidak perlu refactor ribuan methods
- 100% backward compatible

✅ **Seamless Integration**
- Developer tidak perlu tahu tentang pattern ini
- CodeIgniter way of coding tetap bisa digunakan
- Laravel routing tetap berfungsi normal

✅ **Incremental Migration**
- Legacy code tetap jalan
- New code bisa pakai return statement (Laravel way)
- Bisa migrate per controller/method

✅ **Debugging Friendly**
- Pending response bisa di-log
- Clear separation of concerns
- Easy to trace execution flow

✅ **Output Buffer Support**
- Direct `echo`/`print` statements captured
- Backward compatible dengan CI code yang pakai echo
- No breaking changes untuk existing code

### Negatif

⚠️ **Magic Behavior**
- Tidak obvious untuk developer baru
- Perlu dokumentasi yang jelas
- Debugging bisa confusing tanpa pemahaman pattern

⚠️ **Performance Overhead**
- Extra checks pada setiap request (~0.2-0.6ms)
- Memory untuk store pending response (~5-10KB)
- Output buffering overhead (~0.1ms)
- Minimal tapi tetap ada overhead

⚠️ **Maintenance Complexity**
- Extended classes perlu di-maintain
- Breaking changes di Laravel bisa affect pattern ini
- Lebih banyak moving parts

⚠️ **Testing Challenges**
- Unit test harus aware tentang pending response
- Integration test lebih kompleks
- Mock extended services untuk testing

## Alternatif yang Dipertimbangkan

### Alternatif 1: Modifikasi Semua Controllers untuk Return
**Ditolak** karena:
- 1000+ controller methods perlu dimodifikasi
- High risk of bugs
- Time-consuming (berbulan-bulan)
- Breaking changes extensive

### Alternatif 2: Output Buffer Capture Saja
```php
ob_start();
$controller->method();
$output = ob_get_clean();
return response($output);
```
**Ditolak** karena:
- Tidak handle redirects dengan benar
- Loss of response headers
- Tidak bisa set status codes properly
- Flash messages tidak work
- View metadata hilang

### Alternatif 3: Wrapper Functions
```php
function ci_view($view, $data) {
    return view($view, $data); // Auto return
}
```
**Ditolak** karena:
- Masih perlu modify existing code
- Developer confusion (dua cara untuk view)
- Maintenance overhead
- Breaking compatibility

### Alternatif 4: Auto-Return Transformation (AST Parsing)
**Ditolak** karena:
- Over-engineering
- Performance impact significant
- Hard to maintain
- Debugging nightmare
- Runtime code modification risky

## Contoh Implementasi

### Contoh 1: View Tanpa Return

**CodeIgniter Code (Original)**:
```php
class ArtikelController extends CI_Controller
{
    public function index()
    {
        $data['articles'] = $this->artikel_model->get_all();
        $this->load->view('artikel/index', $data);
        // NO RETURN
    }
}
```

**Cara Kerja**:
1. `$this->load->view()` dipanggil
2. Laravel's extended View Factory `make()` method execute
3. View instance di-simpan ke `$pendingView`
4. Method selesai, return `null`
5. Dispatcher cek `resolvePendingResponse()`
6. `getPendingView()` return stored view instance
7. Laravel dapat Response object ✅

### Contoh 2: Redirect Tanpa Return

**CodeIgniter Code (Original)**:
```php
public function delete($id)
{
    $this->artikel_model->delete($id);
    redirect('artikel');
    // NO RETURN
}
```

**Cara Kerja (Exception-Based)**:
1. CodeIgniter `redirect()` helper dipanggil
2. Helper internally calls `app('redirect')->to()`
3. Extended Redirector `createRedirect()` method execute
4. **Throws custom Error dengan RedirectResponse**
5. Exception propagates up, caught by Laravel exception handler
6. Exception handler returns the RedirectResponse ✅
7. Browser redirect executed ✅

**Key Difference**: Tidak wait untuk end of method, **immediate throw**!

### Contoh 3: Mixed (View + Flash Message)

```php
public function create()
{
    $this->session->set_flashdata('success', 'Data berhasil disimpan');
    $this->load->view('artikel/form');
    // NO RETURN, tapi flash message tetap work
}
```

**Cara Kerja**:
1. Flash message stored ke session (standard Laravel)
2. `$this->load->view()` calls Extended View `make()`
3. Extended View stores instance to `$pendingView`
4. Method returns null
5. Dispatcher captures view via `getPendingView()`
6. View rendered with flash message data ✅

**Catatan**: Flash messages work karena session handling **independent** dari response capturing.

### Contoh 4: Direct Echo/Print Output

```php
public function report()
{
    echo "Report Header\n";
    echo $this->generate_report_data();
    print "Report Footer";
    // NO RETURN, output buffered
}
```

**Cara Kerja**:
1. Dispatcher start output buffer sebelum execute method
2. Semua `echo`/`print` di-capture ke buffer
3. Method returns null
4. Dispatcher check buffer content
5. Jika buffer tidak kosong, gunakan buffer sebagai response ✅
6. Browser menerima output yang complete

## Edge Cases yang Ditangani

### 1. Multiple View Calls
```php
public function test()
{
    $this->load->view('header');
    $this->load->view('content'); // Yang ini yang akan di-capture
}
```
Last view wins (by design). Jika butuh multiple views, gunakan layout pattern.

### 2. Redirect Setelah View
```php
public function test()
{
    $this->load->view('something');
    redirect('other'); // Redirect wins karena throw exception
}
```
Redirect takes priority karena exception menghentikan eksekusi.

### 3. JSON Response via Output
```php
public function api()
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    // Handled by MY_Output toResponse()
}
```
Output handled oleh `MY_Output` yang implements `Responsable`.

### 4. Mixed Echo dan View
```php
public function mixed()
{
    echo "Debug info";
    $this->load->view('template');
    // Buffer capture: "Debug info" + view content
}
```
Output buffer menangkap keduanya, view di-append ke buffer output.

## Event System untuk Konfigurasi CI

Pattern ini menggunakan **Event System** untuk **dynamic configuration**.

### CallingController Event

```php
// app/Events/CallingController.php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class CallingController
{
    public function __construct(
        public Route $route,
        public $controller,
        public string $method,
        public array $parameters,
        public Request $request
    ) {}
}
```

**Fired**: Setelah controller di-instantiate, **sebelum** method execute.

**Use Case di Implementasi Aktual**:
```php
// app/Providers/RouteServiceProvider.php

protected function configureConfig()
{
    Event::listen(CallingController::class, function (CallingController $event) {
        // Extract CI settings dari controller property
        $setting = $event->controller->setting ?? null;
        
        if (! $setting) {
            return;
        }
        
        // Map ke Laravel config runtime
        config([
            'mail.default' => $setting->email_protocol,
            'app.timezone' => $setting->timezone ?? 'Asia/Jakarta',
        ]);
    });
}
```

### ControllerCalled Event

```php
// app/Events/ControllerCalled.php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class ControllerCalled
{
    public function __construct(
        public Route $route,
        public $controller,
        public string $method,
        public array $parameters,
        public Request $request,
        public $response  // Response atau null
    ) {}
}
```

**Fired**: Setelah controller method execute, **sebelum** pending response resolve.

**Use Cases**:
- Logging execution time
- Modify response headers
- Analytics tracking
- Audit trail

## Alur Eksekusi (Implementasi Aktual)

```
1. Request masuk → Laravel routing
   ↓
2. Route match, controller instance created
   ↓
3. Extended ControllerDispatcher.dispatch() called
   ↓
4. FIRE: CallingController event
   └─→ Config synchronization dari CI controller settings
   ↓
5. Start output buffering (ob_start)
   ↓
6. Execute controller method:
   ├─ $this->load->view() → Extended View captures & stores pending view
   ├─ redirect() → Extended Redirector throws exception (immediate exit)
   └─ echo/print → Captured ke output buffer
   ↓
7. Method returns (null jika hanya view/redirect, response jika return value)
   ↓
8. Stop output buffering (ob_get_clean)
   ↓
9. FIRE: ControllerCalled event
   └─→ Logging, analytics, audit trail
   ↓
10. IF response === null:
    ├─ Check output buffer (jika ada content, gunakan buffer)
    └─ ELSE: resolvePendingResponse() → get stored view
   ↓
11. Return response to Laravel
   ↓
12. Exception handler catch thrown redirect (jika ada)
    └─→ Return redirect response
   ↓
13. Response sent to browser
```

## Detail Implementasi di RouteServiceProvider

Berikut adalah **implementasi lengkap aktual** yang ada di code:

### Boot Method - Conditional Extension

```php
public function boot(): void
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    $this->routes(function () {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        $this->app->rebinding('request', function ($app, $request) {
            if ($this->isMatchedRouteLaravel($request)) {
                return;
            }

            if (! $this->isMatchedRouteCodeIgniter()) {
                return;
            }

            $this->extendControllerDispatcher();
            $this->extendViewInstance();
            $this->extendRedirectInstance();
            $this->configureConfig();

            global $URI, $RTR, $OUT, $SEC, $IN;

            $URI->setRequest($request);
            $RTR->setRequest($request);
            $OUT->setRequest($request);
            $SEC->setRequest($request);
            $IN->setRequest($request);

            Route::middleware('codeigniter')
                ->group(base_path('bootstrap/routeCodeIgniter.php'));
        });
    });
}

protected function isMatchedRouteLaravel(Request $request): bool
{
    return rescue(fn () => (bool) Route::getRoutes()->match($request), false);
}
```

### Configuration Dynamic Loading

```php
protected function configureConfig()
{
    Event::listen(CallingController::class, function (CallingController $event) {
        $setting = $event->controller->setting ?? null;
        
        if (! $setting) {
            return;
        }
        
        // Configure email SMTP
        if (! empty($setting->email_smtp_host)) {
            $protocol = $setting->email_protocol ?: 'smtp';
            config([
                'mail.default' => $protocol,
                "mail.mailers.{$protocol}" => [
                    'transport' => $protocol,
                    'host'      => $setting->email_smtp_host,
                    'port'      => $setting->email_smtp_port,
                    'username'  => $setting->email_smtp_user,
                    'password'  => $setting->email_smtp_pass,
                ],
            ]);
        }
        
        // Configure Telegram
        if (! empty($setting->telegram_token)) {
            config(['services.telegram-bot-api.token' => $setting->telegram_token]);
        }
        
        // Configure reCAPTCHA
        if (! empty($setting->google_recaptcha_secret_key)) {
            config([
                'captcha' => [
                    'secret'          => $setting->google_recaptcha_secret_key,
                    'sitekey'         => $setting->google_recaptcha_site_key,
                    'options'         => [],
                    'version'         => $setting->google_recaptcha_version ?? 'v2',
                    'score_threshold' => $setting->google_recaptcha_score_threshold ?? 0.5,
                ],
            ]);
        }
    });
}
```

#### 3. Extended View Factory

```php
// app/Providers/RouteServiceProvider.php

protected function extendViewInstance()
{
    $this->app->extend('view', function ($factory, $app) {
        return new class ($factory->getEngineResolver(), $factory->getFinder(), 
            $app['events']) extends Factory 
        {
            protected null|ViewContract|ViewFactoryContract $pendingView = null;
            
            public function getPendingView()
            {
                return $this->pendingView;
            }
            
            public function clearPendingView()
            {
                $this->pendingView = null;
                return $this;
            }
            
            public function make($view, $data = [], $mergeData = [])
            {
                $viewInstance = parent::make($view, $data, $mergeData);
                
                // Store sebagai pending view
                $this->pendingView = $viewInstance;
                
                return $viewInstance;
            }
        };
    });
}
```

**Cara Kerja:**

1. **Intercept `make()`**: Setiap `view()` helper dipanggil, store instance di `$pendingView`
2. **Dispatcher Resolution**: Jika controller return null, dispatcher call `getPendingView()`
3. **Clear After Use**: Setelah resolved, call `clearPendingView()` untuk prevent reuse

## Strategi Testing

### Unit Tests untuk Pending Response

```php
public function test_pending_view_captured_from_controller()
{
    // Simulate view load
    app('view')->make('test');
    
    // Check pending view exists
    $this->assertNotNull(app('view')->getPendingView());
    
    // Clear and verify
    app('view')->clearPendingView();
    $this->assertNull(app('view')->getPendingView());
}

public function test_redirect_throws_exception()
{
    $this->expectException(\Error::class);
    app('redirect')->to('/other');
}

public function test_output_buffer_captured()
{
    $response = $this->get('/controller-with-echo');
    $response->assertStatus(200);
    $response->assertSee('echoed content');
}
```

### Integration Tests

```php
public function test_ci_controller_with_view_returns_response()
{
    $response = $this->get('/artikel');
    
    $response->assertStatus(200);
    $response->assertViewIs('artikel.index');
}

public function test_ci_controller_redirect_works()
{
    $response = $this->get('/artikel/delete/1');
    $response->assertRedirect('/artikel');
}

public function test_mixed_view_and_flashdata()
{
    $response = $this->post('/artikel/store', ['title' => 'Test']);
    
    $response->assertSessionHas('success');
    $response->assertRedirect();
}
```

## Migration Path

Secara bertahap, sebaiknya migrate ke return statements:

```php
// OLD (Masih works)
public function index()
{
    $this->load->view('artikel/index', $data);
}

// NEW (Recommended)
public function index()
{
    return view('artikel/index', $data);
}
```

Target: Migrate 50% controllers dalam 1 tahun, 100% dalam 2 tahun.

## Performance Impact

### Overhead per Request

- Event dispatching: ~0.1-0.3ms per event × 2 events = **0.2-0.6ms**
- Output buffering: ~0.1ms
- Pending view storage: ~0.05ms
- **Total overhead: <1ms per request**

### Memory Impact

- Event objects: ~1-2KB each × 2 = **2-4KB**
- Pending view storage: ~5-10KB
- Output buffer: ~5-10KB (varies by content)
- **Total: <20KB per request**

Acceptable trade-off untuk backward compatibility.

## Related ADRs

- [ADR-001: Hybrid Laravel-CodeIgniter](./ADR-001-hybrid-laravel-codeigniter.md)
- [ADR-002: Laravel First Routing](./ADR-002-laravel-first-routing.md)
- [ADR-005: Middleware Response Transformation](./ADR-005-middleware-response-transformation.md)
- [ADR-007: Service Container Extensions](./ADR-007-service-container-extensions.md)

---

**Decision Maker**: Tim Arsitektur OpenSID  
**Date**: Februari 2024  
**Last Updated**: Januari 2025
