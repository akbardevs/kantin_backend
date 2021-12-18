// See: https://tailwindcss.com/docs/guides/laravel
const defaultTheme = require("tailwindcss/defaultTheme");


const purgeArr = ["./resources/**/*.blade.php", "./resources/**/*.svelte"];
const alwaysIncludeCss = [
  "bg-lean-blue",
  "lg:h-12",
  "md:grid-cols-2",
  "md:grid-cols-3",
  "-mx-4",
  "mx-4",
  "mx-6",
  "rotate-180",
  "w-20",
  "w-24",
  "w-36",
  "w-44"
];

module.exports = {
  purge: {
    content: purgeArr,
    options: { safelist: alwaysIncludeCss }
  },
  darkMode: false,
  theme: {
    container: {
      center: true,
    },
    // screens: {
    //   'tablet': '500px',
    //   // => @media (min-width: 640px) { ... }

    //   'laptop': '1024px',
    //   // => @media (min-width: 1024px) { ... }

    //   'desktop': '1280px',
    //   // => @media (min-width: 1280px) { ... }
    // },
    fontSize: {
      ...defaultTheme.fontSize,
      "2.5xl": "1.75rem",
      'xs': '.75rem',
       'sm': '.875rem',
       'tiny': '.875rem',
        'base': '1rem',
        'lg': '1.125rem',
        'xl': '1.25rem',
        '2xl': '1.5rem',
       '3xl': '1.875rem',
       '4xl': '2.25rem',
        '5xl': '3rem',
        '6xl': '4rem',
       '7xl': '5rem',
    },
    extend: {
      objectFit: ['hover', 'focus'],
      width: {
        '8/8': '99.9%',
      },
      padding: { "fluid-video": "56.25%" },
      colors: {
        "lean-black": "#272c49",
        "lean-blue": "#00499D",
        "pena-blue": "#2997ab",
        "pena-blue-active": "#005a87",
        "pena-blue-5": "#E6F4F4",
        "lean-orange": "#2997ab",
        gray: {
          50: "#f4f4f6",
          100: "#E9EAED",
          200: "#D4D5DB",
          400: "#A9ABB6",
          500: "#9395A4",
          600: "#7D8092"
        },
        blue: {
          800: "#00499D"
        }
      },
      spacing: {
        "15": "3.75rem",
        "17": "4.25rem",
        "88": "22rem"
      }
      // boxShadow: theme => ({
      //   outline: "0 0 0 2px " + theme("colors.indigo.500")
      // }),
      // fill: theme => theme("colors")
    }
  },
  variants: {
    // fill: ["responsive", "hover", "focus", "group-hover"],
    // textColor: ["responsive", "hover", "focus", "group-hover"],
    // zIndex: ["responsive", "focus"]
  },
  plugins: []
};
