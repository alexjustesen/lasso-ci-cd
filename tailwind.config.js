const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php'
    ],

    theme: {
        extend: {
            colors: {
                emerald: colors.emerald,
            },

            fontFamily: {
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            },

            typography: {
                DEFAULT: {
                    css: {
                        fontFamily: 'Work Sans',

                        h2: {
                            fontWeight: '600',
                        },

                        h3: {
                            fontWeight: '500',
                        }
                    }
                }
            }
        }
    },

    variants: {
        //
    },

    plugins: [
        require('@tailwindcss/typography')
    ]
}
