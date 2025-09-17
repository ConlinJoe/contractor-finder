#!/usr/bin/env node

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Ensure public/images directory exists
const publicImagesDir = path.join(process.cwd(), 'public', 'images');
const resourcesImagesDir = path.join(process.cwd(), 'resources', 'images');

if (!fs.existsSync(publicImagesDir)) {
    fs.mkdirSync(publicImagesDir, { recursive: true });
}

// Function to copy directory recursively
function copyDir(src, dest) {
    if (!fs.existsSync(dest)) {
        fs.mkdirSync(dest, { recursive: true });
    }

    const entries = fs.readdirSync(src, { withFileTypes: true });

    for (let entry of entries) {
        const srcPath = path.join(src, entry.name);
        const destPath = path.join(dest, entry.name);

        if (entry.isDirectory()) {
            copyDir(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
            console.log(`📁 Copied: ${path.relative(process.cwd(), srcPath)} → ${path.relative(process.cwd(), destPath)}`);
        }
    }
}

console.log('🖼️  Syncing images from resources to public directory...');

if (fs.existsSync(resourcesImagesDir)) {
    copyDir(resourcesImagesDir, publicImagesDir);
    console.log('✅ Images synced successfully!');
} else {
    console.log('❌ Resources/images directory not found!');
    process.exit(1);
}

console.log('📁 Images are now available at: /images/');
