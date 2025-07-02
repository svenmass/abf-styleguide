import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: 'wp-content/themes/abf-styleguide',
  build: {
    outDir: 'dist',
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'wp-content/themes/abf-styleguide/assets/scss/main.scss'),
      },
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@use "variables" as *;`,
      },
    },
  },
}); 