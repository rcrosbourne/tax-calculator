module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                'island': ['Island Moments', 'cursive']
            }

        },
    },
    plugins: [
        require('@tailwindcss/forms')
    ],
}
