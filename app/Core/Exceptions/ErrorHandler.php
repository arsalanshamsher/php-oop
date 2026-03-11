<?php

namespace App\Core\Exceptions;

class ErrorHandler
{
    public static function handle(\Throwable $exception)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        http_response_code(500);

        $trace = $exception->getTrace();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();
        $class = get_class($exception);

        $snippet = self::getCodeSnippet($file, $line);

        echo self::renderHtml($class, $message, $file, $line, $snippet, $trace);
        exit;
    }

    public static function handleError($level, $message, $file, $line)
    {
        if (!(error_reporting() & $level)) {
            return;
        }
        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handle(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

    private static function getCodeSnippet($file, $line, $radius = 8)
    {
        if (!file_exists($file) || !is_readable($file)) return null;

        $lines = file($file);
        $start = max(0, $line - $radius);
        $end = min(count($lines), $line + $radius);

        $snippet = [];
        for ($i = $start; $i < $end; $i++) {
            $snippet[$i + 1] = $lines[$i];
        }

        return $snippet;
    }

    private static function renderHtml($class, $message, $file, $line, $snippet, $trace)
    {
        $classTitle = substr($class, strrpos($class, '\\') + 1);
        $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $safeFile = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');

        $html = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$classTitle}: {$safeMessage}</title>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Fira+Code:wght@400;500&display=swap' rel='stylesheet'>
    <style>
        :root {
            --bg: #09090b;
            --card: #18181b;
            --border: #27272a;
            --text: #fafafa;
            --muted: #a1a1aa;
            --accent: #ef4444;
            --accent-soft: rgba(239, 68, 68, 0.1);
            --highlight: #3f3f46;
            --info: #3b82f6;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; overflow-x: hidden; }
        .container { display: flex; height: 100vh; }
        
        /* Sidebar (Stack Trace) */
        .sidebar { width: 400px; border-right: 1px solid var(--border); overflow-y: auto; background: #0f0f12; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid var(--border); background: rgba(0,0,0,0.2); position: sticky; top: 0; z-index: 10; }
        .sidebar-header h3 { margin: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); }
        
        .trace-item { padding: 16px 20px; border-bottom: 1px solid var(--border); cursor: pointer; transition: all 0.2s; position: relative; }
        .trace-item:hover { background: var(--card); }
        .trace-item.active { background: var(--card); border-left: 3px solid var(--accent); }
        .trace-index { font-family: 'Fira Code', monospace; color: var(--muted); font-size: 12px; margin-bottom: 4px; }
        .trace-func { font-weight: 700; color: var(--info); font-size: 14px; word-break: break-all; }
        .trace-file { display: block; filter: brightness(0.8) contrast(1.2); font-size: 12px; margin-top: 4px; color: var(--muted); word-break: break-all; }

        /* Main Content */
        .main { flex: 1; overflow-y: auto; display: flex; flex-direction: column; background: var(--bg); }
        .hero { padding: 60px 40px; border-bottom: 1px solid var(--border); background: linear-gradient(to bottom right, #111, #09090b); }
        .exception-badge { display: inline-block; background: var(--accent-soft); color: var(--accent); font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 99px; margin-bottom: 16px; border: 1px solid rgba(239, 68, 68, 0.2); }
        .message { font-size: 40px; font-weight: 800; margin: 0 0 20px 0; line-height: 1.2; letter-spacing: -0.02em; }
        .location { font-family: 'Fira Code', monospace; font-size: 14px; color: var(--muted); display: flex; align-items: center; gap: 10px; }
        .location span { background: var(--card); padding: 4px 12px; border-radius: 6px; border: 1px solid var(--border); }

        .content { padding: 40px; }
        .tabs { display: flex; gap: 24px; border-bottom: 1px solid var(--border); margin-bottom: 32px; }
        .tab { padding: 12px 0; color: var(--muted); font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: 0.2s; font-size: 15px; }
        .tab.active { color: var(--text); border-bottom-color: var(--accent); }

        .code-container { background: var(--card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 40px; }
        .code-header { padding: 16px 24px; background: rgba(0,0,0,0.2); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .code-title { font-family: 'Fira Code', monospace; font-size: 13px; color: var(--muted); }
        
        .code-body { padding: 24px 0; font-family: 'Fira Code', monospace; font-size: 14px; overflow-x: auto; }
        .code-line { display: flex; padding: 0 24px; transition: 0.1s; position: relative; }
        .code-line:hover { background: rgba(255,255,255,0.03); }
        .code-line.active { background: #2d1a1a; }
        .code-line.active::after { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--accent); }
        .ln { width: 50px; color: #52525b; text-align: right; padding-right: 24px; user-select: none; }
        .code-content { color: #e4e4e7; white-space: pre; }
        .code-line.active .code-content { color: #fff; font-weight: 500; }

        .data-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; }
        .data-card { background: var(--card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
        .data-header { padding: 12px 20px; background: rgba(0,0,0,0.2); border-bottom: 1px solid var(--border); font-weight: 600; color: var(--muted); font-size: 13px; text-transform: uppercase; }
        .data-body { padding: 20px; font-family: 'Fira Code', monospace; font-size: 13px; color: #d4d4d8; max-height: 400px; overflow-y: auto; white-space: pre-wrap; }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--highlight); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #52525b; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='sidebar'>
            <div class='sidebar-header'>
                <h3>Stack Trace</h3>
            </div>";
        
        foreach ($trace as $i => $t) {
            $func = ($t['class'] ?? '') . ($t['type'] ?? '') . $t['function'];
            $fileStr = ($t['file'] ?? 'unknown');
            $lineStr = ($t['line'] ?? '');
            $activeClass = ($i === 0) ? 'active' : '';
            
            $html .= "<div class='trace-item {$activeClass}'>
                <div class='trace-index'>#{$i}</div>
                <div class='trace-func'>{$func}</div>
                <div class='trace-file'>{$fileStr}:{$lineStr}</div>
            </div>";
        }

        $html .= "</div>
        <div class='main'>
            <div class='hero'>
                <div class='exception-badge'>{$classTitle}</div>
                <h1 class='message'>{$safeMessage}</h1>
                <div class='location'>
                    <span>{$safeFile}</span>
                    <span>Line {$line}</span>
                </div>
            </div>

            <div class='content'>
                <div class='tabs'>
                    <div class='tab active'>Context</div>
                    <div class='tab'>Request</div>
                    <div class='tab'>Environment</div>
                </div>

                <div class='code-container'>
                    <div class='code-header'>
                        <div class='code-title'>{$safeFile}</div>
                    </div>
                    <div class='code-body'>";

        if ($snippet) {
            foreach ($snippet as $ln => $content) {
                $active = ($ln == $line) ? 'active' : '';
                $safeContent = htmlspecialchars($content);
                $html .= "<div class='code-line {$active}'><span class='ln'>{$ln}</span><span class='code-content'>{$safeContent}</span></div>";
            }
        } else {
            $html .= "<div style='padding: 20px; color: var(--muted);'>Code snippet not available for this file.</div>";
        }

        $html .= "</div>
                </div>

                <div class='data-grid'>
                    <div class='data-card'>
                        <div class='data-header'>GET DATA</div>
                        <div class='data-body'>" . (empty($_GET) ? '[]' : htmlspecialchars(print_r($_GET, true))) . "</div>
                    </div>
                    <div class='data-card'>
                        <div class='data-header'>POST DATA</div>
                        <div class='data-body'>" . (empty($_POST) ? '[]' : htmlspecialchars(print_r($_POST, true))) . "</div>
                    </div>
                    <div class='data-card'>
                        <div class='data-header'>SESSION DATA</div>
                        <div class='data-body'>" . (empty($_SESSION ?? []) ? '[]' : htmlspecialchars(print_r($_SESSION, true))) . "</div>
                    </div>
                    <div class='data-card'>
                        <div class='data-header'>SERVER DATA</div>
                        <div class='data-body'>" . htmlspecialchars(print_r($_SERVER, true)) . "</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple tab switching logic could be added here if needed
    </script>
</body>
</html>";
        return $html;
    }
}
