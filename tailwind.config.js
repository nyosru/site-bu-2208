/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        // tw-elements
        './src/**/*.{html,js}',
        './node_modules/tw-elements/dist/js/**/*.js'
    ],
    theme: {
        extend: {},
    },
    plugins: [
        // tw-elements
        require('tw-elements/dist/plugin')
    ],
}