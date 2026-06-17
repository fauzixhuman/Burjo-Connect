import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
  plugins: [react()],
  build: {
    outDir: 'public/dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        checkout: path.resolve(__dirname, 'src/checkout.jsx')
      }
    }
  },
  server: {
    origin: 'http://localhost:5173',
    cors: true,
  }
});
