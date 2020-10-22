const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
        defaultLineHeights: true,
        standardFontWeights: true
    },
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            }
        },
        typography: {
            default: {
                css: {
                    fontFamily: 'Work Sans',
                    h2: {
                        fontWeight: '600',
                    },
                    h3: {
                        fontWeight: '500'
                    }
                }
            }
        }
    },
    variants: {},
    plugins: [
        require('@tailwindcss/typography')
    ]
}
