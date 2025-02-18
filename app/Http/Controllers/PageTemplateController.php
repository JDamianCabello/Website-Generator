<?php

namespace App\Http\Controllers;

use App\Models\PageTemplate;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageTemplateController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Templates/Index', [
            'templates' => PageTemplate::all(),
        ]);
    }

    public function show(PageTemplate $template, Request $request)
    {
        // Obtener los parámetros de la query como un array
        $customValues = $request->query();
        
        // Renderizar la plantilla con los valores personalizados
        $renderedTemplate = $template->render($customValues);
        
        return Inertia::render('Dashboard/Templates/Show', [
            'template' => $template,
            'defaultValues' => $template->default_values,
            'currentValues' => $customValues ?: $template->default_values, // Usar customValues si existen
            'renderedTemplate' => $renderedTemplate
        ]);
    }

    public function previewDraft(Request $request)
    {
        try {
            $data = $request->validate([
                'template_html'   => 'required|string',
                'fields'          => 'required|json',
                'default_values'  => 'required|json'
            ]);
    
            // Decodificar los campos y valores por defecto
            $fields = json_decode($data['fields'], true);
            $defaultValues = json_decode($data['default_values'], true);
    
            // Procesar cada campo según su tipo, similar al método render()
            $processedValues = [];
            foreach ($fields as $key => $field) {
                if (isset($defaultValues[$key])) {
                    switch ($field['type']) {
                        case 'boolean':
                            $processedValues[$key] = filter_var($defaultValues[$key], FILTER_VALIDATE_BOOLEAN);
                            break;
                        case 'multiselect':
                            $processedValues[$key] = is_string($defaultValues[$key])
                                ? array_filter(array_map('trim', explode(',', $defaultValues[$key])))
                                : (array)$defaultValues[$key];
                            break;
                        default:
                            $processedValues[$key] = $defaultValues[$key];
                    }
                }
            }
    
            // Fusionar los valores procesados con los valores por defecto
            $values = array_merge($defaultValues, $processedValues);
    
            // Crear un directorio temporal si no existe
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }
    
            // Eliminar archivos temporales previos
            foreach (File::glob($tempDir . '/preview_*.blade.php') as $file) {
                File::delete($file);
            }
    
            // Crear archivo temporal para la plantilla
            $tempFileName = 'preview_' . uniqid();
            $tempFilePath = $tempDir . '/' . $tempFileName . '.blade.php';
    
            // Si la plantilla no tiene doctype, envolverla en una estructura HTML básica
            $template = $data['template_html'];
            if (!str_contains($template, '<!DOCTYPE html>')) {
                $template = "<!DOCTYPE html>\n<html>\n<head>\n" .
                            "<meta charset='UTF-8'>\n" .
                            "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n" .
                            "</head>\n<body>\n" . $template . "\n</body>\n</html>";
            }
    
            // Guardar el HTML en el archivo temporal
            File::put($tempFilePath, $template);
    
            try {
                // Renderizar la vista usando el archivo temporal y los valores procesados
                $html = view()->file($tempFilePath, $values)->render();
    
                // Inyectar el CDN de Tailwind justo antes del cierre de </head>
                $cdn = '<script src="https://cdn.tailwindcss.com"></script>';
                $html = str_replace('</head>', $cdn . '</head>', $html);
    
                return response($html);
            } catch (\Throwable $e) {
                \Log::error('Error al renderizar la vista previa: ' . $e->getMessage());
                \Log::error('Template HTML: ' . $template);
                \Log::error('Valores: ' . json_encode($values));
    
                return response()->json([
                    'error'   => 'Error al renderizar la plantilla: ' . $e->getMessage(),
                    'details' => $e->getMessage()
                ], 500);
            } finally {
                // Eliminar el archivo temporal
                if (File::exists($tempFilePath)) {
                    File::delete($tempFilePath);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Error en previewDraft: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Error al procesar la solicitud: ' . $e->getMessage(),
                'details' => $e->getMessage()
            ], 500);
        }
    }
    

    public function create()
    {
        // Tipos de campos disponibles para las plantillas
        $fieldTypes = [
            ['value' => 'text', 'label' => 'Texto'],
            ['value' => 'textarea', 'label' => 'Área de texto'],
            ['value' => 'email', 'label' => 'Email'],
            ['value' => 'url', 'label' => 'URL'],
            ['value' => 'boolean', 'label' => 'Booleano'],
            ['value' => 'multiselect', 'label' => 'Multiselección']
        ];
    
        // Obtén la plantilla Blade desde el archivo en vez de usar un string en el controlador
        $exampleTemplate = view('templates.example')->render();
    
        return Inertia::render('Dashboard/Templates/Create', [
            'fieldTypes'      => $fieldTypes,
            'exampleTemplate' => $exampleTemplate
        ]);
    }
    
    

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:page_templates,name',
            'description' => 'nullable|string',
            'fields' => 'required|array',
            'default_values' => 'nullable|array',
            'template_html' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crear el nombre del archivo blade
        $templateName = Str::slug($data['name']);
        $viewPath = "templates.{$templateName}";
        $fullPath = resource_path("views/templates/{$templateName}.blade.php");

        // Asegurarse de que existe el directorio
        if (!File::exists(resource_path('views/templates'))) {
            File::makeDirectory(resource_path('views/templates'), 0755, true);
        }

        // Guardar el archivo blade
        File::put($fullPath, $data['template_html']);

        // Manejar la subida de la imagen si existe
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('templates', 'public');
            $data['preview_image'] = $imagePath;
        }

        // Guardar en la base de datos
        $data['view_path'] = $viewPath;
        unset($data['template_html']); // Removemos el HTML ya que no lo guardamos en DB

        PageTemplate::create($data);

        return redirect()->route('dashboard.templates.index')
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function update(Request $request, PageTemplate $template)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:page_templates,name,' . $template->id,
            'description' => 'nullable|string',
            'fields' => 'required|array',
            'default_values' => 'nullable|array',
            'template_html' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Actualizar el archivo blade
        $templateName = Str::slug($data['name']);
        $viewPath = "templates.{$templateName}";
        $fullPath = resource_path("views/templates/{$templateName}.blade.php");
        $oldPath = resource_path('views/' . str_replace('.', '/', $template->view_path) . '.blade.php');

        // Si el nombre cambió, eliminar el archivo anterior
        if ($oldPath !== $fullPath && File::exists($oldPath)) {
            File::delete($oldPath);
        }

        // Guardar el nuevo archivo blade
        File::put($fullPath, $data['template_html']);

        // Manejar la subida de la imagen si hay un nuevo archivo
        if ($request->hasFile('image')) {
            if ($template->preview_image) {
                Storage::disk('public')->delete($template->preview_image);
            }
            $imagePath = $request->file('image')->store('templates', 'public');
            $data['preview_image'] = $imagePath;
        }

        // Actualizar en la base de datos
        $data['view_path'] = $viewPath;
        unset($data['template_html']); // Removemos el HTML ya que no lo guardamos en DB

        $template->update($data);

        return redirect()->route('dashboard.templates.index')
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(PageTemplate $template)
    {
        // Eliminar el archivo blade
        $bladePath = resource_path('views/' . str_replace('.', '/', $template->view_path) . '.blade.php');
        if (File::exists($bladePath)) {
            File::delete($bladePath);
        }

        // Eliminar la imagen de preview si existe
        if ($template->preview_image) {
            Storage::disk('public')->delete($template->preview_image);
        }

        // Eliminar el registro de la base de datos
        $template->delete();

        return redirect()->route('dashboard.templates.index')
            ->with('success', 'Plantilla eliminada correctamente.');
    }

    public function preview(PageTemplate $template, Request $request)
    {
        // Obtener los parámetros de la query como un array
        $customValues = $request->query();

        // Renderizar la plantilla sin el layout, solo el contenido
        $html = $template->render($customValues, true);

        // Devolver la respuesta como HTML
        return response($html)->header('Content-Type', 'text/html');
    }


    public function generateWithAI(Request $request, OpenAIService $openAIService)
    {
        $validated = $request->validate([
            'params.type' => 'required|string|in:email,landing,newsletter,form,custom',
            'params.style' => 'required|string|in:modern,minimal,corporate,creative',
            'params.framework' => 'required|string|in:tailwind,bootstrap',
            'params.temperature' => 'numeric|between:0,1',
            'params.user_instructions' => 'nullable|string',
            'fields' => 'array'
        ]);

        $result = $openAIService->generateTemplate($validated['params']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 500);
        }

        return response()->json([
            'success' => true,
            'template' => $result['template']
        ]);
    }
}
