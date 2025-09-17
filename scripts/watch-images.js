#!/usr/bin/env node

import chokidar from 'chokidar';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Ensure public/images directory exists
const publicImagesDir = path.join(process.cwd(), 'public', 'images');
if (!fs.existsSync(publicImagesDir)) {
    fs.mkdirSync(publicImagesDir, { recursive: true });
}

// Function to copy file
function copyFile(src, dest) {
    const destDir = path.dirname(dest);
    if (!fs.existsSync(destDir)) {
        fs.mkdirSync(destDir, { recursive: true });
    }
    fs.copyFileSync(src, dest);
    console.log(`📁 Copied: ${path.relative(process.cwd(), src)} → ${path.relative(process.cwd(), dest)}`);
}

// Function to remove file
function removeFile(dest) {
    if (fs.existsSync(dest)) {
        fs.unlinkSync(dest);
        console.log(`🗑️  Removed: ${path.relative(process.cwd(), dest)}`);
    }
}

// Function to remove directory if empty
function removeEmptyDir(dirPath) {
    if (fs.existsSync(dirPath) && fs.readdirSync(dirPath).length === 0) {
        fs.rmdirSync(dirPath);
        console.log(`📁 Removed empty directory: ${path.relative(process.cwd(), dirPath)}`);
    }
}

// Watch for changes in resources/images
const watcher = chokidar.watch('resources/images/**/*', {
    ignored: /(^|[\/\\])\../, // ignore dotfiles
    persistent: true,
    ignoreInitial: false
});

console.log('🖼️  Watching for image changes in resources/images/...');
console.log('📁 Images will be automatically copied to public/images/');
console.log('⏹️  Press Ctrl+C to stop watching\n');

watcher
    .on('add', (filePath) => {
        const relativePath = path.relative('resources/images', filePath);
        const destPath = path.join(publicImagesDir, relativePath);
        copyFile(filePath, destPath);
    })
    .on('change', (filePath) => {
        const relativePath = path.relative('resources/images', filePath);
        const destPath = path.join(publicImagesDir, relativePath);
        copyFile(filePath, destPath);
    })
    .on('unlink', (filePath) => {
        const relativePath = path.relative('resources/images', filePath);
        const destPath = path.join(publicImagesDir, relativePath);
        removeFile(destPath);

        // Try to remove empty parent directories
        let parentDir = path.dirname(destPath);
        while (parentDir !== publicImagesDir && parentDir.length > publicImagesDir.length) {
            removeEmptyDir(parentDir);
            parentDir = path.dirname(parentDir);
        }
    })
    .on('error', (error) => {
        console.error('❌ Error watching files:', error);
    });

// Handle graceful shutdown
process.on('SIGINT', () => {
    console.log('\n👋 Stopping image watcher...');
    watcher.close();
    process.exit(0);
});
