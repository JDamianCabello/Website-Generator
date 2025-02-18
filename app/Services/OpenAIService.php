<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use OpenAI;

class OpenAIService
{
    protected OpenAI\Client $client;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $apiKey = config('services.openai.api_key');
        
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key no configurada');
        }

        $this->client = OpenAI::client($apiKey);
    }

    public function generateTemplate(array $params)
    {
        try {
            Log::info('Iniciando generación con OpenAI', [
                'params' => $params
            ]);

            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo-0125',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt($params['framework'])
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->getUserPrompt($params)
                    ]
                ],
                'temperature' => floatval($params['temperature'] ?? 0.7),
                'max_tokens' => 2000,
                'presence_penalty' => 0.1,
                'frequency_penalty' => 0.1
            ]);

            $template = $response->choices[0]->message->content;
            
            return [
                'success' => true,
                'template' => $this->processTemplate($template, $params['framework'])
            ];

        } catch (\OpenAI\Exceptions\ErrorException $e) {
            Log::error('Error de OpenAI', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'type' => $e->getErrorType()
            ]);

            return [
                'success' => false,
                'error' => $this->getErrorMessage($e)
            ];
        } catch (\Exception $e) {
            Log::error('Error en OpenAI Service', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Error interno al generar el template'
            ];
        }
    }

    private function getSystemPrompt(string $framework): string
    {
        return "Eres un desarrollador web experto y especialista en Laravel. 
        Tu tarea es generar una plantilla Blade para Laravel que combine HTML, 
        JavaScript y CSS de forma coherente. El código debe ser limpio, semántico y responsive, 
        siguiendo las mejores prácticas de desarrollo web, SEO y las convenciones de Laravel. 
        Emplea directivas Blade adecuadas y usa correctamente las clases de {$framework}.
        Las respuestas deben ser SOLO código HTML sin usar bloques de Markdown o etiquetas de formato y sin explicaciones.";
    }

    private function getUserPrompt(array $params): string
    {
        $type = $params['type'];
        $style = $params['style'];
        $framework = $params['framework'];
        $aditionalInfo = $params['user_instructions'] ?? '';
    
        $typePrompts = [
            'landing' => "Una landing page de alto impacto que incluya una sección principal (hero) con un mensaje claro y un llamado a la acción, seguida de secciones detalladas de características del producto o servicio, testimonios de clientes y un formulario de contacto interactivo.",
            'email' => "Una plantilla de email marketing profesional y responsiva, que cuente con un encabezado visualmente atractivo, un cuerpo central con contenido relevante y un pie de página con enlaces e información adicional, optimizada para dispositivos móviles.",
            'newsletter' => "Una newsletter moderna y visualmente atractiva, estructurada en múltiples columnas para presentar noticias, actualizaciones y artículos destacados, complementada con espacios para imágenes llamativas y elementos gráficos.",
            'form' => "Un formulario de contacto completo y fácil de usar, diseñado para recopilar información del usuario mediante validaciones en tiempo real, que incluya diversos tipos de campos (texto, email, selección, etc.) y un diseño intuitivo y accesible.",
            'custom' => "Una página HTML personalizada y flexible, adaptada a requerimientos específicos, que combine elementos estructurales y de diseño innovador según las necesidades del proyecto."
        ];
    
        $stylePrompts = [
            'modern' => "diseño moderno con espaciado generoso, sombras suaves y bordes redondeados",
            'minimal' => "diseño minimalista con tipografía clara y mucho espacio en blanco",
            'corporate' => "diseño corporativo profesional con colores sobrios y estructura formal",
            'creative' => "diseño creativo con elementos visuales únicos y disposición dinámica"
        ];
    
        $typeDescription = $typePrompts[$type] ?? $typePrompts['landing'];
        $styleDescription = $stylePrompts[$style] ?? $stylePrompts['modern'];
    
        $prompt = "Genera un {$typeDescription} con {$styleDescription} usando {$framework}.
            El código debe ser:
            - Responsive y optimizado para móviles
            - Usar elementos semánticos HTML5
            - Implementar las mejores prácticas de SEO
            - Usar exclusivamente clases de {$framework}
            - No incluir JavaScript ni CSS adicional
            - No se pueden usar @extends, @yield, @section, etc. Tiene que ser HTML plano
            - Seguir las últimas tendencias de diseño web";
    
        if (!empty($aditionalInfo)) {
            $prompt .= "\nInstrucciones adicionales: " . $aditionalInfo;
        }

        Log::info($prompt);
    
        return $prompt;
    }
    

    private function processTemplate(string $template, string $framework): string
    {

        // Remover delimitadores de bloque de código en Markdown si están presentes
        $template = preg_replace('/^```(html)?\s*/i', '', $template);
        $template = preg_replace('/\s*```$/', '', $template);

        if (!str_contains($template, '<!DOCTYPE html>')) {
            $cdnLink = $framework === 'tailwind' 
                ? '<script src="https://cdn.tailwindcss.com"></script>'
                : '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';

            $template = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Template generado automáticamente">
    <title>Template Generado</title>
    {$cdnLink}
</head>
<body>
    {$template}
</body>
</html>
HTML;
        }

        return $template;
    }

    private function getErrorMessage(\OpenAI\Exceptions\ErrorException $e): string
    {
        $errorMessages = [
            'invalid_request_error' => 'La solicitud no es válida',
            'authentication_error' => 'Error de autenticación con OpenAI',
            'permission_error' => 'No tienes permisos para realizar esta acción',
            'rate_limit_error' => 'Se ha alcanzado el límite de solicitudes',
            'quota_exceeded_error' => 'Se ha excedido la cuota de uso',
            'server_error' => 'Error en los servidores de OpenAI'
        ];

        return $errorMessages[$e->getErrorType()] ?? 'Error al generar el template: ' . $e->getMessage();
    }
}