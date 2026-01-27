<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Internal Server Error</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .error-header {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .error-icon {
            width: 48px;
            height: 48px;
            background-color: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .error-icon svg {
            width: 24px;
            height: 24px;
            color: #dc2626;
        }

        .error-title {
            flex: 1;
        }

        .error-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .error-title p {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .copy-button {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .copy-button:hover {
            background-color: #e5e7eb;
        }

        .error-details {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .exception-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .exception-file {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
            font-family: 'Courier New', monospace;
        }

        .exception-message {
            font-size: 1rem;
            color: #374151;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-left: 4px solid #dc2626;
            border-radius: 4px;
        }

        .meta-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-laravel {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-php {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .request-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .method-badge {
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .method-get {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .method-post {
            background-color: #d1fae5;
            color: #065f46;
        }

        .stack-trace {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stack-trace h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .trace-item {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 0;
        }

        .trace-item:last-child {
            border-bottom: none;
        }

        .trace-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            cursor: pointer;
            user-select: none;
        }

        .trace-number {
            background-color: #f3f4f6;
            color: #6b7280;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 2rem;
            text-align: center;
        }

        .trace-content {
            flex: 1;
        }

        .trace-file {
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: #dc2626;
            margin-bottom: 0.25rem;
        }

        .trace-call {
            font-size: 0.875rem;
            color: #374151;
        }

        .trace-code {
            margin-top: 0.75rem;
            background-color: #1f2937;
            border-radius: 6px;
            padding: 1rem;
            overflow-x: auto;
            display: none;
        }

        .trace-code.active {
            display: block;
        }

        .code-line {
            font-family: 'Courier New', monospace;
            font-size: 0.813rem;
            color: #9ca3af;
            padding: 0.125rem 0;
            white-space: pre;
        }

        .code-line.highlight {
            background-color: #7f1d1d;
            color: #fca5a5;
            margin: 0 -1rem;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .line-number {
            display: inline-block;
            width: 3rem;
            color: #6b7280;
            user-select: none;
        }

        @media (max-width: 768px) {
            .error-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .meta-info {
                flex-direction: column;
                gap: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Error Header -->
        <div class="error-header">
            <div class="error-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="error-title">
                <h1>Internal Server Error</h1>
                <p>An unexpected error occurred while processing your request</p>
            </div>
            <button class="copy-button" onclick="copyErrorDetails()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Copy as Markdown
            </button>
        </div>

        <!-- Error Details -->
        <div class="error-details">
            @if(isset($exception))
                <div class="exception-name">{{ get_class($exception) }}</div>
                <div class="exception-file">{{ $exception->getFile() }}:{{ $exception->getLine() }}</div>
                <div class="exception-message">{{ $exception->getMessage() }}</div>
            @else
                <div class="exception-name">Server Error</div>
                <div class="exception-message">An internal server error occurred.</div>
            @endif

            <div class="meta-info">
                <div class="meta-item">
                    <span class="badge badge-laravel">LARAVEL {{ app()->version() }}</span>
                </div>
                <div class="meta-item">
                    <span class="badge badge-php">PHP {{ PHP_VERSION }}</span>
                </div>
                <div class="meta-item">
                    <span class="badge badge-error">CODE 500</span>
                </div>
            </div>

            <div class="request-info">
                <span class="method-badge method-{{ strtolower(request()->method()) }}">
                    {{ request()->method() }}
                </span>
                <span>{{ request()->fullUrl() }}</span>
            </div>
        </div>

        <!-- Stack Trace -->
        @if(isset($exception))
            <div class="stack-trace">
                <h2>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exception trace
                </h2>

                @foreach($exception->getTrace() as $index => $trace)
                    <div class="trace-item">
                        <div class="trace-header" onclick="toggleTrace({{ $index }})">
                            <div class="trace-number">{{ $index + 1 }}</div>
                            <div class="trace-content">
                                @if(isset($trace['file']))
                                    <div class="trace-file">{{ $trace['file'] }}:{{ $trace['line'] ?? '?' }}</div>
                                @endif
                                <div class="trace-call">
                                    @if(isset($trace['class']))
                                        {{ $trace['class'] }}{{ $trace['type'] ?? '::' }}
                                    @endif
                                    {{ $trace['function'] }}()
                                </div>
                                @if(isset($trace['file']) && isset($trace['line']))
                                    <div class="trace-code" id="trace-{{ $index }}">
                                        @php
                                            $file = $trace['file'];
                                            $line = $trace['line'];
                                            if (file_exists($file)) {
                                                $lines = file($file);
                                                $start = max(0, $line - 6);
                                                $end = min(count($lines), $line + 5);
                                                for ($i = $start; $i < $end; $i++) {
                                                    $current = $i + 1;
                                                    $isHighlight = $current === $line;
                                                    echo '<div class="code-line' . ($isHighlight ? ' highlight' : '') . '">';
                                                    echo '<span class="line-number">' . $current . '</span>';
                                                    echo htmlspecialchars(rtrim($lines[$i]));
                                                    echo '</div>';
                                                }
                                            }
                                        @endphp
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($exception->getTrace()) > 10)
                    <div style="text-align: center; margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">
                        {{ count($exception->getTrace()) - 10 }} more vendor frames
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script>
        function toggleTrace(index) {
            const trace = document.getElementById('trace-' + index);
            if (trace) {
                trace.classList.toggle('active');
            }
        }

        function copyErrorDetails() {
            @if(isset($exception))
                            const markdown = `# {{ get_class($exception) }}

                **File:** {{ $exception->getFile() }}:{{ $exception->getLine() }}

                **Message:** {{ $exception->getMessage() }}

                **Request:** {{ request()->method() }} {{ request()->fullUrl() }}

                **Environment:**
                - Laravel: {{ app()->version() }}
                - PHP: {{ PHP_VERSION }}

                ## Stack Trace

                @foreach($exception->getTrace() as $index => $trace)
                    {{ $index + 1 }}. @if(isset($trace['file'])){{ $trace['file'] }}:{{ $trace['line'] ?? '?' }}@endif
                       @if(isset($trace['class'])){{ $trace['class'] }}{{ $trace['type'] ?? '::' }}@endif{{ $trace['function'] }}()
                @endforeach
                `;
            @else
                            const markdown = `# Server Error

                **Message:** An internal server error occurred.

                **Request:** {{ request()->method() }} {{ request()->fullUrl() }}
                `;
            @endif

            navigator.clipboard.writeText(markdown).then(() => {
                const button = event.currentTarget;
                const originalText = button.innerHTML;
                button.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                button.style.backgroundColor = '#d1fae5';
                button.style.color = '#065f46';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.backgroundColor = '';
                    button.style.color = '';
                }, 2000);
            });
        }
    </script>
</body>

</html>