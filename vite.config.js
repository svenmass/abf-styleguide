import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: 'wp-content/themes/abf-styleguide',
  build: {
    outDir: 'assets/css',
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'wp-content/themes/abf-styleguide/assets/scss/main.scss'),
      },
      output: {
        assetFileNames: '[name].[ext]'
      }
    },
  },
}); 