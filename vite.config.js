import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'), // ✅ huruf kecil dan tanpa "/" di depan "src"
    },
  },
  server: {
    host: true,
    port: 6173,
    proxy: {
      '/api': 'http://localhost', // ✅ pakai http jika backend lokal
    },
  },
})
