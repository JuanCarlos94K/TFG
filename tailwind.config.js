module.exports = {
  content: ['./templates/**/*.twig', './assets/**/*.js'],
  theme: {
    extend: {
      fontFamily: {
        serif: ['Merriweather', 'Georgia', 'serif'],
      },
      colors: {
        background: '#f9f8f6', // más cálido que blanco puro
        primary: '#222222', // casi negro
        secondary: '#555555', // gris oscuro para textos secundarios
        accent: '#9c6644', // tono marrón cálido, similar a wanderinginn
      },
      spacing: {
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
      },
      boxShadow: {
        'card': '0 4px 6px rgba(0, 0, 0, 0.1)',
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
}