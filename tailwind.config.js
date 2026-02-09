/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,vue}',
    ],
    theme: {
        extend: {
            colors: {
                "primary": "#135bec",
                "primary-hover": "#0f4bc2",
                "page-bg": "#f8fafc",
                "surface-card": "#ffffff",
                "surface-hover": "#f1f5f9",
                "text-main": "#0f172a",
                "text-muted": "#64748b",
                "border-light": "#e2e8f0"
            },
            fontFamily: {
                "display": ["Space Grotesk", "Noto Sans", "sans-serif"]
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/container-queries'),
    ],
}
